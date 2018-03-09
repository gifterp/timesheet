<?php
/**
 * Custom JavaScript for the checklist section
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:       Matt Batten <matt@improvedsoftware.com.au>
 * Author:       John Gifter C Poja <gifter@improvedsoftware.com.au>
 * Requires:     [Vendor] dataTables JS + CSS
 *               [Vendor] Magnific Popup JS + CSS
 *               [Vendor] PNotify CSS
 */ 

?>       
   <!-- Custom -->
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/db/ajax-db.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/form-modal.js"></script>
   <script type="text/javascript">

    
         $( document ).ready( function() {

            table = $( '#datatable-editable' ).DataTable({
               "autoWidth": false,
               "language": {
                  "infoEmpty": "Showing 0 to 0 of 0 entries",
                  "emptyTable": "<b><?php echo lang( "system_msg_nothing_display" ); ?></b>",
               },
               "columns": [
                  { "name": "Id", "data": "checklistId", "visible": false, "defaultContent": "" },
                  { "name": "Title", "data": "title", "orderable": true },
                  { "name": "Description Type", "data": "description", "orderable": true },
                  { "name": "Actions", "data": "actions", "sClass": "actions", "orderable": false }
               ],
               "ajax": '/checklist/get-rows-json'
            });

 
            $( '#datatable-editable' ).on( 'draw.dt', function () {
 
               /**
                * Called when a user clicks on the edit icon under Actions
                *
                * Prepares the form for editing a checklist, loads the checklist details ready to edit
                */
               $( '.edit' ).on( 'click', function() {
                  // The checklist ID needed to modify the database record
                  var checklistId = $( this ).data( 'id' );
                  // Set the correct title for the modal
                  $( '#form-title' ).html( '<?php echo lang( "checklist_edit_fm_title" ); ?>' );
                  // Set the form into edit type
                  $( '#submit-checklist' ).attr( 'data-form', 'edit' );
                  // Clear the form so it is empty
                  AJXDB.clearFormData( '#form-checklist' );
                  // Load the left form panel
                  loadFormData( checklistId );
               });

            });

            /**
             * Called when a user clicks on the add button
             *
             * Prepares the form for adding a checklist
             */
            $( '.add' ).on( 'click', function(e) {
               e.stopPropagation();

               $( '#submit-checklist' ).attr( 'data-form', 'add' );
               $( '#check-list-id' ).val(null);
               // Set the correct title for the modal
               $( '#form-title' ).html( '<?php echo lang( "checklist_add_fm_title" ); ?>' );
               // Clear the form so it is empty
               AJXDB.clearFormData( '#form-checklist' );
            });


            /** ----------------------------------------------------------------------------
             * Set datatable row as clickable link to job
             */
            $( '#datatable-editable tbody' ).on( 'click', 'tr > td', function () {
               var data = table.row( this ).data();
               // Dont redirect if edit icon is click
               if ( ! $( this ).hasClass( 'actions' ) ){
                 $.each( data, function( key, element ) {
                    if ( key == 'checklistId' ) {
                       var id = element;
                       window.location.href = '<?php echo ROOT_RELATIVE_PATH;?>checklist/view/' + id;
                    }
                 });
               }
            });

            /** ----------------------------------------------------------------------------
             * Object to handle the ajax success/error requests
             */
            checklistCallback = { 

              // Success callback: Add Checklist Section
               addChecklistSuccess: function( data, link, id ) {
                 value = JSON.parse( data );
                  // Redirect to checklist edit page
                  window.location.href = value.link;
               },

               // Error callback: Add Checklist Section
               addChecklistError: function( data, link, id ) {
                  // Return checklist data table
                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'checklist_notif_add_error' ); ?>", "error" );
               },

               // Success callback: Update Checklist Section
               updateChecklistSuccess: function( data, link, id ) {
                  // Return to checklist edit page
                  table.ajax.reload(); 
                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'checklist_notif_edit_success' ); ?>", "success" );
               },

               // Error callback: Update Checklist Section
               updateChecklistError: function( data, link, id ) {
                  // Return checklist data table
                  table.ajax.reload(); 
                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'checklist_notif_edit_error' ); ?>", "error" );
               },

            }

            /**
             * Called when a user submit the checklist form
             *
             * Prepares the form for adding/updating a checklist entry
             */
            $( '#submit-checklist' ).on( 'click' , function(e) {
              
               validateForm('#form-checklist');
               // Check all required form fields are valid
               if ( $( '#form-checklist' ).valid() ) {
                  $.magnificPopup.close();
                  var ajaxURL    = '<?php echo ROOT_RELATIVE_PATH; ?>checklist/save';
                  var ajaxData   = $('#form-checklist').serialize();
                  var successCB  = checklistCallback.addChecklistSuccess;
                  var errorCB    = checklistCallback.addChecklistError;

                  // Set callback for update
                  if ( $( this ).data( 'form' ) == 'edit' ) {
                     successCB   = checklistCallback.updateChecklistSuccess;
                     errorCB     = checklistCallback.updateChecklistError;
                     $( '#submit-checklist' ).attr( 'data-form', 'add' );
                     $( '#check-list-id' ).val(null);
                     // Set the correct title for the modal
                     $( '#form-title' ).html( '<?php echo lang( "checklist_add_fm_title" ); ?>' );
                  }
                  // Call the AJAX object method responsible for handing the db update
                  AJXDB.ajaxForm( ajaxURL, ajaxData, successCB, errorCB );
                  AJXDB.clearFormData( '#form-checklist' );
               }
         

            });

            // Loads the checklist details into the form when we are going to edit them
            function loadFormData( checklistId ) {

               $.ajax({
                  type:'POST',
                  url: "<?php echo ROOT_RELATIVE_PATH; ?>checklist/get-single-row",
                  data: 'checklistId=' + checklistId,
                  cache: false,
                  dataType: 'json',
                  success: function( data ) {
                     $.each( data, function( key, element ) {
                        $( "#check-list-id" ).val( element.checklistId );
                        $( "#title" ).val( element.title );
                        $( "#description" ).val( element.description );
                     });
                  },
                  error: function( e ) {
                     UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "checklist_notif_load_error" ); ?>', 'error' );
                  }
               });
            };

         });

      </script>