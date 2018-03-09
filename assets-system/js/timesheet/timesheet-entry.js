/**
 * Timesheet Entry Object
 *
 * This object handles the entry and manipulation of the timesheet entries that are on
 * the timesheet diary page. The diary uses the jQuery fullCalendar plugin to display the
 * entries
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

var TE = {

   // When a user selects a time frame on the calendar (with the mouse)
   addFromSelect: function( TE, calendarElement, cutKey ) {
      var _self = this;

      // Open the modal to choose the job for this entry
      $( 'a.modal-choose-job-link' ).click();
      // Open the 'chosen' select element so it is ready for quick search/selection
      setTimeout( function() { $( '.job-chooser.chosen-select' ).trigger( 'chosen:open' ); }, 0 );

      // Listen for escape key and cancel if pressed
      $( "#job-chooser" ).chosen().data( 'chosen' ).container.bind( 'keydown', function( e ) {
         if ( e.which === 27 ) {
            $( '.modal-dismiss' ).click();
            calendarElement.fullCalendar( 'unselect' );
         }
      });

      // On select option change we want to get the values before moving to the next step
      // We also unbind the change or we will get multiple events firing with subsequent use
      $( '#job-chooser' ).unbind( "change" ).change( function() {

         // Split the job chooser value returned into an array
         // Consists of 0 = jobId, 1 = subFileId - We have sub files in the drop down too
         var jobArray = $( this ).val().split( "|#|" );
         TE.jobId = jobArray[0];
         TE.childId = jobArray[1];

         // Reset the 'chosen' select to the empty default option ready for next use
         $( ".chosen-select" ).val( '' ).trigger( "chosen:updated" );
         // Close the job chooser modal
         $( 'button.job-chooser-confirm' ).click();

         TE.taskId = _self.addNewGetTask( TE, calendarElement, false, _self.addNewGetComment, cutKey )

      }); // #job-chooser change event
   },


   // Called when a job is dragged onto the calender
   addFromDroppedJob: function( TE, calendarElement, cutKey ) {
      var _self = this;

      TE.taskId = _self.addNewGetTask( TE, calendarElement, true, _self.addNewGetComment, cutKey );
   },


   // Called when a task is dragged onto the calender
   addFromDroppedTask: function( TE, calendarElement, cutKey ) {
      var _self = this;

      // Open the modal to choose the job for this entry
      $( 'a.modal-choose-job-link' ).click();
      // Open the 'chosen' select element so it is ready for quick search/selection
      setTimeout( function() { $( '.job-chooser.chosen-select' ).trigger( 'chosen:open' ); }, 0 );

      // On select option change we want to get the values before moving to the next step
      // We also unbind the change or we will get multiple events firing with subsequent use
      $( '#job-chooser' ).unbind( "change" ).change( function() {

         // Split the job chooser value returned into an array
         // Consists of 0 = jobId, 1 = subFileId - We have sub files in the drop down too
         var jobArray = $( this ).val().split( "|#|" );
         TE.jobId = jobArray[0];
         TE.childId = jobArray[1];

         // Reset the 'chosen' select to the empty default option ready for next use
         $( ".chosen-select" ).val( '' ).trigger( "chosen:updated" );
         // Close the job chooser modal
         $( 'button.job-chooser-confirm' ).click();

         _self.addNewGetComment( TE, calendarElement, true, _self, cutKey );

      }); // #job-chooser change event
   },


   // Modal popup for task activities when adding a new entry
   addNewGetTask: function(TE, calendarElement, removePreviousEvent, callback, cutKey) {
      var _self = this;

      // Open the modal to choose the task for this entry
      $( 'a.modal-choose-task-link' ).click();
      // Open the 'chosen' select element so it is ready for quick search/selection
      setTimeout( function(){ $( '.task-chooser.chosen-select' ).trigger( 'chosen:open' ); }, 0);

      // Listen for escape key and cancel if pressed
      $( "#task-chooser" ).chosen().data( 'chosen' ).container.bind( 'keydown', function( e ) {
         if ( e.which === 27 ) {
            $( '.modal-dismiss' ).click();
            calendarElement.fullCalendar( 'unselect' );
         }
      });

      // On select option change we want to get the values before moving to the next step
      // We also unbind the change or we will get multiple events firing with subsequent use
      $('#task-chooser').unbind( "change" ).change(function(){
         TE.taskId = $(this).val();

         // Reset the 'chosen' select to the empty default option ready for next use
         $(".chosen-select").val('').trigger("chosen:updated");
         // Close the task chooser modal
         $('button.task-chooser-confirm').click();

         // Now we can call the modal to get the comments
         callback(TE, calendarElement, removePreviousEvent, _self, cutKey);

      }); // #task-chooser change event
   },


   // Modal popup for the comment when adding a new entry
   addNewGetComment: function(TE, calendarElement, removePreviousEvent, _self, cutKey) {

      // Clear the textarea for use
      $( '#initial-comment' ).val( '' );
      // Open the modal to add a comment for this entry
      $( 'a.modal-comment-link' ).click();

      // Wait for the 'Add Entry' button to be clicked (Also TAB or ENTER will trigger click - see diary.modals.js)
      $( 'button.comment-action' ).unbind( "click" ).click( function( e ) {

      TE.comment = $( '#initial-comment' ).val();
         // Close the comment modal
         $( 'button.comment-confirm' ).click();

         // Add date/times etc
         TE.startDateTime = _self.createMySQLDateTime( TE.start );
         TE.endDateTime = _self.createMySQLDateTime( TE.end );
         TE.timeTaken = _self.calcDecimalTimeTaken( TE.start, TE.end );
         TE.userId = displayUser.id;

         _self.addNewEntryToDatabase( TE, calendarElement, removePreviousEvent, cutKey );


      }); // #comment entry
   },


   // Add a new entry to the database once we have created a timesheet entry object
   addNewEntryToDatabase: function( TE, calendarElement, removePreviousEvent, cutKey ) {
      var _self = this;
      _self.removeDayTotals();

      if ( TE.jobId && TE.taskId ) {
         $.ajax({
            url: TS.rootRelativePath + 'timesheet/add-timesheet-entry',
            data: 'jobId=' + TE.jobId + '&childId=' + TE.childId + '&timesheetTaskId=' + TE.taskId + '&startDateTime=' + TE.startDateTime + '&endDateTime=' + TE.endDateTime + '&userId=' + TE.userId + '&totalHours=' + TE.timeTaken + '&comment=' + encodeURIComponent( TE.comment ) + '&cut=' + cutKey,
            type: 'POST',
            dataType: 'json',
            success: function( data ) {

               // A single json object is returned with the entry we just inserted into the database
               $.each( data, function( key, element ) {

                  var entryData = _self.createEntryObject( element );
                  if ( removePreviousEvent ) calendarElement.fullCalendar( 'removeEvents', TE._id );
                  if ( element.haveCut == 'true' ) {  // Refetch all events as others may have been altered
                     $( '#calendar' ).fullCalendar( 'refetchEvents' );
                  } else {               // Just update the event that was altered
                     calendarElement.fullCalendar( 'renderEvent', entryData, true ); // stick? = true */
                  }

                  UN.displayNotice( TS.notifSuccessTitle, TS.notifEntryFor + element.firstName + ' ' + element.surname + TS.notifAddSuccess, 'success' );
               });

            },
            error: function ( request, error ) {
               UN.displayNotice( TS.notifErrorTitle, TS.notifAddError, 'error' );
               calendarElement.fullCalendar( 'removeEvents', TE._id );
            }
         });
      }
      calendarElement.fullCalendar( 'unselect' );
   },


   // Called when an entry is resized or moved to a different slot
   updateEntryTimes: function( event, revertFunc, cutKey ) {
      var _self = this;
      var TE = {
         entryId: event.entryId,
         startDateTime: _self.createMySQLDateTime( event.start ),
         endDateTime: _self.createMySQLDateTime( event.end ),
         timeTaken: _self.calcDecimalTimeTaken( event.start, event.end )
      }
      _self.removeDayTotals();

         return $.ajax({
            url: TS.rootRelativePath + 'timesheet/update-timesheet-entry-times',
            data: 'startDateTime=' + TE.startDateTime + '&endDateTime=' + TE.endDateTime + '&entryId=' + TE.entryId + '&totalHours=' + TE.timeTaken + '&cut=' + cutKey,
            type: 'POST',
            dataType: 'json',
            success: function( data ) {
               event.timeTaken = TE.timeTaken;
               if ( data[0]['haveCut'] == 'true' ) {  // Refetch all events as others may have been altered
                  $( '#calendar' ).fullCalendar( 'refetchEvents' );
               } else {               // Just update the event that was altered
                  $( '#calendar' ).fullCalendar( 'updateEvent', event );
               }
               UN.displayNotice( TS.notifSuccessTitle, TS.notifUpdateTimeSuccess, 'success' );
            },
            error: function ( request, error ) {
               UN.displayNotice( TS.notifErrorTitle, TS.notifUpdateTimeError, 'error' );
               revertFunc();  // Revert entries as db update failed
            }
         });
   },


   // Called when an entry is copied by holding down the shift key
   cloneEntry: function( event, revertFunc ) {
      var _self         = this;
      var startDateTime = _self.createMySQLDateTime( event.start ),
          endDateTime   = _self.createMySQLDateTime( event.end );
      _self.removeDayTotals();

      $.ajax({
         url: TS.rootRelativePath + 'timesheet/clone-timesheet-entry',
         data: 'jobId=' + event.jobId + '&childId=' + _self.nullToZeroStr( event.childId ) + '&timesheetTaskId=' + event.taskId + '&startDateTime=' + startDateTime + '&endDateTime=' + endDateTime + '&userId=' + event.userId + '&totalHours=' + event.timeTaken + '&comment=' + encodeURIComponent( event.comment ) + '&disbursement=' + _self.prepCurrencyValue( event.disbursement ),
         type: 'POST',
         dataType: 'json',
         success: function( data ) {

            // Just need to update the entry id
            // A single json object is returned with the entry we just inserted into the database
            $.each( data, function( key, element ) {
               event._id = element.entryId;
               event.entryId = element.entryId;
            });
            UN.displayNotice( TS.notifSuccessTitle, TS.notifCloneSuccess, 'success' );

         },
         error: function ( request, error ) {
            revertFunc();
            $( '#calendar' ).fullCalendar( 'removeEvents', 'cloned' );
            UN.displayNotice( TS.notifErrorTitle, TS.notifCloneError, 'error' );
         }
      });
   },


   // Delete an entry
   deleteEntry: function( entryId ) {
      var _self = this;
      var calEntryId = entryId;
      _self.removeDayTotals();

      $.ajax({
         url: TS.rootRelativePath + 'timesheet/delete-timesheet-entry',
         data: 'entryId=' + entryId,
         type: 'POST',
         dataType: 'json',
         success: function( data ) {

            $.each( data, function( key, element ) {
               UN.displayNotice( TS.notifSuccessTitle, TS.notifDeleteSuccess, 'warning' );
            });
            $( '#calendar' ).fullCalendar( 'removeEvents', calEntryId );
         },
         error: function ( request, error ) {
            UN.displayNotice( TS.notifErrorTitle, TS.notifDeleteError, 'error');
         }
      });
   },


   // Load the edit form with the entry details to be edited
   setEditForm: function( event, multiDayEntryDisabled ) {
      var _self = this;
      var disbursement = _self.nullToZeroStr( event.disbursement );
      if ( disbursement != '' ) {
         if ( disbursement == 0 ) {
            disbursement = ''; // We want to show the placeholder if it's zero
         } else {
            disbursement = parseFloat( event.disbursement ).toFixed( 2 ); // Make sure we only show 2 decimal places
         }
      }

      // Display one or two date fields depending if entries can span multiple days
      _self.displayEditDate( multiDayEntryDisabled );

      $( "#entry-form input#start-date" ).datepicker( 'update', $.fullCalendar.moment( event.start ).format( 'DD/MM/YYYY' ) );
      $( "#entry-form input#end-date" ).datepicker( 'update', $.fullCalendar.moment( event.end ).format( 'DD/MM/YYYY' ) );
      $( "#entry-form input#start-time" ).timepicker( 'setTime', $.fullCalendar.moment( event.start ).format( 'HH:mm A' ) );
      $( "#entry-form input#end-time" ).timepicker( 'setTime', $.fullCalendar.moment( event.end ).format( 'HH:mm A' ) );
      $( "#entry-form select#job-chooser-edit" ).val( event.jobId + '|#|' + _self.nullToZeroStr( event.childId )).trigger( "chosen:updated" );
      $( "#entry-form select#task-chooser-edit" ).val( event.taskId ).trigger( "chosen:updated" );
      $( "#entry-form textarea#task-comment" ).val( event.comment );
      $( "#entry-form input#disbursement" ).val( disbursement );
      $( "#eventObject" ).val( event );
      $( "#entryId" ).val( event.entryId );
      $( "#entry-form input#start-moment" ).val( event.start );
      $( "#entry-form input#end-moment" ).val( event.end );
   },

   // Displays the datepicker date fields for the edit modal
   // It displays an end date field if multi-day entries are allowed
   displayEditDate: function( multiDayEntryDisabled ) {
      var startDateHTML = '<span class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></span><input type="text" name="start-date" id="start-date" class="form-control" onfocus="this.blur();" readonly>',
          endDateHTML   = '<span class="input-group-addon">ends</span><input type="text" name="end-date" id="end-date" class="form-control" onfocus="this.blur();" readonly>';

      if ( multiDayEntryDisabled ) {
         // Only show a single date field
         $( '#edit-date' ).html( startDateHTML );
      } else {
         // Show start and end dates
         $( '#edit-date' ).html( startDateHTML + endDateHTML );
         $( '#time-text' ).text( 'ends' );
      }

      // Apply settings to datepicker fields
      $( function() {
         $( '#start-date' ).datepicker({ format: "dd/mm/yyyy" });
         $( '#end-date' ).datepicker({ format: "dd/mm/yyyy" });
      });
   },


   // Edit an entry
   editEntry: function( multiDayEntryDisabled ) {
      var _self = this;
      var jobArray = $( "#entry-form select#job-chooser-edit" ).val().split( "|#|" );
      var startDateTime, endDateTime;

      // Get our start and end times in a jQuery Moment format
      startDateTime = $.fullCalendar.moment( _self.displayDateToInternationalFormat( $( "#entry-form input#start-date" ).val() ) + ' ' + _self.convertMeridianTo24HourTimeFormat( $( "#entry-form input#start-time" ).val() ) );
      if ( multiDayEntryDisabled ) { // It ends on the same date
         endDateTime = $.fullCalendar.moment( _self.displayDateToInternationalFormat( $( "#entry-form input#start-date" ).val() ) + ' ' + _self.convertMeridianTo24HourTimeFormat( $( "#entry-form input#end-time" ).val() ) );
      } else { // It can have a different end date
         endDateTime = $.fullCalendar.moment( _self.displayDateToInternationalFormat( $( "#entry-form input#end-date" ).val() ) + ' ' + _self.convertMeridianTo24HourTimeFormat( $( "#entry-form input#end-time" ).val() ) );
      }

      var TE = {
         entryId: $( "#entryId" ).val(),
         jobId: jobArray[0],
         childId: jobArray[1],
         userId: displayUser.id,
         taskId: $( "#entry-form select#task-chooser-edit" ).val(),
         startDateTime: _self.createMySQLDateTime( startDateTime ),
         endDateTime: _self.createMySQLDateTime( endDateTime ),
         timeTaken: _self.calcDecimalTimeTaken( startDateTime, endDateTime ),
         comment: encodeURIComponent( $( "#entry-form textarea#task-comment" ).val() ),
         disbursement: _self.prepCurrencyValue( $( "#entry-form input#disbursement" ).val() )
      };

      // Error handling: End time can't be after start time
      if ( $.fullCalendar.moment( endDateTime ).diff( startDateTime ) <= 0 ) {
         UN.displayNotice( TS.notifErrorTitle, TS.notifEndTimeInvalid, 'error' );
         return;
      }
      var entry = $( '#calendar' ).fullCalendar( 'clientEvents', TE.entryId );

      $.ajax({
         url: TS.rootRelativePath + 'timesheet/update-timesheet-entry',
         data: 'entryId=' + TE.entryId + '&jobId=' + TE.jobId + '&childId=' + TE.childId + '&timesheetTaskId=' + TE.taskId + '&userId=' + TE.userId + '&comment=' + TE.comment + '&disbursement=' + TE.disbursement + '&startDateTime=' + TE.startDateTime + '&endDateTime=' + TE.endDateTime + '&totalHours=' + TE.timeTaken,
         type: 'POST',
         dataType: 'json',
         success: function( data ) {
            _self.removeDayTotals();

            // A JSON object is returned with the entry details
            $.each( data, function( key, element ) {

               // Update the entry on the calendar
               var entryData = _self.createEntryObject( element );
               $( '#calendar' ).fullCalendar( 'removeEvents', entryData.entryId );
               $( '#calendar' ).fullCalendar( 'renderEvent', entryData, true ); // stick? = true */

               UN.displayNotice( TS.notifSuccessTitle, TS.notifEntryFor + element.firstName + ' ' + element.surname + TS.notifEditSuccess, 'success' );
            });
         },
         error: function ( request, error ) {
            UN.displayNotice( TS.notifErrorTitle, TS.notifEditError, 'error' );
         }
      });
   },


   // Remove entry from calendar if it is dragged to bin area outside calendar
   dragEventToBinAndDelete: function( event, jsEvent, callbackEditEntry ) {

      var trashCan = $( '#droppable-remove-job' );
      var ofs = trashCan.offset();

      var x1 = ofs.left;
      var x2 = ofs.left + trashCan.outerWidth( true );
      var y1 = ofs.top;
      var y2 = ofs.top + trashCan.outerHeight( true );

      if ( jsEvent.pageX >= x1 && jsEvent.pageX <= x2 &&
         jsEvent.pageY >= y1 && jsEvent.pageY <= y2 ) {
         TE.deleteEntry( event._id );
      } else {
         // Not to be deleted so use a callback function to let the update happen
         if ( typeof( callbackEditEntry ) == "function" ) {
            callbackEditEntry();
         }
      }
   },


   // Title for the entry
   createEntryTitle: function( jobRef, jobName, childRef, childName ) {
      // Put the job name before the child name if it is for a child job
      if ( childRef ) {
         return '[' + jobRef + ' - ' + jobName + '] >> ' + childRef + ' - ' + childName;
      } else {
         return jobRef + ' - ' + jobName;
      }
   },


   // Check if an event spans multiple days
   isMultiDay: function ( start, end ) {
      var mEnd   = $.fullCalendar.moment( end );
      var mStart = $.fullCalendar.moment( start );

      // We subtract 15 minutes as 00:00 is technically the next day
      if ( mEnd.subtract( 15, 'minutes' ).isAfter( mStart, 'day' )) {
         UN.displayNotice( TS.notifNotAllowedTitle, TS.notifNoMultiDay, 'notice' );

         // Remove any cloned entries if it is a failed attempt to copy
         $( '#calendar' ).fullCalendar( 'removeEvents', 'cloned' );
         return true;
      } else {
         return false;
      }
   },

   // Turns null in zero length strings
   nullToZeroStr: function( string ) {
      if ( string == null ) {
         return '';
      } else {
         return string;
      }
   },

   // Swap date in dd/mm/yyyy format to yyyy-mm-dd format
   displayDateToInternationalFormat: function( displayDate ) {
      var datePart = displayDate.split( "/" );
      return datePart[2] + "-" + datePart[1] + "-" + datePart[0];
   },

   // Turns a jQuey Moment formatted date/time to a valid MySQL format of YYYY-MM-DD HH:mm
   createMySQLDateTime: function( moment ) {
      return $.fullCalendar.moment( moment ).format( 'YYYY-MM-DD HH:mm' );
   },

   // Calculates the time taken by an entry as a decimal amount
   calcDecimalTimeTaken: function( momentStart, momentEnd ) {
      return momentEnd.diff( momentStart, 'hours', true );
   },

   // Convert a time like 9:30 AM to 09:30
   convertMeridianTo24HourTimeFormat: function( timepickerTime ) {

      var hours = Number( timepickerTime.match( /^(\d+)/ )[1] );
      var minutes = Number( timepickerTime.match( /:(\d+)/ )[1] );
      var AMPM = timepickerTime.match( /\s(.*)$/ )[1];
      if ( AMPM == "PM" && hours < 12 ) hours = hours + 12;
      if ( AMPM == "AM" && hours == 12 ) hours = hours - 12;
      if ( hours < 10 ) hours = "0" + hours;
      if ( minutes == 0 ) minutes = minutes + "0";

      return hours + ":" + minutes;
   },


   // Prepares a currency value for the database
   // If the value is null or a zero length string it returns 0
   prepCurrencyValue: function( value ) {
      value = this.nullToZeroStr( value );
      if ( value == '' ) {
         return 0; // To force currency values to have a number
      } else {
         return value;
      }
   },


   // Creates an object with all the required details of a timesheet entry
   // This object is used to display each entry using the fullCalendar plugin
   createEntryObject: function( entryData ) {
      var _self = this;
      var color = entryData.groupColor;                // Get the group color
      if ( entryData.color ) color = entryData.color;  // Override if task has individual color

      var entryObject = {
         _id: entryData.entryId,
         entryId: entryData.entryId,
         title: _self.createEntryTitle( entryData.jobRef, entryData.jobName, entryData.childRef, entryData.childName ),
         start: entryData.startDateTime,
         end: entryData.endDateTime,
         timeTaken: entryData.timeTaken,
         userId: entryData.userId,
         jobId: entryData.jobId,
         jobRef: entryData.jobRef,
         jobName: entryData.jobName,
         childId: entryData.childId,
         childRef: entryData.childRef,
         childName: entryData.childName,
         taskId: entryData.taskId,
         taskName: entryData.taskName,
         taskGroup: entryData.taskGroup,
         comment: _self.nullToZeroStr( entryData.comment ),
         disbursement: _self.nullToZeroStr( entryData.disbursement ),
         color: color
      }
      return entryObject;
   },


   // Adds a button to the top button set to show day time totals
   addDayTotalButton: function( calendarElement ) {
      $( document ).find( '.fc-button-group' ).prepend( '<button type="button" id="show-times" class="fc-button fc-state-default fc-corner-right fc-corner-left mr-xs"><i class="fa fa-hourglass-half"></i></button>' );
      // Show day totals when button clicked
      $( '#show-times' ).click( function() {
         showTotals = true;                               // When this is true the day totals will be added up when the calendar events are displayed
         calendarElement.fullCalendar( 'refetchEvents' );
      });
   },


   // Adds up total time spent for each day of the week
   // Time is stored in global variables for this page
   addDayTotals: function( entry, arrId ) {
      if ( arrId.indexOf( parseInt( entry.entryId ) ) == -1 ) {   // Eliminate multiple calls for same entry
         // Add up daily totals
         switch( $.fullCalendar.moment( entry.startDateTime ).format( 'ddd' ) ) {
            case 'Sun':
               sun += parseFloat( entry.timeTaken );
               break;
            case 'Mon':
               mon += parseFloat( entry.timeTaken );
               break;
            case 'Tue':
               tue += parseFloat( entry.timeTaken );
               break;
            case 'Wed':
               wed += parseFloat( entry.timeTaken );
               break;
            case 'Thu':
               thu += parseFloat( entry.timeTaken );
               break;
            case 'Fri':
               fri += parseFloat( entry.timeTaken );
               break;
            case 'Sat':
               sat += parseFloat( entry.timeTaken );
               break;
         }
      }
      return parseInt( entry.entryId );
   },


   // Appends the day totals to each day column
   displayDayTotals: function() {
      if (showTotals) {
         this.removeDayTotals();   // Clear any previous totals
         if ( sun != 0 ) $( document ).find( 'th.fc-sun' ).append( ' <span class="label label-primary total-hours">'+ sun + '<span class="hidden-sm hidden-xs"> hours</span></span>' );
         if ( mon != 0 ) $( document ).find( 'th.fc-mon' ).append( ' <span class="label label-primary total-hours">'+ mon + '<span class="hidden-sm hidden-xs"> hours</span></span>' );
         if ( tue != 0 ) $( document ).find( 'th.fc-tue' ).append( ' <span class="label label-primary total-hours">'+ tue + '<span class="hidden-sm hidden-xs"> hours</span></span>' );
         if ( wed != 0 ) $( document ).find( 'th.fc-wed' ).append( ' <span class="label label-primary total-hours">'+ wed + '<span class="hidden-sm hidden-xs"> hours</span></span>' );
         if ( thu != 0 ) $( document ).find( 'th.fc-thu' ).append( ' <span class="label label-primary total-hours">'+ thu + '<span class="hidden-sm hidden-xs"> hours</span></span>' );
         if ( fri != 0 ) $( document ).find( 'th.fc-fri' ).append( ' <span class="label label-primary total-hours">'+ fri + '<span class="hidden-sm hidden-xs"> hours</span></span>' );
         if ( sat != 0 ) $( document ).find( 'th.fc-sat' ).append( ' <span class="label label-primary total-hours">'+ sat + '<span class="hidden-sm hidden-xs"> hours</span></span>' );
      }
      showTotals = false;
   },


   // Removes the day totals from each day column
   removeDayTotals: function() {

      $( document ).find( 'th.fc-sun .total-hours' ).remove();
      $( document ).find( 'th.fc-mon .total-hours' ).remove();
      $( document ).find( 'th.fc-tue .total-hours' ).remove();
      $( document ).find( 'th.fc-wed .total-hours' ).remove();
      $( document ).find( 'th.fc-thu .total-hours' ).remove();
      $( document ).find( 'th.fc-fri .total-hours' ).remove();
      $( document ).find( 'th.fc-sat .total-hours' ).remove();
   }
}