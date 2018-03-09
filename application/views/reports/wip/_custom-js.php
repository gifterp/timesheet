<?php
/**
 * Report - Work in progress Custom JavaScript
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


            /** ----------------------------------------------------------------------------
             * Set up report form plugins and event handlers
             */
            // Limit numeric input fields
            $( ".numeric" ).numeric();

            // Set up datepicker fields
            $( '.datepicker' ).datepicker({
               format: 'dd/mm/yyyy',
               autoclose: true,
               orientation : 'bottom',
               clearBtn: true,
               todayBtn: 'linked',
               todayHighlight: true
            });

            // Button to clear dates in the date range section
            $( '.clear-dates' ).click( function() {
               $( '#startDate' ).datepicker( 'update', '' );
               $( '#endDate' ).datepicker( 'update', '' );
            });

            // Button to click on all headers and expand/collapse
            $( '.click-all-headers' ).click( function() {
               $( '.report-panel-header' ).trigger( 'click' );
            });

            // Button to show/hide all entries
            $( '.show-hide-entries' ).click( function() {
               $( '.entry-row' ).toggleClass( 'hidden' );
               toggle_text( this, <?php echo "'" . lang( 'report_btn_show_entries' ) . "', '" . lang( 'report_btn_hide_entries' ) . "'"; ?> );
            });

            // Click a job header row to show/hide all entries
            $( document ).on( 'click', '.report-heading-row', function() {
               $( '.entry-row-job-' + $( this ).data( 'job-id') ).toggleClass( 'hidden' );
            });


            /** ----------------------------------------------------------------------------
             * Object to handle the ajax success/error requests
             */
            CALLBACK = { 

               // Success callback: Archive an entry
               addToArchiveSuccess: function( data, entryId, _self ) {

                  // Disable ability to write off or invoice
                  $( '#write-off-' + entryId + ', #invoice-checkbox-' + entryId ).attr( 'disabled', true );
                  $( '#entry-row-' + entryId ).addClass( 'archived-row' );
                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_wip_notif_archive_success' ); ?>", "info" );
               },

               // Error callback: Archive an entry
               addToArchiveError: function( e, entryId, _self ) {

                  // Return archive checkbox to previous state
                  $( _self ).prop( 'checked', false );   // Not checked
                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_wip_notif_archive_error' ); ?>", "error" );
               },

               // Success callback: Restore entry from archive
               restoreFromArchiveSuccess: function( data, entryId, _self ) {

                  // Enable writing off or invoicing again
                  $( '#write-off-' + entryId + ', #invoice-checkbox-' + entryId ).attr( 'disabled', false );
                  $( '#entry-row-' + entryId ).removeClass( 'archived-row' );
                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_wip_notif_unarchive_success' ); ?>", "success" );
               },

               // Error callback: Restore entry from archive
               restoreFromArchiveError: function( e, entryId, _self ) {

                  // Return archive checkbox to previous state
                  $( _self ).prop( 'checked', true );  // Checked
                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_wip_notif_unarchive_error' ); ?>", "error" );
               },

               // Success callback: Write off an entry
               writeOffSuccess: function( data, entryId, _self ) {

                  // Disable ability to archive or invoice
                  $( '#archive-' + entryId + ', #invoice-checkbox-' + entryId ).attr( 'disabled', true );
                  $( '#entry-row-' + entryId ).addClass( 'written-off-row' );
                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_wip_notif_wo_success' ); ?>", "warning" );
               },

               // Error callback: Write off an entry
               writeOffError: function( e, entryId, _self ) {

                  // Return written off checkbox to previous state
                  $( _self ).prop( 'checked', false );   // Not checked
                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_wip_notif_wo_error' ); ?>", "error" );
               },

               // Success callback: Restore an entry that was written off
               restoreFromWriteOffSuccess: function( data, entryId, _self ) {

                  // Enable all functionality
                  $( '#archive-' + entryId + ', #invoice-checkbox-' + entryId ).attr( 'disabled', false );
                  $( '#entry-row-' + entryId ).removeClass( 'written-off-row' );
                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_wip_notif_restore_wo_success' ); ?>", "success" );
               },

               // Error callback: Restore an entry that was written off
               restoreFromWriteOffError: function( e, entryId, _self ) {

                  // Return written off checkbox to previous state
                  $( _self ).prop( 'checked', true );   // Checked
                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_wip_notif_restore_wo_error' ); ?>", "error" );
               },

               // Success callback: Displaying job totals
               jobTotalsSuccess: function( html, jobId, _self ) {

                  // Replace the button with the job totals
                  $( '#job-total-section-' + jobId ).html( html );
               },

               // Error callback: Displaying job totals
               jobTotalsError: function( e, jobId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_wip_notif_job_totals_error' ); ?>", "error" );
               },

               // Success callback: Adding items to mock invoice
               addToInvoiceSuccess: function( json, jobId, _self ) {

                  // Mark all entries on WIP report with correct invoice number and link
                  var entryArray = json[0].entries.split(', ');
                  for ( i = 0; i < entryArray.length; ++i ) {
                     $( '#invoice-link-' + entryArray[i] ).html( '<a href="<?php echo base_url() ?>reports/mock-invoice?id=' + json[0].mockInvoiceId + '&wip=1" target="_blank">' + json[0].mockInvoiceId + '</a>' );
                     $( '#invoice-checkbox-' + entryArray[i] ).toggleClass( 'hidden' );
                     $( '#invoice-checkbox-' + entryArray[i] ).prop( 'checked', false );
                  }

                  // Update the invoice details for the job
                  manage_invoices_wip_job_control_panel( jobId, '<?php echo ROOT_RELATIVE_PATH; ?>', false );

                  // Open the mock invoice
                  newWindow.location = '<?php echo base_url() ?>reports/mock-invoice?id=' + json[0].mockInvoiceId + '&wip=1';

                  UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'report_wip_notif_add_invoice_success' ); ?>", "success" );
               },

               // Error callback: Adding items to mock invoice
               addToInvoiceError: function( e, entryId, _self ) {

                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_wip_notif_add_invoice_error' ); ?>", "error" );
               }
            }



            /** ----------------------------------------------------------------------------
             * Handle archiving and writing off entries
             * Also handle disabling/enabling other checkboxes when invoice checkbox is used
             */

            var updateByEntryIdURL = '<?php echo ROOT_RELATIVE_PATH ?>reports/wip/update_by_entry_id';

            // Archiving of report entries
            // Triggered by clicking on an archive checkbox
            $( '.archive-checkbox' ).unbind( 'change' ).change( function() {
               var _self = this;
               var entryId = $( _self ).data( 'entry-id' );

               if ( $( _self ).prop( 'checked' ) ) {

                  // Archive the entry
                  var ajaxData = 'entryId=' + entryId + '&archived=1';
                  do_ajax( updateByEntryIdURL, ajaxData, 'json', CALLBACK.addToArchiveSuccess, CALLBACK.addToArchiveError, entryId, _self );

               } else {

                  // Restore the archived entry
                  var ajaxData = 'entryId=' + entryId + '&archived=0';
                  do_ajax( updateByEntryIdURL, ajaxData, 'json', CALLBACK.restoreFromArchiveSuccess, CALLBACK.restoreFromArchiveError, entryId, _self );
               }
            });

            // Writing off report entries
            // Triggered by clicking on a write off checkbox
            $( '.write-off-checkbox' ).unbind( 'change' ).change( function() {
               var _self = this;
               var entryId = $( _self ).data( 'entry-id' );

               if ( $( _self ).prop( 'checked' ) ) {

                  // Write off the entry
                  var ajaxData = 'entryId=' + entryId + '&writtenOff=1';
                  do_ajax( updateByEntryIdURL, ajaxData, 'json', CALLBACK.writeOffSuccess, CALLBACK.writeOffError, entryId, _self );

               } else {

                  // Restore written off entries
                  var ajaxData = 'entryId=' + entryId + '&writtenOff=0';
                  do_ajax( updateByEntryIdURL, ajaxData, 'json', CALLBACK.restoreFromWriteOffSuccess, CALLBACK.restoreFromWriteOffError, entryId, _self );

               }
            });

            // Using the invoice checkboxes
            // Disable/enable other checkboxes
            $( '.invoice-checkbox' ).unbind( 'change' ).change( function() {
               var entryId = $( this ).data( 'entry-id' );

               if ( $( this ).prop( 'checked' ) ) {
                  // Disable ability to archive or write off
                  $( '#archive-' + entryId + ', #write-off-' + entryId ).attr( 'disabled', true );
               } else {
                  // Enable ability to archive or write off
                  $( '#archive-' + entryId + ', #write-off-' + entryId ).attr( 'disabled', false );
               }
            });


            /** ----------------------------------------------------------------------------
             * Handle job control panel actions
             */

            // Hide/show text for totals and invoice buttons
            $( '.btn-job-totals, .btn-job-invoices' ).unbind( "click" ).click( function() {
               toggle_text( this, 'Show', 'Hide' );
            });
            // Display all totals for a job if the button to do this is pressed
            $( '.btn-all-job-totals' ).unbind( 'click' ).click( function() {
               var _self = this;
               var jobId = $( _self ).data( 'job-id' );
               var ajaxURL = '<?php echo ROOT_RELATIVE_PATH ?>reports/wip/create_job_total_html';
               var ajaxData = 'jobId=' + jobId + <?php echo "'&startDate=" . create_int_date_format( @$_POST['startDate'] ) . "&endDate=" . create_int_date_format( @$_POST['endDate'] ) . "'" ?>;
               do_ajax( ajaxURL, ajaxData, 'html', CALLBACK.jobTotalsSuccess, CALLBACK.jobTotalsError, jobId, _self );
            });

            // Check/uncheck all job invoice checkboxes - but only those that are still enabled
            $( document ).on( 'change', '.job-invoice-checker', function() {
               $( '.checkbox-job-' + $( this ).data( 'job-id') + ':enabled' ).prop( 'checked', $( this ).prop( 'checked' ) );
            });

            // Add items to an invoice
            $( '.add-invoice-btn' ).unbind( "click" ).click( function() {
               var jobId = $( this ).data( 'job-id' );
               // Get the selected items to add to the mock invoice
               var entries = $( '#form-' + jobId + ' .invoice-checkbox:checked' ).map( function() {
                     return this.value;
                  }).get().join( ', ' );
               var mockInvoiceId = $( '#invoice-chooser-' + jobId ).val();
               if ( entries != '' ) {
                  var ajaxData = 'jobId=' + jobId + '&entries=' + entries + '&mockInvoiceId=' + mockInvoiceId;
                  // Open a new window now using the click event to prevent pop up blockers blocking it
                  // In the AJAX success callback method we will update the window with the correct information
                  newWindow = window.open( '', '_blank' );
                  do_ajax( '<?php echo ROOT_RELATIVE_PATH ?>reports/mock-invoice/add_to_invoice', ajaxData, 'json', CALLBACK.addToInvoiceSuccess, CALLBACK.addToInvoiceError, jobId, this );
                  // Update the invoice controls on the job control panel
                  manage_invoices_wip_job_control_panel( jobId, '<?php echo ROOT_RELATIVE_PATH; ?>', false );
               } else {
                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'report_wip_notif_add_invoice_none' ); ?>", "error" );
               }
            });

            // Handle table rows (job control panel - show invoices) being linked to open the mock invoices
            $( document ).on( 'click', '.clickable-row', function() {
               window.open( '<?php echo ROOT_RELATIVE_PATH ?>reports/mock-invoice?id=' + $( this ).data( 'mock-invoice-id' ) + '&wip=1', '_blank' );
            });

         });
      </script>