<?php
/**
 * Custom JavaScript for the customer section
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

            table = $( '#datatable-editable' ).DataTable({
               "autoWidth": false,
               "language": {
                     "infoEmpty": "Showing 0 to 0 of 0 entries",
                     "emptyTable": "<b><?php echo lang("system_msg_nothing_display"); ?></b>",
               },
               "columns": [
                  {"name": "Name", "data": "name", "orderable": true},
                  {"name": "Customer Type", "data": "customer", "orderable": true},
                  {"name": "Email", "data": "email", "orderable": true},
                  {"name": "Actions", "data": "actions", "sClass": "actions", "orderable": false}
               ],
               "ajax": '/customer/get-rows-json'
            });


            $( '#datatable-editable' ).on( 'draw.dt', function () {

               /**
                * Called when a user clicks on the edit icon under Actions
                *
                * Prepares the form for editing a customer, loads the customer details ready to edit
                */
               $( '.edit' ).on( 'click', function(){
                  // The customer ID needed to modify the database record
                  var customerId = $( this ).data('id');

                  // Set the AJAX URL to be used to process the update customer db query
                  $( '#modal-confirm-button' ).attr( 'data-ajax-url', '<?php echo ROOT_RELATIVE_PATH; ?>customer/save' );

                  // Set the success/error messages from language file
                  $( '#modal-confirm-button' ).attr( 'data-ajax-success', '<?php echo lang( "customer_notif_edit_success" ); ?>' );
                  $( '#modal-confirm-button' ).attr( 'data-ajax-error', '<?php echo lang( "customer_notif_edit_error" ); ?>' );

                  // Set the correct title for the modal
                  $( '#form-title' ).html( '<?php echo lang( "customer_edit_fm_title" ); ?>' );

                  // Clear the form so it is empty
                  AJXDB.clearFormData( '#form-customer' );
                  // Load the selected customer details into the form
                  loadFormData( customerId );
             
               });

            });


            /**
             * Called when a user clicks on the "Add customer" button
             *
             * Prepares the form for adding a new customer
             */
            $( '.add' ).on( 'click', function(e) {
               
               e.stopPropagation();
               // Set empty id
               $( '#customerId' ).val( '' );
               // Set the AJAX URL to be used to process the add db query
               $( '#modal-confirm-button' ).attr( 'data-ajax-url', '<?php echo ROOT_RELATIVE_PATH; ?>customer/save' );

               // Set the success/error messages from language file
               $( '#modal-confirm-button' ).attr( 'data-ajax-success', '<?php echo lang( "customer_notif_add_success" ); ?>' );
               $( '#modal-confirm-button' ).attr( 'data-ajax-error', '<?php echo lang( "customer_notif_add_error" ); ?>' );


               // Set the correct title for the modal
               $( '#form-title' ).html( '<?php echo lang( "customer_add_fm_title" ); ?>' );

               // Clear the form so it is empty, ready for the new customer details
               AJXDB.clearFormData( '#form-customer' );
            });



            // Loads the customer details into the form when we are going to edit them
            function loadFormData( customerId ) {

               $.ajax({
                  type:'POST',
                  url: "<?php echo ROOT_RELATIVE_PATH; ?>customer/get_single_row",
                  data: 'customerId=' + customerId,
                  cache: false,
                  dataType: 'json',
                  success: function( data ) {
                     $.each(data, function(key, element) {
                        $( "#customerId" ).val( element.customerId );
                        $( "#name" ).val( element.name );
                        $( "select[name='customerType']" ).val( element.customerType );
                        $( "#address1" ).val( element.address1 );
                        $( "#address2" ).val( element.address2) ; 
                        $( "#city" ).val( element.city );
                        $( "#region" ).val( element.region );
                        $( "#postCode" ).val( element.postCode );
                        $( "#phone" ).val( element.phone );
                        $( "#fax" ).val( element.fax );
                        $( "#mobile" ).val( element.mobile );
                        $( "#email" ).val( element.email );
                        if ( element.active == 0 ) {
                           // We need to simulate a click to turn the ios7-switch jQuery off
                           $( "#active" ).prev( '.ios-switch' ).trigger( 'click' );
                        }
                     });
                  },
                  error: function(e) {
                     
                     UN.displayNotice('<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "customer_notif_load_error" ); ?>', 'error');
                  }
               });
            };




           


            



         });


      </script>