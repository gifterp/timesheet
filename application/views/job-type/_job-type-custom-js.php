<?php
/**
 * Custom JavaScript for the job type section
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
  
         $(document).ready(function() {

            table = $('#datatable-editable').DataTable({
               "autoWidth": false,
               "language": {
                     "infoEmpty": "Showing 0 to 0 of 0 entries",
                     "emptyTable": "<b><?php echo lang( "system_msg_nothing_display" ); ?></b>",
               },
               "columns": [
                  {"name": "Name", "data": "name", "orderable": true},
                  {"name": "Actions", "data": "actions", "sClass": "actions", "orderable": false}
               ],
               "ajax": '/job-type/get-rows-json'
            });


            $( '#datatable-editable' ).on( 'draw.dt', function () {

               /**
                * Called when a user clicks on the edit icon under Actions
                *
                * Prepares the form for editing a job type, loads the job type details ready to edit
                */
               $( '.edit' ).on( 'click', function(){

                  // The job type ID needed to modify the database record
                  var jobTypeId = $( this ).data( 'id' );

    

                  // Set the AJAX URL to be used to process the update job type db query
                  $( '#modal-confirm-button' ).attr( 'data-ajax-url', '<?php echo ROOT_RELATIVE_PATH; ?>job-type/save' );

                  // Set the success/error messages from language file
                  $( '#modal-confirm-button' ).attr( 'data-ajax-success', '<?php echo lang( "job_type_notif_edit_success" ); ?>' );
                  $( '#modal-confirm-button' ).attr( 'data-ajax-error', '<?php echo lang( "job_type_notif_edit_error" ); ?>' );

                  // Set the correct title for the modal
                  $( '#form-title' ).html('<?php echo lang( "job_type_edit_fm_title" ); ?>' );

                  // Clear the form so it is empty
                  AJXDB.clearFormData( '#form-job-type' );
                  // Load the selected job type details into the form
                  loadFormData( jobTypeId );
                                  
               });

               // Delete function modal
               $( '.confirmation-callback' ).confirmation({
                onConfirm: function() {
                  var data = $( this );
                  deleteJobType( data[0].id );
                }
              });
             
            });


            /**
             * Called when a user clicks on the "Add job type" button
             *
             * Prepares the form for adding a new job type
             */
            $( '.add' ).on( 'click', function(e) {
               e.stopPropagation();
               // Set empty id
               $( '#jobTypeId' ).val( '' );
               // Set the AJAX URL to be used to process the add db query
               $( '#modal-confirm-button' ).attr( 'data-ajax-url', '<?php echo ROOT_RELATIVE_PATH; ?>job-type/save' );

               // Set the success/error messages from language file
               $( '#modal-confirm-button' ).attr( 'data-ajax-success', '<?php echo lang( "job_type_notif_add_success" ); ?>' );
               $( '#modal-confirm-button' ).attr( 'data-ajax-error', '<?php echo lang( "job_type_notif_add_error" ); ?>' );


               // Set the correct title for the modal
               $( '#form-title' ).html( '<?php echo lang("job_type_add_fm_title" ); ?>' );

               // Clear the form so it is empty, ready for the new job type details
               AJXDB.clearFormData( '#form-job-type' );

            });



            // Loads the job type details into the form when we are going to edit them
            function loadFormData( jobTypeId ) {

               $.ajax({
                  type:'POST',
                  url: "<?php echo ROOT_RELATIVE_PATH; ?>job-type/get_single_row",
                  data: 'jobTypeId=' + jobTypeId,
                  cache: false,
                  dataType: 'json',
                  success: function( data ) {
                     $.each(data, function( key, element ) {
                       $( "#jobTypeId" ).val( element.jobTypeId );
                       $( "#name" ).val( element.name );                       
                     });
                  },
                  error: function(e) {
                     //$('.modal-dismiss').click();  // Close the modal before showing error
                     UN.displayNotice('<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang("job_type_notif_delete_error" ); ?>', 'error');
                  }
               });
            };


            function deleteJobType( jobId ) {

               $.ajax({
                   type: "POST",
                   url: "<?php echo ROOT_RELATIVE_PATH; ?>job-type/delete_single_row",
                   data: 'jobTypeId=' + jobId,
                   success: function( data ){
                      table.ajax.reload();
                      UN.displayNotice('Success!','Job type deleted', 'warning');
                   }, error: function (XHR, status, response) {
                      UN.displayNotice('<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang("job_type_notif_delete_error" ); ?>', 'error');
                   }

                });
            };

            


          

            



         });


      </script>