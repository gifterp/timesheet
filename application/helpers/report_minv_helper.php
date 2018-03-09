<?php
/**
 * Mock Invoice Report Helper
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */


/** ----------------------------------------------------------------------------
 * Creates the HTML to display entries included in a mock invoice
 *
 * @param   array    $reportEntries       Array with entries to be included in the report
 * @return  void                          Prints the HTML to display the report
 */
function print_minv_report( $reportEntries, $invoiceDetails, $mockInvoiceId ) {

   // Check we have some items in the recordset and an invoiceId has been passed
   if ( empty( $reportEntries ) or $mockInvoiceId == '' ) {
      redirect( ROOT_RELATIVE_PATH . 'search', 'location' );
   }

   print_wip_report( $reportEntries, $invoiceDetails->sortOrder, '', true );
}



/** ----------------------------------------------------------------------------
 * Creates a filterable list for any invoices marked as ready but not yet complete
 *
 * @return  void
 */
function list_ready_invoices( ) {

   // Get a reference to the controller object to access the mock invoice
   $CI =& get_instance();
   // Get any invoice details
   $invoiceResults = $CI->mockinvoice_model->get_ready_invoices();

   $listHTML = '';

   // Show we have nothing if there are no ready invoices
   if ( empty( $invoiceResults ) ) {
      echo lang( 'system_msg_nothing_display' );

   // Cycle through the invoices to build a list
   } else {
      foreach ( $invoiceResults as $row ) {
         $jobTitle = format_job_ref_name( $row['jobRef'], $row['jobName'], $row['childRef'], $row['childName'] );
         $parentJobName = $row['jobName'];

         $listHTML .= <<< HTML
                                       <div class="filtered-list-link important-list" data-id="{$row['mockInvoiceId']}">
                                          #{$row['mockInvoiceId']} | {$jobTitle}
                                          <span class="job-title">{$jobTitle}</span>
                                          <span class="parent-job-name">{$parentJobName}</span>
                                          <span class="invoice-no">{$row['mockInvoiceId']}</span>
                                       </div>
HTML;
      }
   }

   // Output the HTML if we have some
   if ( $listHTML != '' ) {
      echo <<< HTML
                                 <div id="ready-invoice-list">
                                    <div class="input-group mb-md">
                                       <span class="input-group-addon">
                                          <i class="fa fa-search"></i>
                                       </span>
                                       <input type="text" class="form-control search" placeholder="Filter invoices">
                                    </div>
                                    <div class="list">
{$listHTML}
                                    </div>
                                 </div>
HTML;
   }

}


/** ----------------------------------------------------------------------------
 * Creates tables to display invoice information for a job.
 *
 * @param   array $_POST      Job id is sent via POST
 */
function create_invoice_html( $invoiceArray ) {

   $currentInvoiceRows = '';
   $completeInvoiceRows = '';
   $currentTableClass = ' hidden';
   $completeTableClass = ' hidden';


   if ( $invoiceArray != '' ) {
      foreach ( $invoiceArray as $row ) {

         // If invoice is fully completed
         if ( $row['readyToInvoice'] and $row['archived'] ) {

            // Show the completed table (No hidden class)
            $completeTableClass = '';
            $completeInvoiceRows .= <<< HTML
                                          <tr class="clickable-row cursor-hand" data-mock-invoice-id="{$row['mockInvoiceId']}">
                                             <td>{$row['mockInvoiceId']}</th>
                                             <td><span class="label label-success">Yes</span></td>
                                             <td><span class="label label-success">Yes</span></td>
                                          </tr>

HTML;
         // If invoice is ready to be processed but not yet completed
         } elseif ( $row['readyToInvoice'] ) {
            // Show the current table (No hidden class)
            $currentTableClass = '';
            $currentInvoiceRows .= <<< HTML
                                          <tr class="clickable-row cursor-hand" data-mock-invoice-id="{$row['mockInvoiceId']}">
                                             <td>{$row['mockInvoiceId']}</th>
                                             <td><span class="label label-success">Yes</span></td>
                                             <td><span class="label label-warning">No</span></td>
                                          </tr>

HTML;
         // Invoice is not yet ready to be processed
         } else {
            // Show the current table (No hidden class)
            $currentTableClass = '';
            $currentInvoiceRows .= <<< HTML
                                          <tr class="clickable-row cursor-hand" data-mock-invoice-id="{$row['mockInvoiceId']}">
                                             <td>{$row['mockInvoiceId']}</th>
                                             <td><span class="label label-warning">No</span></td>
                                             <td><span class="label label-warning">No</span></td>
                                          </tr>

HTML;
         }
      }
   }

   // Return the html
   return <<< HTML

                              <div class="row{$currentTableClass}">
                                 <div class="col-md-12">
                                    <div class="table-responsive">
                                       <table class="table table-striped table-bordered table-hover">
                                       <thead>
                                          <tr class="report-heading-row">
                                             <th colspan="4">Current Invoices</th>
                                          </tr>
                                          <tr class="total-heading-row">
                                             <th>Inv. No</th>
                                             <th>Ready</th>
                                             <th>Completed</th>
                                          </tr>
                                       </thead>
                                       <tbody>
{$currentInvoiceRows}
                                       </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                              <div class="row{$completeTableClass}">
                                 <div class="col-md-12">
                                    <div class="table-responsive">
                                       <table class="table table-striped table-bordered table-hover">
                                       <thead>
                                          <tr class="total-heading-row">
                                             <th colspan="4">Completed Invoices</th>
                                          </tr>
                                          <tr class="total-heading-row">
                                             <th>Inv. No</th>
                                             <th>Ready</th>
                                             <th>Completed</th>
                                          </tr>
                                       </thead>
                                       <tbody>
{$completeInvoiceRows}
                                       </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>

HTML;
}
?>