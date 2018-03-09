<?php
/**
 * Custom JavaScript for the council section
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
                  {"name": "Actions", "data": "actions", "sClass": "actions", "orderable": false}
               ],
               "ajax": '/council/get-rows-json'
            });


            $( '#datatable-editable' ).on( 'draw.dt', function () {

               /**
                * Called when a user clicks on the edit icon under Actions
                *
                * Prepares the form for editing a council, loads the council details ready to edit
                */
               $( '.edit' ).on( 'click', function(){

                  // The council ID needed to modify the database record
                  var councilId = $( this ).data( 'id' );

                  // Set the AJAX URL to be used to process the update council db query
                  $( '#modal-confirm-button' ).attr( 'data-ajax-url', '<?php echo ROOT_RELATIVE_PATH; ?>council/save' );

                  // Set the success/error messages from language file
                  $( '#modal-confirm-button' ).attr( 'data-ajax-success', '<?php echo lang( "council_notif_edit_success" ); ?>' );
                  $( '#modal-confirm-button' ).attr( 'data-ajax-error', '<?php echo lang( "council_notif_edit_error" ); ?>' );

                  // Set the correct title for the modal
                  $( '#form-title' ).html( '<?php echo lang( "council_edit_fm_title" ); ?>' );

                  // Clear the form so it is empty
                  AJXDB.clearFormData( '#form-council' );
                  // Load the selected council details into the form
                  loadFormData( councilId );                                  
               });

            });


            /**
             * Called when a user clicks on the "Add council" button
             *
             * Prepares the form for adding a new council
             */
            $( '.add' ).on( 'click', function(e) {
               
               e.stopPropagation();
               // Set empty id
               $( '#councilId' ).val( '' );
               // Set the AJAX URL to be used to process the add db query
               $( '#modal-confirm-button' ).attr( 'data-ajax-url', '<?php echo ROOT_RELATIVE_PATH; ?>council/save' );

               // Set the success/error messages from language file
               $( '#modal-confirm-button' ).attr( 'data-ajax-success', '<?php echo lang( "council_notif_add_success" ); ?>' );
               $( '#modal-confirm-button' ).attr( 'data-ajax-error', '<?php echo lang( "council_notif_add_error" ); ?>' );


               // Set the correct title for the modal
               $( '#form-title' ).html( '<?php echo lang("council_add_fm_title"); ?>' );

               // Clear the form so it is empty, ready for the new council details
               AJXDB.clearFormData( '#form-council' );
            });



            // Loads the council details into the form when we are going to edit them
            function loadFormData(councilId) {

               $.ajax({
                  type:'POST',
                  url: "<?php echo ROOT_RELATIVE_PATH; ?>council/get_single_row",
                  data: 'councilId=' + councilId,
                  cache: false,
                  dataType: 'json',
                  success: function( data ) {
                     $.each(data, function( key, element ) {
                        $( "#councilId" ).val( element.councilId );
                        $( "#name" ).val( element.name );                       
                     });
                  },
                  error: function(e) {
                     UN.displayNotice('<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang("council_notif_load_error"); ?>', 'error');
                  }
               });
            };


          

            



         });


      </script>