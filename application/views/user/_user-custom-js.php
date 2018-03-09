<?php
/**
 * Custom JavaScript for the user section
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:       Matt Batten <matt@improvedsoftware.com.au>
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
 
            table = $( '#datatable-editable' ).DataTable({
               "autoWidth": false,
               "language": {
                     "infoEmpty": "Showing 0 to 0 of 0 entries",
                     "emptyTable": "<b><?php echo lang( "system_msg_nothing_display" ); ?></b>",
               },
               "columns": [
                  {"name": "Name", "data": "name", "orderable": true},
                  {"name": "Username", "data": "username", "orderable": true},
                  {"name": "Charge Rate", "data": "chargeRate", "orderable": true},
                  {"name": "Access Level", "data": "accessLevel", "orderable": true},
                  {"name": "Status", "data": "status", "orderable": true},
                  {"name": "Actions", "data": "actions", "sClass": "actions", "orderable": false}
               ],
               "ajax": '/user/get-rows-json'
            });


            $( '#datatable-editable' ).on( 'draw.dt', function () {

               /**
                * Called when a user clicks on the edit icon under Actions
                *
                * Prepares the form for editing a user, loads the user details ready to edit
                */
               $( '.edit' ).on('click', function(){

                  // The user ID needed to modify the database record
                  var userId = $( this ).data( 'id' );

                  // Hide the password input field and disable it. Password not changed when editing
                  $( ".disable-on-edit" ).css( "display", "none" );
                  $( "input[name='password']" ).prop( 'disabled', true );

                  // Set the AJAX URL to be used to process the update user db query
                  $( '#modal-confirm-button' ).attr( 'data-ajax-url', '<?php echo ROOT_RELATIVE_PATH; ?>user/save');

                  // Set the success/error messages from language file
                  $( '#modal-confirm-button' ).attr( 'data-ajax-success', '<?php echo lang("user_notif_edit_success"); ?>' );
                  $( '#modal-confirm-button' ).attr( 'data-ajax-error', '<?php echo lang("user_notif_edit_error"); ?>' );

                  // Set the correct title for the modal
                  $( '#form-title' ).html( '<?php echo lang("user_edit_fm_title"); ?>' );

                  // Clear the form so it is empty
                  AJXDB.clearFormData( '#form-user' );
                  // Load the selected users details into the form
                  loadFormData( userId );
               });



                /**
                * Called when a user clicks on the update password (key) icon under Actions
                *
                * Prepares the form for updating user password
                */
               $( '.update-password' ).on('click', function(){

                  // Clear the form so it is empty
                  AJXDB.clearFormData( '#form-password' );
                  $( "input[name='password']" ).prop( 'disabled', false );
                  // The user ID needed to modify the database record
                  var userId = $( this ).data( 'id' );
                  // Update the user ID in the hidden form field so we know which user to change the password for
                  $( '#passwordId' ).val( userId );

               });

            });


            /**
             * Called when a user clicks on the "Add User" button
             *
             * Prepares the form for adding a new user
             */
            $( '.add' ).on( 'click', function(e) {
               e.stopPropagation();
               // Set empty id
               $( '#userId' ).val( '' );
               // Show the password input field and enable it
               $( ".disable-on-edit" ).css( "display", "block" );
               $( "input[name='password']" ).prop( 'disabled', false );

               // Set the AJAX URL to be used to process the add db query
               $( '#modal-confirm-button' ).attr( 'data-ajax-url', '<?php echo ROOT_RELATIVE_PATH; ?>user/save' );

               // Set the success/error messages from language file
               $( '#modal-confirm-button' ).attr( 'data-ajax-success', '<?php echo lang("user_notif_add_success"); ?>' );
               $( '#modal-confirm-button' ).attr( 'data-ajax-error', '<?php echo lang("user_notif_add_error"); ?>' );


               // Set the correct title for the modal
               $( '#form-title' ).html( '<?php echo lang("user_add_fm_title"); ?>' );

               // Clear the form so it is empty, ready for the new user details
               AJXDB.clearFormData('#form-user');
            });



            // Loads the users details into the form when we are going to edit them
            function loadFormData( userId ) {

               $.ajax({
                  type:'POST',
                  url: "<?php echo ROOT_RELATIVE_PATH; ?>user/get_single_row",
                  data: 'userId=' + userId,
                  cache: false,
                  dataType: 'json',
                  success: function( data ) {
                     $.each( data, function( key, element ) {
                        $( "#userId" ).val( element.userId );
                        $( "#firstName" ).val( element.firstName );
                        $( "#surname" ).val( element.surname );
                        $( "#initials" ).val( element.initials );
                        $( "#email" ).val( element.email );
                        $( "#username" ).val( element.username );
                        $( "#chargeRate" ).val( element.chargeRate );
                        $( "#accessLevel" ).val( element.accessLevel );
                        if ( element.active == 0 ) {
                           // We need to simulate a click to turn the ios7-switch jQuery off
                           $( "#active").prev( '.ios-switch' ).trigger( 'click' );
                        }
                     });
                  },
                  error: function(e) {
                     //$('.modal-dismiss').click();  // Close the modal before showing error
                     UN.displayNotice('<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang("user_notif_load_error"); ?>', 'error');
                  }
               });
            };


            $("#form-password").validate({
            	rules: {
            		password: "required",
            		confirm_password: {
            			required: true,
            			equalTo: "#password"
            		}
            	},
            	highlight: function( label ) {
            		$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
            	},
            	success: function( label ) {
            		$(label).closest('.form-group').removeClass('has-error');
            		label.remove();
            	},
            	errorPlacement: function( error, element ) {
            		var placement = element.closest('.input-group');
            		if (!placement.get(0)) {
            			placement = element;
            		}
            		if (error.text() !== '') {
            			placement.after(error);
            		}
            	}
            });



         });


      </script>