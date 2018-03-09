<?php
/**
 * Report - Mock Invoice Custom JavaScript
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

?>
      <!-- Custom -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/utilities.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/reports/functions.js"></script>

      <script type="text/javascript">

         $( document ).ready( function() {

            // Get the mock invoice id
            var mockInvoiceId = '<?php echo @$_GET["id"]; ?>';
            // Set the mock invoice id on the page
            $( '#invoice-number' ).text( mockInvoiceId );
            // jobId will be set when we set the form controls
            var jobId = '';

            /** ----------------------------------------------------------------------------
             * Set up report form plugins and event handlers
             */

            // Get the mock invoice settings and set up the control form
            get_settings();

            // Get the invoice details and load the form for entry
            print_invoice_details_form();

            // Display the invoice details display section
            print_invoice_details_table();

            // Set mock invoice entry panel event handlers
            listen_invoice_entry_panel();



            /** ----------------------------------------------------------------------------
             * Object to handle the ajax success/error requests
             */
            CALLBACK = {

               // Report control form

               // Success callback: Change ready to invoice setting
               updateReadySuccess: function( data, isReady, _self ) {

                  // Update the invoice controls on the WIP report page
                  manage_invoices_wip_job_control_panel( jobId, '<?php echo ROOT_RELATIVE_PATH; ?>', true );

                  // Show/hide and disable/enable any necessary sections of the invoice
                  process_invoice_stages( isReady, 0 );

                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_minv_notif_ready_success' ); ?>", "success" );
               },

               // Error callback: Change ready to invoice setting
               updateReadyError: function( e, isReady, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_minv_notif_ready_error' ); ?>", "error" );
               },

               // Success callback: Change completed setting
               completedSuccess: function( data, isComplete, _self ) {

                  // For each entry, show they are now archived on the WIP report window (If we have access)
                  $.each( data, function( key, element ) {
                     archive_wip_entry( element.timesheetEntryId, isComplete );
                  });

                  // Show/hide and disable/enable any necessary sections of the invoice
                  process_invoice_stages( 1, isComplete );

                  // Update the invoice controls on the WIP report page
                  manage_invoices_wip_job_control_panel( data[0].jobId, '<?php echo ROOT_RELATIVE_PATH; ?>', true );

                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_minv_notif_complete_success' ); ?>", "success" );
               },

               // Error callback: Change completed setting
               completedError: function( e, isComplete, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_minv_notif_complete_error' ); ?>", "error" );
               },

               // Success callback: Update sort order
               updateSortOrderSuccess: function( data, mockInvoiceId, _self ) {

                  // Reload the mock invoice page to render with new sort order
                  location.reload();
               },

               // Error callback: Update sort order
               updateSortOrderError: function( e, mockInvoiceId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_minv_notif_sort_order_error' ); ?>", "error" );
               },

               // Success callback: Delete the invoice
               deleteSuccess: function( data, mockInvoiceId, _self ) {

                  // For each entry, restore the invoice checkbox on the WIP report window (If we have access)
                  $.each( data, function( key, element ) {
                     restore_wip_entry_invoice_checkbox( element.timesheetEntryId );
                  });

                  // Update the invoice controls on the WIP report page
                  manage_invoices_wip_job_control_panel( jobId, '<?php echo ROOT_RELATIVE_PATH; ?>', true );

                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_minv_notif_delete_success' ); ?>", "warning" );
                  // Close the window after 2 seconds
                  setTimeout( function() { window.close() }, 2000 );
               },

               // Error callback: Delete the invoice
               deleteError: function( e, mockInvoiceId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_minv_notif_delete_error' ); ?>", "error" );
               },

               // Updating the mock invoice row details

               // Success callback: Update a row comment
               updateCategorySuccess: function( data, mockInvoiceId, _self ) {

                  // Reload the invoice details display section
                  print_invoice_details_table();
                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_minv_notif_upd_category_success' ); ?>", "success" );
               },

               // Error callback: Update a row comment
               updateCategoryError: function( e, mockInvoiceId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_minv_notif_upd_category_error' ); ?>", "error" );
               },

               // Success callback: Update a row comment
               updateDescriptionSuccess: function( data, mockInvoiceId, _self ) {

                  // Reload the invoice details display section
                  print_invoice_details_table();
                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_minv_notif_upd_desc_success' ); ?>", "success" );
               },

               // Error callback: Update a row comment
               updateDescriptionError: function( e, mockInvoiceId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_minv_notif_upd_desc_error' ); ?>", "error" );
               },

               // Success callback: Update a row total
               updateAmountSuccess: function( data, mockInvoiceId, _self ) {

                  // Reload the invoice details display section
                  print_invoice_details_table();
                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_minv_notif_upd_amount_success' ); ?>", "success" );
               },

               // Error callback: Update a row description
               updateAmountError: function( e, mockInvoiceId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_minv_notif_upd_amount_error' ); ?>", "error" );
               },

               // Success callback: Delete a single entry from the report
               deleteEntrySuccess: function( data, entryId, _self ) {

                  // Reset the entry on the WIP report window (If we have access)
                  restore_wip_entry_invoice_checkbox( entryId );

                  // When an entry is deleted, it may mean that the mock invoice row or the entire mock invoice was removed as they contained no more entries
                  // We need to handle all 3 scenarios below

                  // This was the last entry for the invoice. The whole invoice has been deleted and no longer exists
                  // We show an extra notification to say it was deleted and close the invoice automatically
                  if ( data[0].invoiceDeleted ) {

                     // Update the invoice controls on the WIP report page
                     manage_invoices_wip_job_control_panel( jobId, '<?php echo ROOT_RELATIVE_PATH; ?>', true );

                     UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_minv_notif_delete_success' ); ?>", "warning" );
                     // Close the window after 2 seconds
                     setTimeout( function() { window.close() }, 2000 );

                  // Just the entry was deleted
                  } else {
                     // Remove the entry from the invoice
                     remove_minv_entry( entryId );
                     // Reload the invoice entries panel to update totals
                     reload_invoice_entry_panel();
                  }

                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_minv_notif_delete_item_success' ); ?>", "warning" );
               },

               // Error callback: Delete a single entry from the report
               deleteEntryError: function( e, entryId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_minv_notif_delete_item_error' ); ?>", "error" );
               },

               // Success callback: Entry written off
               writeOffSuccess: function( data, entryId, _self ) {

                  // Show entry is written off on both mock invoice and WIP report if it is present
                  $( '#entry-row-' + entryId ).addClass( 'written-off-row' );
                  // Reload the invoice entries panel to update totals
                  reload_invoice_entry_panel();
                  if ( window.opener && window.opener.document ) {
                     // Uncheck the archive checkbox
                     $( '#write-off-' + entryId, window.opener.document ).prop( 'checked', true );
                     $( '#entry-row-' + entryId, window.opener.document ).addClass( 'written-off-row' );
                  }
                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_wip_notif_wo_success' ); ?>", "warning" );
               },

               // Error callback: Entry written off
               writeOffError: function( e, entryId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_wip_notif_wo_error' ); ?>", "error" );
               },

               // Success callback: Restore an entry that was written off
               restoreFromWriteOffSuccess: function( data, entryId, _self ) {

                  // Show entry is written off on both mock invoice and WIP report if it is present
                  $( '#entry-row-' + entryId ).removeClass( 'written-off-row' );
                  // Reload the invoice entries panel to update totals
                  reload_invoice_entry_panel();
                  if ( window.opener && window.opener.document ) {
                     // Uncheck the archive checkbox
                     $( '#write-off-' + entryId, window.opener.document ).prop( 'checked', false );
                     $( '#entry-row-' + entryId, window.opener.document ).removeClass( 'written-off-row' );
                  }
                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_wip_notif_restore_wo_success' ); ?>", "success" );
               },

               // Error callback: Restore an entry that was written off
               restoreFromWriteOffError: function( e, entryId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_wip_notif_restore_wo_error' ); ?>", "error" );
               },

               // Success callback: Delete a whole row from the report
               deleteRowSuccess: function( data, mockInvoiceRowId, _self ) {

                  // Check if a row was deleted
                  if ( data[0].rowDeleted ) {
                     // Remove the row if it was deleted
                     remove_minv_row( mockInvoiceRowId );

                  // Else, a single row remains, just the data was removed
                  // We do this so at least one row is always present ready for data input
                  } else {
                     // Refresh the form and load the blank row ready for input
                     print_invoice_details_form();
                     // Reload the invoice details display section
                     print_invoice_details_table();
                  }



                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_minv_notif_delete_row_success' ); ?>", "warning" );
               },

               // Error callback: Delete a whole row from the report
               deleteRowError: function( e, mockInvoiceRowId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_minv_notif_delete_row_error' ); ?>", "error" );
               },

               // Success callback: Delete a whole row from the report
               addRowSuccess: function( data, mockInvoiceRowId, _self ) {

                  // Check if a row was added
                  if ( data[0].rowAdded ) {
                     // Notify a new row as added
                     UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_minv_notif_add_row_success' ); ?>", "success" );

                     // Refresh the form and show the new row
                     print_invoice_details_form();

                  // Else, there is already a row without data, a new one was not added
                  } else {
                     // Notify about the exisitng row
                     UN.displayNotice( "<?php echo lang( 'system_notif_not_allowed' ); ?>", "<?php echo lang( 'report_minv_notif_add_row_already' ); ?>", "warning" );
                  }
               },

               // Error callback: Delete a whole row from the report
               addRowError: function( e, mockInvoiceRowId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_minv_notif_add_row_error' ); ?>", "error" );
               }

            }


            /** ----------------------------------------------------------------------------
             * Get the settings for the mock invoice report control form
             */
            function get_settings() {
               // Get the mock invoice settings from the database to set the form
               $.ajax({
                  url: '<?php echo ROOT_RELATIVE_PATH; ?>reports/mock-invoice/get_settings',
                  data: 'mockInvoiceId=' + mockInvoiceId,
                  type: 'POST',
                  dataType: 'json',
                  success: function ( data ) {
                     set_report_control_form( data );
                  },
                  error: function ( request, error ) {
                     UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "report_minv_notif_control_error" ); ?>', 'error' );
                  }
               });
            }


            /** ----------------------------------------------------------------------------
             * Set report control form controls to match stored settings of report
             */
            function set_report_control_form( data ) {

               if ( data[0].readyToInvoice == 1 ) {
                  $( "#ready" ).prev( '.ios-switch' ).trigger( 'click' );
                  // Change label text and color - The label provides a quick visual check of the current state
                  toggle_text( '#ready-label', '<?php echo lang( 'system_yes' ); ?>', '<?php echo lang( 'system_no' ); ?>' );
                  $( '#ready-label' ).toggleClass( 'label-warning' ).toggleClass( 'label-success' );
                  // Show or hide the section controls to complete an invoice (Can only complete when the invoice is ready)
                  $( '#complete-section' ).toggleClass( 'hidden' );
               }

               if ( data[0].archived == 1 ) {
                  $( "#complete" ).prev( '.ios-switch' ).trigger( 'click' );
                  // Change label text and color - The label provides a quick visual check of the current state
                  toggle_text( '#complete-label', '<?php echo lang( 'system_yes' ); ?>', '<?php echo lang( 'system_no' ); ?>' );
                  $( '#complete-label' ).toggleClass( 'label-warning' ).toggleClass( 'label-success' );
               }

               // Set the jobId global variable
               jobId = data[0].jobId;

               // Using setTimeout just to ensure form controls are set before event listeners are set
               setTimeout( function() { listen_report_controls() }, 500 );
            }


            /** ----------------------------------------------------------------------------
             * Prints out the mock invoice details form
             */
            function print_invoice_details_form() {
               $.ajax({
                  url: '<?php echo ROOT_RELATIVE_PATH; ?>reports/mock-invoice/print_invoice_details_form',
                  data: 'mockInvoiceId=' + mockInvoiceId,
                  type: 'POST',
                  dataType: 'html',
                  success: function ( html ) {
                     // Draw the form to screen
                     $( '#container-form-minv-details' ).html( html );
                     // Set plugins and event listeners on invoice details form
                     prepare_invoice_details_form();
                  },
                  error: function ( request, error ) {
                     UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "report_minv_fm_load_notif_error" ); ?>', 'error' );
                  }
               });
            }


            /** ----------------------------------------------------------------------------
             * Prints out the mock invoice details in a HTML table format
             */
            function print_invoice_details_table() {
               // Get the mock invoice details from the database to display them
               $.ajax({
                  url: '<?php echo ROOT_RELATIVE_PATH; ?>reports/mock-invoice/print_invoice_details_table',
                  data: 'mockInvoiceId=' + mockInvoiceId,
                  type: 'POST',
                  dataType: 'html',
                  success: function ( html ) {
                     // Draw the table with details to the screen if there is something to display
                     // Displays a message if there is nothing to show
                     $( '#container-minv-details' ).html( html );
                  },
                  error: function ( request, error ) {
                     UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "report_minv_notif_display_error" ); ?>', 'error' );
                  }
               });
            }


            // links and data used to update database via ajax
            var updateByMockInvoiceIdURL    = '<?php echo ROOT_RELATIVE_PATH ?>reports/mock-invoice/update_by_mock_invoice_id';
            var updateByMockInvoiceRowIdURL = '<?php echo ROOT_RELATIVE_PATH ?>reports/mock-invoice/update_by_mock_invoice_row_id';
            var ajaxData = '';


            // Because we are using bootstrap confirmation to delete entries and rows
            // we need to store the data in a variable to use in the onConfirm callback function
            var lastClickEntryId = '';
            var lastClickRowId   = '';
            // Grab the data using the click event and store to use later
            $( document ).on( 'click', '[data-toggle="delete-invoice-entry"], [data-toggle="delete-invoice-row"]', function( e ) {
            //$( '[data-toggle="delete-invoice-entry"], [data-toggle="delete-invoice-row"]' ).click( function () {
               lastClickEntryId = $( this ).data( 'entry-id' );
               lastClickRowId   = $( this ).data( 'invoice-row-id' );
            });


            /** ----------------------------------------------------------------------------
             * Handle actions of the invoice details form
             * Loads necessary plugins and listens for event handlers
             */
            function prepare_invoice_details_form() {

               // Select2 plugin for invoice categories
               $( '.invoice-category' ).select2({
                  placeholder: "<?php echo lang( 'report_minv_fm_category_ph' ); ?>",
                  allowClear: true,
                  theme: 'bootstrap'
               });

               // Autosize for invoice descriptions
               autosize( $( '.invoice-description' ) );

               // Limit numeric input fields
               $( ".numeric" ).numeric();

               // Certain functions are disabled or hidden depending on the stage the invoice is at
               // Call after form loaded to allow select2 plugin to load before hiding (Problem with width if hidden)
               process_invoice_stages( <?php echo $invoiceDetails->readyToInvoice . ', ' . $invoiceDetails->archived; ?> );


               /** ----------------------------------------------------------------------------
                * Handle actions of the invoice details form
                */

               // Update if a comment for a mock invoice row is changed
               $( '.invoice-category' ).change( function( e ) {

                  // Update the database
                  ajaxData = 'mockInvoiceRowId=' + $( this ).data( 'invoice-row-id' ) + '&category=' + $( this ).val();
                  do_ajax( updateByMockInvoiceRowIdURL, ajaxData, 'json', CALLBACK.updateCategorySuccess, CALLBACK.updateCategoryError );
               });

               // Update if a comment for a mock invoice row is changed
               $( '.invoice-description' ).change( function( e ) {

                  // Update the database
                  ajaxData = 'mockInvoiceRowId=' + $( this ).data( 'invoice-row-id' ) + '&description=' + encodeURIComponent( $( this ).val() );
                  do_ajax( updateByMockInvoiceRowIdURL, ajaxData, 'json', CALLBACK.updateDescriptionSuccess, CALLBACK.updateDescriptionError );
               });

               // Update if am amount for a mock invoice row is changed
               $( '.invoice-amount' ).change( function( e ) {

                  // Update the database
                  ajaxData = 'mockInvoiceRowId=' + $( this ).data( 'invoice-row-id' ) + '&amount=' + $( this ).val();
                  do_ajax( updateByMockInvoiceRowIdURL, ajaxData, 'json', CALLBACK.updateAmountSuccess, CALLBACK.updateAmountError);
               });

               // Confirm delete of an invoice row
               // [Bootstrap Confirmation plugin]
               $( '[data-toggle="delete-invoice-row"]' ).confirmation({
                  placement: 'left',
                  onConfirm: function( e ) {
                     e.preventDefault();
                     // Update the database and remove this row
                     ajaxData = 'mockInvoiceId=' + mockInvoiceId + '&mockInvoiceRowId=' + lastClickRowId;
                     do_ajax( '<?php echo ROOT_RELATIVE_PATH ?>reports/mock-invoice/delete_invoice_row', ajaxData, 'json', CALLBACK.deleteRowSuccess, CALLBACK.deleteRowError, lastClickRowId );
                  },
                  btnOkLabel: '<?php echo lang( 'system_btn_delete' ); ?>',
                  container: 'body'
               });

               // Add a new row to the invoice details form
               $( '#add-invoice-row' ).unbind( 'click' ).click( function( e ) {

                  // Update the database
                  ajaxData = 'mockInvoiceId=' + mockInvoiceId;
                  do_ajax( '<?php echo ROOT_RELATIVE_PATH ?>reports/mock-invoice/add_invoice_row', ajaxData, 'json', CALLBACK.addRowSuccess, CALLBACK.addRowError);
               });
            }


            /** ----------------------------------------------------------------------------
             * Handle actions of the report control form
             * We needed to get the mock invoice settings from the db and set up the form first
             * Now it will listen for user input
             */
            function listen_report_controls() {


               // Handle ready for invoicing controls [Ready to invoice switch]
               // Fix: When label is clicked for IOS7 Switch we need to change the switch state to match the checkbox
               $( '#form-minv' ).on( 'click', "label[for='ready']", function( e ) {
                  e.preventDefault();
                  $( "#ready" ).prev( '.ios-switch' ).trigger( 'click' );
               });
               // IOS7 switch actions to mark invoice as ready
               $( '#ready' ).on( 'change', function( e ) {
                  var isReady = false;

                  // Change label text and color - The label provides a quick visual check of the current state
                  toggle_text( '#ready-label', '<?php echo lang( 'system_yes' ); ?>', '<?php echo lang( 'system_no' ); ?>' );
                  $( '#ready-label' ).toggleClass( 'label-warning' ).toggleClass( 'label-success' );
                  // Show or hide the section controls to complete an invoice (Can only complete when the invoice is ready)
                  $( '#complete-section' ).toggleClass( 'hidden' );

                  // If invoice is changed from ready to being not ready, make sure invoice is also not set to complete
                  if ( ( $( '#ready' ).prop( 'checked' ) == false ) && ( $( '#complete' ).prop( 'checked' ) ) ) {
                     $( "#complete" ).prev( '.ios-switch' ).trigger( 'click' );
                  }

                  // Set ready to invoice to true if switch turned on
                  if ( $( '#ready' ).prop( 'checked' ) ) {
                     ajaxData = 'mockInvoiceId=' + mockInvoiceId + '&readyToInvoice=1';
                     isReady = true;
                  } else {
                     // Set both ready to invoice and archived (completed) to false if switch turned off
                     ajaxData = 'mockInvoiceId=' + mockInvoiceId + '&readyToInvoice=0&archived=0';
                  }

                  // Update the database
                  do_ajax( updateByMockInvoiceIdURL, ajaxData, 'json', CALLBACK.updateReadySuccess, CALLBACK.updateReadyError, isReady );
               });


               // Handle completed controls [Completed switch]
               // Fix: When label is clicked for IOS7 Switch we need to change the switch state to match the checkbox
               $( '#form-minv' ).on( 'click', "label[for='complete']", function( e ) {
                  e.preventDefault();
                  $( "#complete" ).prev( '.ios-switch' ).trigger( 'click' );
               });
               // IOS7 switch actions to mark invoice as ready
               $( '#complete' ).on( 'change', function( e ) {
                  var isComplete = false;

                  // Change label text and color - The label provides a quick visual check of the current state
                  toggle_text( '#complete-label', '<?php echo lang( 'system_yes' ); ?>', '<?php echo lang( 'system_no' ); ?>' );
                  $( '#complete-label' ).toggleClass( 'label-warning' ).toggleClass( 'label-success' );

                  // Set archived (completed) to true if switch turned on
                  if ( $( '#complete' ).prop( 'checked' ) ) {
                     ajaxData = 'mockInvoiceId=' + mockInvoiceId + '&archived=1';
                     isComplete = true;
                  } else {
                     // Set archived (completed) to false if switch turned off
                     ajaxData = 'mockInvoiceId=' + mockInvoiceId + '&archived=0';
                  }

                  // Update the database
                  do_ajax( '<?php echo ROOT_RELATIVE_PATH ?>reports/mock-invoice/complete_invoice', ajaxData, 'json', CALLBACK.completedSuccess, CALLBACK.completedError, isComplete );
               });

               // Handle changing the sort order for the mock invoice
               $( '#sort-order-control' ).on( 'change', function( e ) {

                  // It will update the sort order in the database and refresh the page if successful
                  // Updating the sort order changes it permanently for the mock invoice
                  ajaxData = 'mockInvoiceId=' + mockInvoiceId + '&sortOrder=' + $( this ).val();
                  // Update the database
                  do_ajax( updateByMockInvoiceIdURL, ajaxData, 'json', CALLBACK.updateSortOrderSuccess, CALLBACK.updateSortOrderError );
               });

               // Confirm delete of invoice [Delete invoice button]
               // [Bootstrap Confirmation plugin]
               $( '[data-toggle="delete-invoice"]' ).confirmation({
                  placement: 'top',
                  onConfirm: function( e ) {
                     e.preventDefault();
                     // Delete the mock invoice
                     ajaxData = 'mockInvoiceId=' + mockInvoiceId;
                     do_ajax( '<?php echo ROOT_RELATIVE_PATH ?>reports/mock-invoice/delete_invoice', ajaxData, 'json', CALLBACK.deleteSuccess, CALLBACK.deleteError );
                  },
                  btnOkLabel: 'Delete'
               });
            }


            /** ----------------------------------------------------------------------------
             * Handle actions of the mock invoice entries panel
             * If data is reloaded, we need to reset the event handlers to the new DOM content
             */
            function listen_invoice_entry_panel() {

               // Writing off report entries
               // Triggered by clicking on a write off checkbox
               $( '.write-off-checkbox' ).unbind( 'change' ).change( function() {
                  var _self = this;
                  var entryId = $( _self ).data( 'entry-id' );

                  if ( $( _self ).prop( 'checked' ) ) {

                     // Write off the entry
                     var ajaxData = 'entryId=' + entryId + '&writtenOff=1';
                     do_ajax( '<?php echo ROOT_RELATIVE_PATH ?>reports/wip/update_by_entry_id', ajaxData, 'json', CALLBACK.writeOffSuccess, CALLBACK.writeOffError, entryId, _self );

                  } else {

                     // Restore written off entries
                     var ajaxData = 'entryId=' + entryId + '&writtenOff=0';
                     do_ajax( '<?php echo ROOT_RELATIVE_PATH ?>reports/wip/update_by_entry_id', ajaxData, 'json', CALLBACK.restoreFromWriteOffSuccess, CALLBACK.restoreFromWriteOffError, entryId, _self );

                  }
               });

               // Confirm delete of a single invoice entry
               // [Bootstrap Confirmation plugin]
               $( '[data-toggle="delete-invoice-entry"]' ).confirmation({
                  singleton: true,
                  placement: 'left',
                  onConfirm: function( e ) {
                     e.preventDefault();
                     // Update the database and remove this entry
                     // We also need to remove the entire mock invoice if there are no more entries left
                     ajaxData = 'mockInvoiceId=' + mockInvoiceId + '&timesheetEntryId=' +  lastClickEntryId;
                     do_ajax( '<?php echo ROOT_RELATIVE_PATH ?>reports/mock-invoice/delete_single_entry', ajaxData, 'json', CALLBACK.deleteEntrySuccess, CALLBACK.deleteEntryError, lastClickEntryId );
                  },
                  btnOkLabel: '<?php echo lang( 'system_btn_delete' ); ?>',
                  container: 'body'
               });
             }


            // Click the panel header or table header to show/hide all entries
            $( document ).on( 'click', '.report-heading-row, .panel-heading-entries', function() {
               $( '.entry-row-job-' + $( this ).data( 'job-id') ).toggleClass( 'hidden' );
            });


            /** ----------------------------------------------------------------------------
             * Disables enables/certain functions of the invoice as it is marked as ready or complete
             *
             * @param  boolean isReady          If the invoice is marked as ready to invoice
             * @param  boolean isComplete       If the invoice is marked as complete
             * @return void
             */
            function process_invoice_stages( isReady, isComplete ) {

               // Show/hide certain elements if the invoice is/isn't ready
               ( isReady ? $( '.hidden-ready' ).addClass( 'hidden' ) : $( '.hidden-ready' ).removeClass( 'hidden' ) );

               // Change from 1/0 to true/false if needed for disabled state below
               ( isReady ? isReady = true : isReady = false );
               // Disable/enable all write off checkboxes based on ready state
               $( '.write-off-checkbox' ).attr( 'disabled', isReady );

               // Show/Hide or disable certain elements if the invoice is/isn't complete
               ( isComplete ? $( '.hidden-complete' ).addClass( 'hidden' ) : $( '.hidden-complete' ).removeClass( 'hidden' ) );
            }


            /** ----------------------------------------------------------------------------
             * Reloads the invoice entries in the panel at the bottom of the report
             *
             * @return void
             */
            function reload_invoice_entry_panel() {

               // Get the mock invoice settings from the database to set the form
               $.ajax({
                  url: '<?php echo ROOT_RELATIVE_PATH; ?>reports/mock-invoice/print_invoice_entries_panel',
                  data: 'mockInvoiceId=' + mockInvoiceId,
                  type: 'POST',
                  dataType: 'html',
                  success: function ( html ) {
                     // Update the panel html
                     $( '#container-minv-entries-panel' ).html( html );
                     // Reset panel event handlers
                     listen_invoice_entry_panel();
                  },
                  error: function ( request, error ) {
                     UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "report_minv_notif_entries_error" ); ?>', 'error' );
                  }
               });
            }


         });
      </script>