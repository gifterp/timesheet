<?php
/**
 * Administration - Timesheet manage task Custom JavaScript
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>
      <!-- Custom -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/db/ajax-db.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/form-modal.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/timesheet/timesheet-manage-task.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/timesheet/timesheet-sortable.js"></script>
      <script type="text/javascript">

      $( document ).ready( function() {
         var link  = '<?php echo ROOT_RELATIVE_PATH; ?>admin/timesheet_tasks/';
         TMT.loadGroupList( link, "<?php echo lang( 'timesheet_adm_notif_group_add_error' ); ?>", "<?php echo lang( 'system_notif_error' ); ?>" );

         timesheetAdminCallback = { 
 
            // Timesheet Group

            // Success callback: Add Timesheet Group Panel
            addTimesheetGroupSuccess: function( data ) {
               value = JSON.parse( data );
               // Load job checklist details on the panel
               TMT.loadGroupList( value.link, "<?php echo lang( 'timesheet_adm_notif_group_load_error' ); ?>", "<?php echo lang( 'system_notif_error' ); ?>" );
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'timesheet_adm_notif_group_add_success' ); ?>", "success" );
            },

            // Error callback: Add Timesheet Group Panel
            addTimesheetGroupError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'timesheet_adm_notif_group_add_error' ); ?>", "error" );
            },

            // Success callback: Update Timesheet Group Panel
            updateTimesheetGroupSuccess: function( data ) {
               // Load job checklist details on the panel
               TMT.loadGroupList( link, "<?php echo lang( 'timesheet_adm_notif_group_load_error' ); ?>", "<?php echo lang( 'system_notif_error' ); ?>" );
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'timesheet_adm_notif_group_update_success' ); ?>", "success" );
            },

            // Error callback: Update Timesheet Group Panel
            updateTimesheetGroupError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'timesheet_adm_notif_group_update_error' ); ?>", "error" );
            },

            // Success callback: Delete Job Checklist Panel
            deleteTimesheetGroupSuccess: function( data ) {
               // Load job checklist details on the panel
               TMT.loadGroupList( link, "<?php echo lang( 'timesheet_adm_notif_group_load_error' ); ?>", "<?php echo lang( 'system_notif_error' ); ?>" );
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'timesheet_adm_notif_group_delete_success' ); ?>", "warning" );
            },

            // Error callback: Delete Job Checklist  Panel
            deleteTimesheetGroupError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'timesheet_adm_notif_group_delete_error' ); ?>", "error" );
            },


            // Timesheet Task

            // Success callback: Add Timesheet Task Panel
            addTimesheetTaskSuccess: function( data ) {
               value = JSON.parse(data);
               // Load job checklist details on the panel
               TMT.loadSelectedGroup( value.id, value.link, "<?php echo lang( 'timesheet_adm_notif_task_load_error' ); ?>", "<?php echo lang( 'system_notif_error' ); ?>" );
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'timesheet_adm_notif_task_add_success' ); ?>", "success" );
            },

            // Error callback: Add Timesheet Task Panel
            addTimesheetTaskError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'timesheet_adm_notif_task_add_error' ); ?>", "error" );
            },

            // Success callback: Update Timesheet Task Panel
            updateTimesheetTaskSuccess: function( data ) {
               value = JSON.parse(data);
               // Load job checklist details on the panel
               TMT.loadSelectedGroup( value.id, value.link, "<?php echo lang( 'timesheet_adm_notif_task_load_error' ); ?>", "<?php echo lang( 'system_notif_error' ); ?>" );
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'timesheet_adm_notif_task_update_success' ); ?>", "success" );
            },

            // Error callback: Update Timesheet Task Panel
            updateTimesheetTaskError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'timesheet_adm_notif_task_update_error' ); ?>", "error" );
            },

            // Success callback: Delete Timesheet Task Panel
            deleteTimesheetTaskSuccess: function( data ) {
               value = JSON.parse(data);
               // Load job checklist details on the panel
               TMT.loadSelectedGroup( value.id, value.link, "<?php echo lang( 'timesheet_adm_notif_task_load_error' ); ?>", "<?php echo lang( 'system_notif_error' ); ?>" );
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'timesheet_adm_notif_task_delete_success' ); ?>", "warning" );
            },

            // Error callback: Delete Timesheet Task  Panel
            deleteTimesheetTaskError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'timesheet_adm_notif_task_delete_error' ); ?>", "error" );
            },

         }


         /** ----------------------------------------------------------------------------
          * Change the group name and color once click then save next click
          * First click edit, then click save icon to save
          */
         $( document ).on( 'click', '.handle-edit', function(e) {

            var holderId   = $( this ).data( 'id' );
            var color      = $( '.shade' + holderId ).val();
            var groupName  = $( '#group-name' + holderId ).val();
            

            // check if add or update
            if( $( this ).hasClass( 'fa-pencil' ) ) {
               // Stop handle task trigger function
               e.stopPropagation();
               $( this ).removeClass( 'fa-pencil' );
               $( this ).addClass( 'fa-save' );
               $( '.fa-pencil' ).addClass( 'hidden' );
               $( '.group-sort' ).addClass( 'hidden' );
               $( '.handle-delete' ).addClass( 'hidden' );
               $( '.group-cancel' + holderId ).removeClass( 'hidden' );
               $( '.color-div' + holderId ).removeClass( 'hidden' );
               $( '.group-title' + holderId ).removeClass( 'hidden' );
               $('.group-name' + holderId ).text('');
               $( '.color-div' + holderId ).colorpicker();
               AJXDB.clearFormData( '#form-taskgroup' );
               // Set the form into edit type
               $( '#submit-taskgroup' ).attr( 'data-form', 'edit' );
            } else {
               
               $( this ).addClass( 'fa-pencil' );
               $( this ).removeClass( 'fa-save' );
               $( '.fa-pencil' ).removeClass( 'hidden' );
               $( '.group-sort' ).addClass( 'hidden' );
               $( '.handle-delete' ).removeClass( 'hidden' );  
               $( '.group-cancel' + holderId ).addClass( 'hidden' );
               $( '.group-head' + holderId ).css('background-color',  color );
               $('.group-name' + holderId).text( groupName );

               $( '.group-title' + holderId ).addClass( 'hidden' );
               $( '.color-div' + holderId).addClass( 'hidden' );

               $( '#timesheetTaskGroupId' ).val( holderId );
               $( '#groupName' ).val( groupName );
               $( '#groupColor' ).val( color );
   
               // Update group form
               $('#submit-taskgroup').trigger( 'click' );
               // Reset Form
               $( '#timesheetTaskGroupId' ).val( '' );
               $( '#groupName' ).val( '' );
               $( '#groupColor' ).val( '' );
               AJXDB.clearFormData( '#form-taskgroup' );
               
            }

         });

         $( '.add-group' ).on('click', function(e) {
            e.stopPropagation();
         });


         /** ----------------------------------------------------------------------------
          * Modal add task for the group form, clears the form
          */
         $( document ).on('click', '.add', function(e) {
               
               // Clear the form so it is empty, ready for the new user details
               AJXDB.clearFormData( '#form-timesheet-task' );
               // Set the correct title for the modal
               $( '#form-task-title' ).html( $( this ).data( 'title' ) );
               // Set the group id in the form
               $( '#task-group-id' ).val( $( this ).data( 'id' ) );
               // Reset task id
               $( '#timesheetTaskId' ).val( '' );
               // reset color 
               $( '#color' ).val( '' );
               $( '.input-group-addon' ).find( 'i' ).removeAttr( "style" );
               // Set hidden reports off
               $( "#hiddenReports" ).prev( '.ios-switch' ).trigger( 'click' );
               $( "#hiddenReports" ).prop( 'checked', false );
               $( "#createButton" ).prev( '.ios-switch' ).trigger( 'click' );
               $( "#createButton" ).prop( 'checked', false );
              
         });

         /** ----------------------------------------------------------------------------
          * Modal edit task for the group form, clears the form
          */
         $( document ).on( 'click', '.edit', function(e) {
            // Clear the form so it is empty, ready for the new user details
            AJXDB.clearFormData( '#form-timesheet-task' );
            $( '#form-task-title' ).html( $( this ).data( 'title' ) );
            // reset color 
            $( '#color' ).val( '' );
            $( '.input-group-addon' ).find( 'i' ).removeAttr( "style" );
            // load the task data to the form
            loadTaskData( $( this ).data( 'id' ) );
            $( '#submit-task' ).attr( 'data-form', 'edit' );

         });


         /**
          * Called when a user submit the taskgroup form
          *
          * Prepares the form for adding/updating a taskgroup entry
          */
         $( '#submit-taskgroup' ).on( 'click' , function(e) {
            e.preventDefault();
            validateForm('#form-taskgroup');
            // Check all required form fields are valid
            if ( $( '#form-taskgroup' ).valid() ) {
               $.magnificPopup.close();
               var ajaxURL    = '<?php echo ROOT_RELATIVE_PATH; ?>admin/timesheet_tasks/save_group';
               var ajaxData   = $( '#form-taskgroup' ).serialize();
               var successCB  = timesheetAdminCallback.addTimesheetGroupSuccess;
               var errorCB    = timesheetAdminCallback.addTimesheetGroupError;

               // Set callback for update
               if ( $( this ).data( 'form' ) == 'edit' ) {

                  successCB   = timesheetAdminCallback.updateTimesheetGroupSuccess;
                  errorCB     = timesheetAdminCallback.updateTimesheetGroupError;
                  $( '#submit-taskgroup' ).attr( 'data-form', 'add' );
                  $( '#timesheetTaskGroupId' ).val( null );

               }
               // Call the AJAX object method responsible for handing the db update
               AJXDB.ajaxForm( ajaxURL, ajaxData, successCB, errorCB );
               AJXDB.clearFormData( '#form-taskgroup' );
            }

         });

         /**
          * Called when a user submit the tasklist form
          *
          * Prepares the form for adding/updating a tasklist entry
          */
         $( '#submit-task' ).on( 'click' , function(e) {
            e.preventDefault();
            validateForm('#form-task');
            // Check all required form fields are valid
            if ( $( '#form-task' ).valid() ) {
               $.magnificPopup.close();
               var ajaxURL    = '<?php echo ROOT_RELATIVE_PATH; ?>admin/timesheet_tasks/save_task';
               var ajaxData   = $( '#form-task' ).serialize();
               var successCB  = timesheetAdminCallback.addTimesheetTaskSuccess;
               var errorCB    = timesheetAdminCallback.addTimesheetTaskError;

               // Set callback for update
               if ( $( this ).data( 'form' ) == 'edit' ) {

                  successCB   = timesheetAdminCallback.updateTimesheetTaskSuccess;
                  errorCB     = timesheetAdminCallback.updateTimesheetTaskError;
                  $( '#submit-task' ).attr( 'data-form', 'add' );
                  $( '#timesheetTaskGroupId' ).val( null );

               }
               // Call the AJAX object method responsible for handing the db update
               AJXDB.ajaxForm( ajaxURL, ajaxData, successCB, errorCB );
               AJXDB.clearFormData( '#form-task' );
            }

         });

         

         /** ----------------------------------------------------------------------------
          * Html create group header list
          */
         $( document ).on( 'click', '.handle-task', function(e) {

            // Empty task details
            $( '.task-list' ).html( '' );
            var groupId = $( this ).attr( 'id' );
            // Load selected group with task
            TMT.loadSelectedGroup( groupId, link,  "<?php echo lang( 'timesheet_adm_notif_group_add_error' ); ?>", "<?php echo lang( 'system_notif_error' ); ?>" );

         });

         

          /** ----------------------------------------------------------------------------
          * Stop handle task trigger function if other button is click or input field selected
          */
         $( document ).on( 'click', '.stop-handle-task-trigger', function(e) {
            e.stopPropagation();
         });

         /** ----------------------------------------------------------------------------
          * Trigger color picker once focus on color input
          */
         $( document ).on( 'focus', '.color-input', function() {
            $( '.input-group-addon' ).find( 'i' ).trigger( "click" );
         });



         /** ----------------------------------------------------------------------------
          * Cancel group
          * Return data to normal,
          */
         $( document ).on( 'click', '.handle-cancel', function(e) {
            var holderId   = $( this ).data( 'id' );
            var color      = $( '.shade' + holderId ).val();
            var groupName  = $( '#group-name' + holderId ).val();

            $( '.group-title' + holderId ).addClass( 'hidden' );
            $( '.color-div' + holderId).addClass( 'hidden' );
            $( '.group-head' + holderId ).css('background-color',  color );
            $('.group-name' + holderId).text( groupName );

            $( '.save' + holderId ).addClass( 'fa-pencil' );
            $( '.save' + holderId ).removeClass( 'fa-save' );

            $( '.fa-save' ).addClass( 'hidden' );
            $( '.fa-times' ).addClass( 'hidden' );

            $( '.fa-pencil' ).removeClass( 'hidden' );
            $( '.group-sort' ).removeClass( 'hidden' );
            $( '.handle-delete' ).removeClass( 'hidden' );

            TMT.loadGroupList( link, "<?php echo lang( 'timesheet_adm_notif_group_add_error' ); ?>", "<?php echo lang( 'system_notif_error' ); ?>" );
         });

        


         // Loads the users details into the form when we are going to edit them
         function loadTaskData( timsheetTaskId ) {

            $.ajax({
               type:'POST',
               url: "<?php echo ROOT_RELATIVE_PATH; ?>admin/timesheet-tasks/get_single_row",
               data: 'timsheetTaskId=' + timsheetTaskId,
               cache: false,
               dataType: 'json',
               success: function( data ) {
                  $.each(data, function( key, element ) {
                     $( "#timesheetTaskId" ).val( element.timesheetTaskId );
                     $( "#task-group-id" ).val( element.timesheetTaskGroupId );
                     $( "#taskName" ).val( element.taskName );
                     $( "#taskDescription" ).val( element.taskDescription );
                     $( "#color" ).val( element.color );
                     $( "#timeTaken" ).val( element.timeTaken );
                     $( '.input-group-addon' ).find('i').css('background-color',  element.color );
                     if ( element.chargeable == 0 ) {
                        // We need to simulate a click to turn the ios7-switch jQuery off
                        $( "#chargeable" ).prev( '.ios-switch' ).trigger( 'click' );
                     }
                     if ( element.createButton == 0 ) {
                        // We need to simulate a click to turn the ios7-switch jQuery off
                        $( "#createButton" ).prev( '.ios-switch' ).trigger( 'click' );
                     }
                     if ( element.hiddenReports == 0 ) {
                        // We need to simulate a click to turn the ios7-switch jQuery off
                        $( "#hiddenReports" ).prev( '.ios-switch' ).trigger( 'click' );
                     }
                     if ( element.active == 0 ) {
                        // We need to simulate a click to turn the ios7-switch jQuery off
                        $( "#active" ).prev( '.ios-switch' ).trigger( 'click' );  
                     }
                  });
               },
               error: function(e) {
                  UN.displayNotice('<?php echo lang( "system_notif_error" ); ?>', "<?php echo lang( 'timesheet_adm_notif_task_load_error' ); ?>", 'error');
               }
            });
         };

         

      });

      </script>