/**
 * Functions used by the reports
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:     Matt Batten <matt@improvedsoftware.com.au>
 * Requires:   [Vendor] jQuery
*/


/** ----------------------------------------------------------------------------
 * Functions to update the elements on the mock invoice report
 */

// Removes a single entry from the mock invoice report
function remove_minv_entry( entryId ) {
   $( '#entry-row-' + entryId ).remove();
}

// Removes an invoice details row from the mock invoice report (both form and display table)
function remove_minv_row( mockInvoiceRowId ) {
   $( '#invoice-form-row-' + mockInvoiceRowId ).remove();
   $( '#invoice-details-row-' + mockInvoiceRowId ).remove();
}



/** ----------------------------------------------------------------------------
 * Functions to update the elements on the parent WIP report
 * We have to also update the WIP report when certain changes occur on our mock invoice report
 */

// Removes the invoice number and restores the invoice checkbox for an entry on the WIP report
// When an entry is removed from the mock invoice, we must allow it to be added to another invoice
function restore_wip_entry_invoice_checkbox( entryId ) {

   if ( window.opener && window.opener.document ) {
      // Enable all checkboxes
      $( '#archive-' + entryId + ', #write-off-' + entryId + ', #invoice-checkbox-' + entryId, window.opener.document ).attr( 'disabled', false );
      // Remove the invoice link
      $( '#invoice-link-' + entryId, window.opener.document ).html( '' );
      // Uncheck the invoice checkbox
      $( '#invoice-checkbox-' + entryId, window.opener.document ).prop( 'checked', false );
      // Show the invoice checkbox
      $( '#invoice-checkbox-' + entryId, window.opener.document ).toggleClass( 'hidden' );
      // Uncheck the archive checkbox
      $( '#archive-' + entryId, window.opener.document ).prop( 'checked', false );
      // Remove the archived row class if there is one
      $( '#entry-row-' + entryId, window.opener.document ).removeClass( 'archived-row' );
      // Uncheck the written off checkbox
      $( '#write-off-' + entryId, window.opener.document ).prop( 'checked', false );
      // Remove the written off row class if there is one
      $( '#entry-row-' + entryId, window.opener.document ).removeClass( 'written-off-row' );
   }
}

// Changes the display on the WIP to show the entry as being archived/not archived
// Also updates the mock invoice report class to show archived state
function archive_wip_entry( entryId, archived ) {

   // Check the entry is not written off on the invoice first
   if ( $( '#write-off-' + entryId ).prop( 'checked' ) == false ) {
      if ( window.opener && window.opener.document ) {
         if ( archived ) {
            // Check the archived checkbox
            $( '#archive-' + entryId, window.opener.document ).prop( 'checked', true );
            // Add the archived row class to show the entry is archived
            $( '#entry-row-' + entryId, window.opener.document ).addClass( 'archived-row' );
            $( '#entry-row-' + entryId ).addClass( 'archived-row' ); // Mock invoice
         } else {
            // Uncheck the archived checkbox
            $( '#archive-' + entryId, window.opener.document ).prop( 'checked', false );
            // Remove the archived row class
            $( '#entry-row-' + entryId, window.opener.document ).removeClass( 'archived-row' );
            $( '#entry-row-' + entryId ).removeClass( 'archived-row' ); // Mock invoice
         }
      }
   }
}


// Checks if a job has invoices
// Adds/removes the button to show them, displays/removes the dropdown with current invoices
function manage_invoices_wip_job_control_panel( jobId, rootRelativePath, updateParentWindow ) {

   var hasInvoices    = false;
   var invoiceOptions = '';
   var invoiceChooser = '<input type="hidden" name="invoice-chooser-' + jobId + '" id="invoice-chooser-' + jobId + '" value="" />';
   var invoiceSummary = '';

   $.ajax({
      url: rootRelativePath + 'reports/mock-invoice/get_invoices',
      data: 'jobId=' + jobId,
      type: 'POST',
      dataType: 'json',
      success: function ( data ) {

         // Loop through any invoices, build an option list if there are any current invoices
         $.each( data, function( key, element ) {
            if ( element.mockInvoiceId == '' ) {
               invoiceSummary = element.invoiceHTML;
            } else {
               hasInvoices = true;
               if ( element.readyToInvoice == 0 ) { invoiceOptions += '<option value="' + element.mockInvoiceId + '">' + element.mockInvoiceId + '</option>'; }
            }
         });

         // Create HTML for dropdown list if we have current invoices
         if ( invoiceOptions != '' ) {
            invoiceChooser = '<select name="invoice-chooser-' + jobId + '" id="invoice-chooser-' + jobId + '" class="invoice-select va-middle"><option value="">New invoice</option>' + invoiceOptions + '</select>';
         }

         // Update the WIP job control panel with the current invoice controls
         // We wither update the parent window (called from mock invoice) or itself
         if ( updateParentWindow ) { // Called from mock invoice
            if ( window.opener && window.opener.document ) {
               // Show/hide the button to view invoices
               ( hasInvoices ? $( '#container-invoice-btn-' + jobId, window.opener.document ).removeClass( 'hidden' ) : $( '#container-invoice-btn-' + jobId, window.opener.document ).addClass( 'hidden' ) )
               // Display the invoice chooser dropdown or a hidden input field
               $( '#container-invoice-chooser-' + jobId, window.opener.document ).html( invoiceChooser );
               // Update the invoice summary tables
               $( '#job-invoice-section-' + jobId, window.opener.document ).html( invoiceSummary );

            }
         } else {  // Update itself
               // Show/hide the button to view invoices
               ( hasInvoices ? $( '#container-invoice-btn-' + jobId ).removeClass( 'hidden' ) : $( '#container-invoice-btn-' + jobId ).addClass( 'hidden' ) )
               // Display the invoice chooser dropdown or a hidden input field
               $( '#container-invoice-chooser-' + jobId ).html( invoiceChooser );
               // Update the invoice summary tables
               $( '#job-invoice-section-' + jobId ).html( invoiceSummary );

               //listen_invoice_table_clicks();
         }
      }
   });
}


/** ----------------------------------------------------------------------------
 * Base AJAX function
 */
function do_ajax( ajaxURL, ajaxData, dataType, successCallback, errorCallback, id, _self ) {

   $.ajax({
      type: 'POST',
      url: ajaxURL,
      data: ajaxData,
      dataType: dataType,
      success: function( data ) {
         if ( typeof( successCallback ) == "function" ) {
            successCallback( data, id, _self );
         }
      },
      error: function( e ) {
         if ( typeof( errorCallback ) == "function" ) {
            errorCallback( e, id, _self );
         }
      }
   });
}

