<?php
/**
 * Custom JavaScript for the contact section
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:       Matt Batten <matt@improvedsoftware.com.au>
 * Author:       John Gifter C Poja <gifter@improvedsoftware.com.au>
 * Requires:     [Vendor] dataTables JS + CSS
 *               [Vendor] Magnific Popup JS + CSS
 *               [Vendor] PNotify CSS
 */ 
?>      <!-- Custom -->
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/db/ajax-db.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/form-modal.js"></script>
   <script type="text/javascript">
  
         $( document ).ready( function() {
 
            table = $('#datatable-editable').DataTable({
               "autoWidth": false,
               "language": {
                     "infoEmpty": "Showing 0 to 0 of 0 entries",
                     "emptyTable": "<b><?php echo lang("system_msg_nothing_display"); ?></b>",
               },
               "columns": [
                  {"name": "Name", "data": "name", "orderable": true},
                  {"name": "Company Type", "data": "company", "orderable": true},
                  {"name": "Phone", "data": "phone", "orderable": true},
                  {"name": "Mobile", "data": "mobile", "orderable": true},
                  {"name": "Email", "data": "email", "orderable": true},
                  {"name": "Actions", "data": "actions", "sClass": "actions", "orderable": false}
               ],
               "ajax": '/contact/get-rows-json'
            });


            $( '#datatable-editable' ).on( 'draw.dt', function () {

               /**
                * Called when a user clicks on the edit icon under Actions
                *
                * Prepares the form for editing a contact, loads the contact details ready to edit
                */
               $( '.edit' ).on( 'click', function(){

                  // The contact ID needed to modify the database record
                  var contactPersonId = $( this ).data( 'id' );

                  // Set the AJAX URL to be used to process the update contact db query
                  $( '#modal-confirm-button' ).attr( 'data-ajax-url', '<?php echo ROOT_RELATIVE_PATH; ?>contact/save' );

                  // Set the success/error messages from language file
                  $( '#modal-confirm-button' ).attr( 'data-ajax-success', '<?php echo lang( "contact_notif_edit_success" ); ?>' );
                  $( '#modal-confirm-button' ).attr( 'data-ajax-error', '<?php echo lang( "contact_notif_edit_error" ); ?>' );

                  // Set the correct title for the modal
                  $( '#form-title' ).html( '<?php echo lang( "contact_edit_fm_title" ); ?>' );

                  // Clear the form so it is empty
                  AJXDB.clearFormData( '#form-contact' );
                  // Load the selected contact details into the form
                  loadFormData( contactPersonId) ;
                                  
               });

            });


            /**
             * Called when a user clicks on the "Add contact" button
             *
             * Prepares the form for adding a new contact
             */
            $( '.add' ).on( 'click', function(e) {
               
               e.stopPropagation();
               // Set empty id
               $( '#contactPersonId' ).val( '' );
               // Set the AJAX URL to be used to process the add db query
               $( '#modal-confirm-button' ).attr( 'data-ajax-url', '<?php echo ROOT_RELATIVE_PATH; ?>contact/save' );

               // Set the success/error messages from language file
               $( '#modal-confirm-button' ).attr( 'data-ajax-success', '<?php echo lang( "contact_notif_add_success" ); ?>' );
               $( '#modal-confirm-button' ).attr( 'data-ajax-error', '<?php echo lang( "contact_notif_add_error" ); ?>' );

               // Set the correct title for the modal
               $( '#form-title' ).html( '<?php echo lang( "contact_add_fm_title" ); ?>' );

               // Clear the form so it is empty, ready for the new contact details
               AJXDB.clearFormData( '#form-contact' );
            });



            // Loads the contact details into the form when we are going to edit them
            function loadFormData( contactPersonId ) {

               $.ajax({
                  type:'POST',
                  url: "<?php echo ROOT_RELATIVE_PATH; ?>contact/get_single_row",
                  data: 'contactPersonId=' + contactPersonId,
                  cache: false,
                  dataType: 'json',
                  success: function( data ) {
                     $.each( data, function( key, element ) {
                        $( "#contactPersonId" ).val( element.contactPersonId );
                        $( "#name" ).val( element.name );
                        $( "#company" ).val( element.company );
                        $( "#address1" ).val( element.address1 );
                        $( "#address2" ).val( element.address2) ; 
                        $( "#phone" ).val( element.phone );
                        $( "#fax" ).val( element.fax );
                        $( "#mobile" ).val( element.mobile );
                        $( "#email" ).val( element.email );
                     });
                  },
                  error: function(e) {
                     UN.displayNotice('<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "contact_notif_load_error" ); ?>', 'error');
                  }
               });
            };
         });
   </script>