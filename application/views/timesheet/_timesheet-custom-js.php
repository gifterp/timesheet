<?php
/**
 * Timesheet Custom JavaScript
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

// Has the function used to create the settings object
require_once ( $_SERVER['DOCUMENT_ROOT'] . '/assets-system/php/timesheet-functions.php' );
?>
      <!-- Custom -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/timesheet/timesheet-modals.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/timesheet/timesheet-entry.js"></script>

      <script type="text/javascript">

         var loginUser = {                                                    // Logged in user of system
            <?php echo "id: \"" . $_SESSION["userId"] . "\",
            fullName: \"" . $_SESSION["fullName"] . "\",
            accessLevel: \"" . $_SESSION["accessLevel"] . "\"\n"; ?>
         };

         var displayUser;                                                     // User being shown on the diary
         var interval = 0, failCount = 0; deleteFlag = true;                  // Timeout and count for ajax error handling. Flag to stop delete twice on dragging to bin
         var sun = 0, mon = 0, tue = 0, wed = 0, thu = 0, fri = 0, sat = 0, showTotals = false;   // Holds total hours for each day

         // Timesheet settings object - Stores the settings for fullCalendar and also any CI language variables used
         <?php print_timesheet_settings( $timesheetSettings ); ?>


         $( document ).ready( function() {

            // Initially set timesheet user shown to the logged in user of the system
            changeDiaryUser( loginUser.id, loginUser.fullName, loginUser.accessLevel, false )  // False, don't refetch as calender not yet loaded (date not used)
            $( "#timesheet-user-switcher" ).val( displayUser.id + '|#|' + displayUser.fullName + '|#|' + displayUser.accessLevel ).trigger( "change" ); // Initially set our timesheet user switcher to current user

            // Initialise our Chosen select inputs
            $( ".chosen-select" ).chosen({
               width: "80%"
            });
            $( ".chosen-select-edit" ).chosen({
               width: "100%"
            });

            // Limit numeric input fields
            $( ".numeric" ).numeric();


            // Calendar begin
            var $calendar = $( '#calendar' );
            var date = new Date(),
                d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();

            var entryData;    // Entry object written to calendar


            // Enable diary entries to be copied by holding down SHIFT
            // Enable them to be cut by holding down ALT+X
            var copyKey = false,
                cutKey  = false,
                xKey    = 88;
            $( document ).keydown( function( e ) {
               if ( e.altKey && e.keyCode == xKey ) {
                  cutKey  = true;
               } else { copyKey = e.shiftKey; }
            }).keyup( function () {
               copyKey = false;
               cutKey  = false;
            });


            // Create the calendar
            var calendarSettings = {
               header: {
                  left: 'title',
                  right: 'prev,next,today,agendaDay,agendaWeek'
               },
               defaultView: TS.defaultView,
               scrollTime: TS.scrollTime,
               scrollable: true,
               allDaySlot: false,
               titleFormat: {
                  month: 'MMMM YYYY',      // September 2016
                  week: "MMM D YYYY",      // Sep 13 2016
                  day: 'dddd, MMMM D, YYYY' // Tuesday, Sep 6, 2016
               },
               timeFormat: TS.timeFormat,
               axisFormat: TS.timeFormat,
               views: {
                  week: {
                     columnFormat: TS.weekViewColumnFormat
                  },
                  day: {
                     columnFormat: TS.dayViewColumnFormat
                  }
               },
               businessHours: {
                  start: TS.businessHoursStart,
                  end: TS.businessHoursEnd,
                  dow: TS.businessDaysOfWeek
               },
               slotDuration: TS.slotDuration,
               slotLabelInterval: TS.slotLabelInterval,
               selectable: displayUser.canEdit,
               selectHelper: true,
               select: function( start, end ) {

                  // If the event spans multiple days don't allow the selection
                  if ( TS.multiDaySelectDisabled && TE.isMultiDay( start, end ) ) {
                     $calendar.fullCalendar( 'unselect' );
                  } else {
                     var entryObject = {
                        start: start,
                        end: end
                     };
                     TE.addFromSelect( entryObject, $calendar, cutKey );
                  }
               },
               editable: displayUser.canEdit,
               eventLimit: true,
               droppable: true,
               eventDrop: function( event, delta, revertFunc, jsEvent, ui, view ) {

                  // If the event spans multiple days don't allow it to be dropped and revert back
                  if ( TS.multiDaySelectDisabled && TE.isMultiDay( event.start, event.end ) ) {
                     // If it is a clone then delete it
                     if (event._id == 'cloned') $calendar.fullCalendar('removeEvents', event._id);
                     revertFunc();


                  } else {
                     if ( copyKey ) {
                        TE.cloneEntry( event, revertFunc );
                     } else {
                        // Check if entry dragged to the bin for deletion
                        TE.dragEventToBinAndDelete( event, jsEvent, callbackEdit );
                        // If not deleted, edit the entry
                        function callbackEdit() {
                           TE.updateEntryTimes( event, revertFunc, cutKey );
                        }
                     }
                  }

               },
               eventResize: function ( event, delta, revertFunc, jsEvent, ui, view ) {
                  // If the event spans multiple days don't allow resizing and revert back
                  if ( TS.multiDaySelectDisabled && TE.isMultiDay( event.start, event.end ) ) {
                     revertFunc();
                  } else {
                     TE.updateEntryTimes( event, revertFunc, cutKey );
                  }
               },
               drop: function( event, delta, revertFunc, jsEvent, ui, view ) {

               },
               eventReceive: function( event ) {  // This function is called when something is dropped from outside the calendar
                  if ( TS.multiDaySelectDisabled && TE.isMultiDay( event.start, event.end )) {
                     $calendar.fullCalendar( 'removeEvents', event._id );
                  } else {
                     switch( event.dropType ) {
                        case 'job':
                           TE.addFromDroppedJob( event, $calendar, cutKey );
                           break;
                        case 'task':
                           TE.addFromDroppedTask( event, $calendar, cutKey );
                           break;
                     }
                  }
               },
               lazyFetching: true,
               events: {
                  url: '<?php echo ROOT_RELATIVE_PATH; ?>timesheet/get-entries-json',
                  cache: false,
                  type: 'POST',
                  dataType: 'json',
                  data: function() { // a function that returns an object
                     return {
                        userId: displayUser.id
                     };
                  },
                  error: function ( request, error ) {
                     if ( ++failCount < 15 ) {
                        interval += 750;
                        setTimeout( $calendar.fullCalendar( 'refetchEvents' ), interval );
                     }
                     if ( failCount == 15 ) {
                        TE.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', 'Multiple errors occured\nDatabase may be open and locked', 'error' );  // Not needed, will fire if switching pages very quickly and script hasn't finished
                     }
                  },
                  success: function( data ) {
                     interval  = 0;
                     failCount = 0;
                     if ( showTotals == true ) {
                        var arrId = new Array();
                        sun = 0, mon = 0, tue = 0, wed = 0, thu = 0, fri = 0, sat = 0
                        $.each( data, function ( key, element ) {
                           arrId.push( TE.addDayTotals( element, arrId ) );  // Day totals to display for each day column
                        });
                        TE.displayDayTotals();
                     }
                  },
                  allDayDefault: false,
                  timezoneParam: 'UTC',
                  eventDataTransform: function ( data ) {
                     $calendar.fullCalendar( 'removeEvents', data.entryId );

                     var entryData = TE.createEntryObject( data );
                     return entryData;
                  }
               },
               loading: function( isLoading, view ) {
                  //if (isLoading) $calendar.fullCalendar('removeEvents'); // Prevent duplicate events, also a fix below for next previous
               },
               eventRender: function( event, element ) {
                  // Add clock face icon before times
                  element.find( '.fc-time' ).prepend( '<span class="fc-task-time"><i class="fa fa-clock-o"></i> </span>' );
                  // Add the entry time taken once it is calculated (It isn't when first selecting)
                  if ( event.timeTaken ) element.find( '.fc-time' ).append( '<span class="fc-task-time"> - ' + event.timeTaken + ' hours</span>' );
                  // Add task name with an arrow icon after the title
                  if ( event.taskName ) element.find( '.fc-title' ).after( '<div class="fc-task"><i class="fa fa-arrow-right"></i> [' + event.taskGroup + '] ' + event.taskName + '</div>' );
                  // Add disbursement after task if ther is one
                  if ( event.disbursement != 0 && event.disbursement != null) element.find( '.fc-task' ).after( '<div class="fc-disbursement"><b>Disbursement:</b> <i class="fa fa-dollar"></i>' + parseFloat( event.disbursement ).toFixed( 2 ) + '</div>' );
                  // Add comment after task if there is one and bump down disbursement
                  if ( event.comment ) element.find( '.fc-task' ).after( '<div class="fc-description"><span class="fc-description-icon"><i class="fa fa-commenting ml-xs mr-xs"></i> </span>' + event.comment.replace( /\n/g," <i class='fa fa-arrow-circle-down'></i> " ) + '</div>' );
               },
               eventDragStop: function ( event, jsEvent, ui, view ) {
                  TE.dragEventToBinAndDelete( event, jsEvent );
               },
               eventDragStart: function ( event, jsEvent, ui, view ) {
                  $( '#calendar' ).fullCalendar( 'removeEvents', 'cloned' );
                  if ( !copyKey ) return;
                  var tempId = event._id + 'temp';

                  var eClone = {
                     _id: tempId,
                     previousId: event._id,
                     entryId: event.entryId,
                     title: event.title,
                     start: event.start,
                     end: event.end,
                     timeTaken: event.timeTaken,
                     userId: event.userId,
                     jobId: event.jobId,
                     jobRef: event.jobRef,
                     jobName: event.jobName,
                     childId: event.childId,
                     childRef: event.childRef,
                     childName: event.childName,
                     taskId: event.taskId,
                     taskName: event.taskName,
                     taskGroup: event.taskGroup,
                     comment: event.comment,
                     disbursement: event.disbursement,
                     color: event.color
                  };
                  $calendar.fullCalendar( 'renderEvent', eClone );

                  // Change our newly created entry to have an id of 'cloned'
                  // Store the original id so we can copy details from the database
                  event.originalId = event._id;
                  event._id = 'cloned';
                  // Revert original entry back as it was
                  var entry = $calendar.fullCalendar( 'clientEvents', tempId );
                  entry[0]._id = entry[0].previousId;
               },
               eventClick: function( calEvent, jsEvent, view ) {
                  // Check if user can edit first
                  if ( displayUser.canEdit ) {
                     $( '#calendar' ).fullCalendar( 'removeEvents', 'cloned' ); // Cleanup in case something has gone amiss when copying and cloned entries exist
                     TE.setEditForm( calEvent, TS.multiDaySelectDisabled );
                     if ( calEvent._id != 'cloned' ) $( 'a.modal-edit-entry-link' ).click();
                  }
               }
            };  // End calendar settings

            // Create the calendar to display timesheet entries
            $calendar.fullCalendar( calendarSettings );







            // Changes who the diary is being displayed for
            function changeDiaryUser( id, fullName, accessLevel, refetchEntries, displayDate ) {

               var canEdit = true; // Currently everyone can edit, need to change so only admins can edit/view others

               // Settings for the user whose timesheet diary is being displayed
               displayUser = {
                  id: id,
                  fullName: fullName,
                  accessLevel: accessLevel,
                  canEdit: canEdit
               };

               // Update the name to match the timesheet being displayed
               $( ".timesheet-full-name" ).text( fullName );

               // Print a warning message if viewing timesheets that are not your own
               if ( displayUser.id != loginUser.id ) {  // Not their timesheet
                  // Non-admins should not be able to view other peoples timesheets. Send them back to their own.
                  if ( accessLevel < 49 ) { window.location.replace( "<?php echo ROOT_RELATIVE_PATH; ?>timesheet" ); }
                  // Print warning message
                  $( '#message-area' ).html( '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times fa-sm"></i></button><strong>Careful!</strong> This isn' + "'t your timesheet it's " + displayUser.fullName + "'s</div>" );
               } else {  // Logged in users timesheet
                  // Clear any warning messages
                  $( '#message-area' ).html( '' );
               }

               // Redraw the timesheet if changing to a different user
               if ( refetchEntries ) {
                  calendarSettings.selectable = canEdit;
                  calendarSettings.editable   = canEdit;
                  calendarSettings.droppable  = canEdit;
                  $calendar.fullCalendar( 'destroy' );
                  $calendar.fullCalendar( calendarSettings );
                  TE.addDayTotalButton( $calendar );   // Add button for daily totals to top button set
                  if ( displayDate ) { $calendar.fullCalendar( 'gotoDate', $.fullCalendar.moment( displayDate ) ) };
                  //$calendar.fullCalendar( 'removeEvents' );
                  //$calendar.fullCalendar( 'refetchEvents' );
               }

               // Loads personal job list for the user being displayed
               loadDraggables();
            }


            // Changes the date displayed for the diary from the datepicker
            $( '#timesheet-datepicker' ).on( 'changeDate', function ( e ) {
               $calendar.fullCalendar( 'gotoDate', $( '#timesheet-datepicker' ).datepicker( 'getFormattedDate' ) )
            });

            // Changes whose diary is being displayed
            $( '#timesheet-user-switcher' ).change( function () {
               var userArray = $( this ).val().split( "|#|" );
               changeDiaryUser( staffArray[0], staffArray[1], staffArray[2], staffArray[3], true );  // True, refetch calendar entries
            });


            // FIX INPUTS TO BOOTSTRAP VERSIONS
            var $calendarButtons = $calendar.find( '.fc-header-right > span' );
            $calendarButtons
               .filter( '.fc-button-prev, .fc-button-today, .fc-button-next' )
               .wrapAll( '<div class="btn-group mt-sm mr-md mb-sm ml-sm"></div>' )
               .parent()
               .after( '<br class="hidden"/>' );

            $calendarButtons
               .not( '.fc-button-prev, .fc-button-today, .fc-button-next' )
               .wrapAll( '<div class="btn-group mb-sm mt-sm"></div>' );

            $calendarButtons
               .attr({ 'class': 'btn btn-sm btn-default' });

            // Fix events being duplicated when changing views using next/prev buttons.
            $( '.fc-button-group button').click( function () {
               $( '.fc-button-group .fc-next-button, .fc-button-group .fc-prev-button, .fc-button-group .fc-today-button' ).prop( 'disabled', true );
               $( '.fc-button-group .fc-next-button, .fc-button-group .fc-prev-button' ).addClass( "fc-state-disabled" );
               setTimeout( function () {
                  $( '.fc-button-group .fc-next-button, .fc-button-group .fc-prev-button, .fc-button-group .fc-today-button' ).prop( 'disabled', false );
                  $( '.fc-button-group .fc-next-button, .fc-button-group .fc-prev-button' ).removeClass( "fc-state-disabled" );
               }, 0 );
            });


            // Gets the personal job list from the database
            // Calls function to make items draggable on the page
            function loadDraggables() {
               $.ajax({
                  url: '<?php echo ROOT_RELATIVE_PATH; ?>timesheet/personal-job-list-html',
                  data: 'userId='+ displayUser.id,
                  type: 'POST',
                  success: function ( html ) {
                     // Display personal list
                     $( "#personal-events" ).html( html );
                     // Reset fail counters if any
                     interval  = 0;
                     failCount = 0;
                     // Make divs on page draggable
                     makeDraggable();
                  },
                  error: function ( request, error ) {
                     if ( ++failCount < 5 ){ // Problem with AJAX call, try again up to 5 times
                        // recursively call function again and extend interval
                        interval += 1000;
                        setTimeout( loadDraggables(), interval );
                     } else { // Problem we can't resolve
                        TE.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang("timesheet_list_personal_notif_error") ?>' + error, 'error' );
                        $( "#personal-events" ).html( '<div class="add-personal-job-helper"><i class="fa fa-exclamation-triangle fa-lg mr-sm"></i><span class="panel-subtitle">Problem opening database</span></div>' );
                        interval  = 0;
                        failCount = 0;
                     }
                  }
               });

            }


            // Prepare draggable items on the page
            function makeDraggable() {
               // DRAGGABLE EVENTS
               $( '.draggable-events div.draggable-event' ).data( 'duration', '01:00' ); // one hour

               $( '.draggable-events div.draggable-event' ).each( function () {

                  // Create an Event Object for the draggable item
                  var entryObject = {
                     _id: 'droppable',
                     title: $( this ).find( 'span.entry-title' ).text(),  // Details are stored in hidden span elements
                     timeTaken: '1',  // 1 hour block
                     userId: displayUser.id,
                     jobId: $( this ).find( 'span.entry-job-id' ).text(),
                     jobRef: $( this ).find( 'span.entry-job-ref' ).text(),
                     jobName: $( this ).find( 'span.entry-job-name' ).text(),
                     childId: $( this ).find( 'span.entry-child-id' ).text(),
                     childRef: $( this ).find( 'span.entry-child-ref' ).text(),
                     childName: $( this ).find( 'span.entry-child-name' ).text(),
                     taskId: $( this ).find( 'span.entry-task-id' ).text(),
                     taskName: $( this ).find( 'span.entry-task-name' ).text(),
                     dropType: $( this ).find( 'span.entry-type' ).text(),  // Job or task
                     stick: true
                  };

                  // store the Event Object in the DOM element so we can get to it later
                  $( this ).data( 'event', entryObject );

                  // make the event draggable using jQuery UI
                  $( this ).draggable({
                     zIndex: 999,
                     helper: 'clone',
                     appendTo: 'body',
                     revert: true,      // Will cause the event to go back to its
                     revertDuration: 0  // original position after the drag
                  });

               });
            }



            // DRAGGABLE PERSONAL LIST
            $( '#droppable-add-job' ).droppable({
               accept: '.draggable-job .draggable-event',
               drop: function ( event, ui ) {
                  var jobId = $( ui.draggable ).find( 'span.entry-job-id' ).text();
                  // If it's a child job then use the correct job ID
                  if ( $( ui.draggable ).find( 'span.entry-child-id' ).text() != '' ) { jobId = $( ui.draggable ).find( 'span.entry-child-id' ).text() }

                  $.ajax({
                     url: '<?php echo ROOT_RELATIVE_PATH; ?>timesheet/save-user-job',
                     data: 'jobId=' + jobId + '&userId=' + displayUser.id + '&title=' + $( ui.draggable ).find( 'span.entry-title' ).text(),
                     type: 'POST',
                     dataType: 'json',
                     success: function ( data ) {
                        loadDraggables();
                        $.each( data, function( key, element ) {
                           UN.displayNotice( '<?php echo lang( "system_notif_success" ); ?>', element.title + '<?php echo lang( 'timesheet_list_personal_notif_add' ); ?>', 'success' );
                        });
                     },
                     error: function ( request, error ) {
                        UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( 'timesheet_list_personal_notif_err_add' ); ?>', 'error' );
                     }
                  });
               }
            });
            $( '#droppable-remove-job' ).droppable({
               accept: '#personal-events .draggable-event',
               drop: function ( event, ui ) {

                  $.ajax({
                     url: '<?php echo ROOT_RELATIVE_PATH; ?>timesheet/delete-user-job',
                     data: 'entryId='+ $( ui.draggable ).find( 'span.entry-db-id' ).text() + '&title='+ $( ui.draggable ).find( 'span.entry-title' ).text(),
                     type: 'POST',
                     dataType: 'json',
                     success: function ( data ) {
                        loadDraggables();
                        $.each( data, function ( key, element ) {
                           UN.displayNotice( '<?php echo lang( "system_notif_success" ); ?>', element.title + '<?php echo lang( 'timesheet_list_personal_notif_delete' ); ?>', 'warning' );
                        });
                     },
                     error: function ( request, error ) {
                        UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( 'timesheet_list_personal_notif_err_del' ); ?>', 'error' );
                     }
                  });
               }
            });

            // Alternate styles for draggable events
            //$('.draggable-events.personal-events div.draggable-event').filter(':even').addClass('draggable-event-alt');

            // Search filters for draggable lists (lists.js)
            // Jobs
            var jobOptions = {
               valueNames: [ 'entry-title', 'entry-job-ref', 'entry-job-name' ]
            };
            var jobList = new List( 'draggable-jobs', jobOptions );
            // Tasks
            var taskOptions = {
               valueNames: [ 'entry-task-name' ]
            };
            var taskList = new List( 'draggable-tasks', taskOptions );

            // Add button to show daily totals
            TE.addDayTotalButton( $calendar );

         });
      </script>