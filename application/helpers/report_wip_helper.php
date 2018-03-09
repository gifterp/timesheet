<?php
/**
 * Work in Progress Report Helper
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */


/** ----------------------------------------------------------------------------
 * Creates the HTML to display entries included in the WIP report
 *
 * @param   array    $reportEntries       Array with entries to be included in the report
 * @param   string   $sortOrder           The sort order selected in the report form
 * @param   array    $filter              Array with the filter options chosen in the report form
 * @param   boolean  $displayMockInvoice  True if it is displayed on the mock invoice report
 * @return  void                          Prints the HTML to display the report
 */
function print_wip_report( $reportEntries, $sortOrder, $filter, $displayMockInvoice = false ) {

   $totalEntries = (int)count( $reportEntries );
   // Used for comparison
   $jobRef       = '';
   $subtotalName = '';
   // The field which we will print subtotals for
   $sortField = _get_primary_sort_field( $sortOrder );
   // The data converted into table rows (HTML)
   $tableRows = '';
   // Handle if the job has invoices attached
   $hasInvoices = false;
   $invoiceList = '';
   $invoiceSummaryHTML = '';
   // Totals for the report - Currency and time in hours are totalled
   $totalKeys = array( 'entry', 'sortOption', 'sortOptionHours', 'report', 'reportHours', 'current', 'currentHours', 'archived', 'archivedHours', 'writtenOff', 'writtenOffHours', 'currentReportPeriod', 'currentHoursReportPeriod', 'archivedReportPeriod', 'archivedHoursReportPeriod', 'writtenOffReportPeriod', 'writtenOffHoursReportPeriod', 'grand', 'grandHours' );
   $totals = array_fill_keys( $totalKeys, 0 );



   for ( $i = 0; $i < $totalEntries; $i++ ) {
      // Set the job reference and the field we will subtotal on so we can compare them
      $jobRef = format_job_ref( $reportEntries[$i]['jobRef'], $reportEntries[$i]['childRef'] );
      $subtotalName = _create_subtotal_name( $sortField, $reportEntries[$i] );

      // Calculate the total for the timesheet entry
      $totals['entry'] = calculate_entry_total( $reportEntries[$i]['timeTaken'], $reportEntries[$i]['chargeRate'], $reportEntries[$i]['chargeable'], $reportEntries[$i]['disbursement'] );

      // Add a single entry data to a table row. Then update our totals accordingly
      $tableRows .= _create_entry_row( $reportEntries[$i], $totals['entry'], $displayMockInvoice );
      update_totals( $totals, $reportEntries[$i], false, '', '', $displayMockInvoice );


      // Create a subtotal row if...
      if ( ( $i == $totalEntries-1 ) or  // There is no more data to come
         ( $jobRef != format_job_ref( $reportEntries[$i+1]['jobRef'], $reportEntries[$i+1]['childRef'] ) ) or  // A new job is next
         ( $subtotalName != _create_subtotal_name( $sortField, $reportEntries[$i+1] ) ) ) {  // The item to subtotal changes

         $tableRows .= _create_subtotal_row( $subtotalName, $totals['sortOption'], $totals['sortOptionHours'] );
         $totals['sortOption']      = 0;  // Reset the totals for the primary sort field
         $totals['sortOptionHours'] = 0;
      }


      // Print the report for each job if...
      if ( ( $i == $totalEntries-1 ) or  // There is no more data to come
         ( $jobRef != format_job_ref( $reportEntries[$i+1]['jobRef'], $reportEntries[$i+1]['childRef'] ) ) ) {  // A new job is next

         // Get the job id.
         $jobId = _get_job_id( $reportEntries[$i] );

         // Total for the report
         $tableRows .= _create_report_total_row( $totals['report'], $totals['reportHours'] );

         // Print the report panel for this job
         _print_job_panel( $reportEntries[$i], $tableRows, $jobId, $displayMockInvoice );

         // Only process items for the job control panel if using the WIP report
         if ( !$displayMockInvoice ) {
            // Check for invoices attached to this job and create necessary HTML
            $invoiceList = _check_job_invoices( $hasInvoices, $invoiceSummaryHTML, $jobId );

            // Print the control panel for this job
            _print_job_control_panel( $reportEntries[$i], $totals, $jobId, $hasInvoices, $invoiceList, $invoiceSummaryHTML );
         }

         // Clear all totals at the end of a job
         _reset_totals( $totals, $totalKeys );

         // Reset our table row data and invoice flag for next job
         $tableRows   = '';
         $hasInvoices = false;
      }
   }

   // Show an alert if there are no results
   if ( $totalEntries == 0 ) {
       echo '<div class="alert alert-danger"><b>Your search returned no results. Please try again</b></div>';
   }

}


/** ----------------------------------------------------------------------------
 * Creates the name used for the report subtotal
 *
 * @param   array    $sortField      Primary sort field
 * @param   string   $reportEntry    Array with entry details
 * @return  void
 */
function _create_subtotal_name( $sortField, $reportEntry ) {

   // Set to user name by default
   $subtotalName = $reportEntry['name'];

   // Set to date with format dd/mm/yyyy if using date
   if ( $sortField == 'date' ) {
      $subtotalName = format_db_date( $reportEntry['startDateTime'] );

   // Set to task name if using task to total by
   } elseif ( $sortField == 'task' ) {
      $subtotalName = format_task_name( $reportEntry['taskGroup'], $reportEntry['taskName'] );
   }

   return $subtotalName;
}


/** ----------------------------------------------------------------------------
 * Outputs a panel with report information for a single job in the report
 *
 * @param   array    $reportEntry    Array with entry details
 * @return  void
 */
function _print_job_panel( $reportEntry, $tableRows, $jobId, $displayMockInvoice ) {

   // Place CI language variables into php variables to use in heredoc syntax
   $archivedTooltip     = lang( 'report_wip_ttip_archived_col' );
   $writtenOffTooltip   = lang( 'report_wip_ttip_written_off_col' );
   $invoiceTooltip      = lang( 'report_wip_ttip_invoice_col' );
   $removeTooltip       = lang( 'report_minv_ttip_remove_col' );

   // Create the title for the panel
   $panelSubTitle = '';  // We only have a subtitle if it is a child job. The subtitle will list the parent job
   if ( $reportEntry['childRef'] != null ) { $panelSubTitle = '<p class="panel-subtitle">Sub file of ' . format_job_ref_name( $reportEntry['jobRef'], $reportEntry['jobName'] ) . '</p>'; }
   $panelTitle = '<h2 class="panel-title report-panel-header">' . format_job_ref_name( $reportEntry['jobRef'], $reportEntry['jobName'], $reportEntry['childRef'], $reportEntry['childName']) . '</h2>' . $panelSubTitle;

   // Our columns and panel header change slightly if displayed on a mock invoice report
   if ( $displayMockInvoice ) {
      // Panel header settings for mock invoice
      $panelHeaderSettings = 'class="panel-heading panel-heading-entries cursor-hand" data-job-id="' . $jobId . '"';
      // Columns for mock invoice report
      $columns = <<< HTML
                                             <th class="checkbox-column">W/O <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="{$writtenOffTooltip}"></i></th>
                                             <th class="checkbox-column"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="{$removeTooltip}"></i></th>
HTML;
   } else {
      // Panel header settings for WIP Report
      $panelHeaderSettings = 'class="panel-heading" data-panel-toggle';
      // Columns for WIP Report
      $columns = <<< HTML
                                             <th class="checkbox-column">Arc. <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="{$archivedTooltip}"></i></th>
                                             <th class="checkbox-column">W/O <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="{$writtenOffTooltip}"></i></th>
                                             <th class="checkbox-column">Inv. <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="{$invoiceTooltip}"></i></th>
HTML;
   }

   // Print the panel for the job
   echo <<< HTML
                     <section class="panel panel-featured panel-featured-dark">
                        <header {$panelHeaderSettings}>
                           {$panelTitle}
                        </header>
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-12">
                                 <div class="table-responsive">
                                    <form id="form-{$jobId}">
                                       <table class="table table-striped table-bordered table-hover table-report" id="job-table-{$jobId}">
                                       <thead>
                                          <tr class="report-heading-row cursor-hand" data-job-id="{$jobId}">
                                             <th>Date</th>
                                             <th>Staff</th>
                                             <th>Task</th>
                                             <th>Description</th>
                                             <th>Hrs</th>
                                             <th>Total</th>
{$columns}
                                          </tr>
                                       </thead>
                                       <tbody>

{$tableRows}

                                       </tbody>
                                       </table>
                                    </form>
                                 </div>
                              </div>

                           </div>
                        </div>
                     </section>

HTML;
}


/** ----------------------------------------------------------------------------
 * Outputs a panel with controls to show totals and also add/show invoices
 *
 * @param   array    $reportEntry        The entry details used to get the job name and id
 * @param   array    $totals             Array with currency and hour totals for the job
 * @return  void
 */
function _print_job_control_panel( $reportEntry, $totals, $jobId, $hasInvoices, $invoiceList, $invoiceSummaryHTML ) {

   // Language variables for heredoc syntax
   $currencySymbol = lang( 'system_currency_symbol' );

   // Get the job name in the proper format
   $jobName = format_job_ref_name( $reportEntry['jobRef'], $reportEntry['jobName'], $reportEntry['childRef'], $reportEntry['childName'] );

   // Show/hide the button to view invoices if this job has some
   ( $hasInvoices ? $invoiceClass = '' : $invoiceClass = ' class="hidden"' );


   // Print the control panel for the job
   echo <<< HTML
                     <section class="panel job-control-panel">

                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-4">
                                 <button class="btn btn-job-totals" data-toggle="collapse" data-target="#job-totals-{$jobId}">Show totals</button>
                                 <span id="container-invoice-btn-{$jobId}"{$invoiceClass}><button class="btn btn-job-invoices" data-toggle="collapse" data-job-id="{$jobId}" data-target="#job-invoices-{$jobId}">Show invoices</button></span>
                              </div>
                              <div class="col-md-8 text-right">
                                 <span class="ib mt-xs mr-xs"><input type="checkbox" class="job-invoice-checker va ml-xs" data-job-id="{$jobId}" /></span>
                                 <a class="btn btn-primary add-invoice-btn ib" data-job-id="{$jobId}">Add to invoice</a>
                                 <div id="container-invoice-chooser-{$jobId}" class="ib">{$invoiceList}</div>
                              </div>

                           </div>
                        </div>
                        <div class="panel-footer collapse" id="job-totals-{$jobId}">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                       <tr class="total-heading-row">
                                          <th colspan="3">Report breakdown <i class="fa fa-arrow-right"></i> {$jobName}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <th class="total-subheading">Current</th>
                                          <td>{$totals['currentHours']} Hrs</td>
                                          <td>{$currencySymbol}{$totals['current']}</td>
                                       </tr>
                                       <tr>
                                          <th class="total-subheading">Archived</th>
                                          <td>{$totals['archivedHours']} Hrs</td>
                                          <td>{$currencySymbol}{$totals['archived']}</td>
                                       </tr>
                                       <tr>
                                          <th class="total-subheading">Written Off</th>
                                          <td>{$totals['writtenOffHours']} Hrs</td>
                                          <td>{$currencySymbol}{$totals['writtenOff']}</td>
                                       </tr>
                                       <tr class="dark">
                                          <th>Report Total</th>
                                          <td>{$totals['grandHours']} Hrs</td>
                                          <td>{$currencySymbol}{$totals['grand']}</td>
                                       </tr>
                                    </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div id="job-total-section-{$jobId}">
                                    <button class="btn btn-primary btn-all-job-totals mb-sm" data-job-id="{$jobId}">Show all job totals</button>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="panel-footer collapse" id="job-invoices-{$jobId}">
                           <div id="job-invoice-section-{$jobId}">{$invoiceSummaryHTML}</div>
                        </div>
                     </section>

HTML;
}


/** ----------------------------------------------------------------------------
 * Creates a HTML table row. Displays the data of a single entry of the report
 *
 * @param   array   $reportEntry    Array with entry details
 * @param   double  $total          The total currency amount for the entry
 * @return  string                  Table row HTML for the report
 */
function _create_entry_row( $reportEntry, $total, $displayMockInvoice ) {

   // Variables to be used in heredoc syntax
   $date  = format_db_date( $reportEntry['startDateTime'] );
   $task  = format_task_name( $reportEntry['taskGroup'], $reportEntry['taskName'] );
   $total = lang( 'system_currency_symbol' ) . $total;
   $timeTaken = trim_trailing_zeros( $reportEntry['timeTaken'] );

   $jobId = _get_job_id( $reportEntry );

   // Handling of entry status
   $rowClass        = '';
   $archiveState    = '';
   $writeOffState   = '';
   $invoiceState    = '';
   $invoiceRow      = '';
   if ( $reportEntry['archived'] ) {
      $rowClass      = ' archived-row';
      $archiveState  = ' checked';
      $writeOffState = ' disabled';
      $invoiceState  = ' disabled';
   }
   if ( $reportEntry['writtenOff'] ) {
      $rowClass      = ' written-off-row';
      $archiveState  = ' disabled';
      $writeOffState = ' checked';
      $invoiceState  = ' disabled';
   }

   // Our columns change slightly if displayed on a mock invoice report
   if ( $displayMockInvoice ) {
      // Columns for mock invoice report
      $columns = <<< HTML
                                             <td>
                                                <input type="checkbox" name="write-off-{$reportEntry['entryId']}" id="write-off-{$reportEntry['entryId']}" class="write-off-checkbox" data-entry-id="{$reportEntry['entryId']}"{$writeOffState}>
                                             </td>
                                             <td>
                                                <a href="#" class="hidden-ready" data-entry-id="{$reportEntry['entryId']}" data-toggle="delete-invoice-entry">
                                                   <i class="fa fa-trash"></i>
                                                </a>
                                             </td>
HTML;
   } else { // WIP Report

      // Check if entry is attached to a mock invoice and display the link if it is
      // If we show a link, we hide the checkbox (We hide it so later if we remove the link and show the checkbox, the change event will still fire)
      // Checkbox shown by default, no link to an invoice
      $invoiceCheckboxHidden = '';
      $invoiceLink = '';
      if ( $reportEntry['mockInvoiceId'] != null ) {
         // It's attached to an invoice so display a link
         $invoiceLink = '<a href="' . base_url() . 'reports/mock-invoice?id=' . $reportEntry['mockInvoiceId'] . '&wip=1" target="_blank">' . $reportEntry['mockInvoiceId'] . '</a>';
         $invoiceCheckboxHidden = ' hidden';
         // Disable both the archive and write off checkboxes. Leave them checked if they are archived or written off
         ( $archiveState == ' checked' ? $archiveState = ' checked disabled' : $archiveState = ' disabled' );
         ( $writeOffState == ' checked' ? $writeOffState = ' checked disabled' : $writeOffState = ' disabled' );
      }

      // HTML for the invoice checkbox and link if we have one
      $invoiceRow = '<input type="checkbox" name="invoicedId[]" id="invoice-checkbox-' . $reportEntry['entryId'] .'" value="' . $reportEntry['entryId'] . '" class="invoice-checkbox' . $invoiceCheckboxHidden . '" data-entry-id="' . $reportEntry['entryId'] . '"' . $invoiceState . '><span id="invoice-link-' + $reportEntry['entryId'] . '">' . $invoiceLink . '</span>';

      // Columns for WIP Report
      $columns = <<< HTML
                                             <td>
                                                <input type="checkbox" name="archive-{$reportEntry['entryId']}" id="archive-{$reportEntry['entryId']}" class="archive-checkbox" data-entry-id="{$reportEntry['entryId']}"{$archiveState}>
                                             </td>
                                             <td>
                                                <input type="checkbox" name="write-off-{$reportEntry['entryId']}" id="write-off-{$reportEntry['entryId']}" class="write-off-checkbox" data-entry-id="{$reportEntry['entryId']}"{$writeOffState}>
                                             </td>
                                             <td id ="invoice-cell-{$reportEntry['entryId']}">
                                                <input type="checkbox" name="invoicedId[]" id="invoice-checkbox-{$reportEntry['entryId']}" value="{$reportEntry['entryId']}" class="invoice-checkbox checkbox-job-{$jobId}{$invoiceCheckboxHidden}" data-entry-id="{$reportEntry['entryId']}"{$invoiceState}><span id="invoice-link-{$reportEntry['entryId']}">{$invoiceLink}</span>
                                             </td>
HTML;
   }


   return <<< HTML
                                          <tr id="entry-row-{$reportEntry['entryId']}" class="entry-row entry-row-job-{$jobId}{$rowClass}">
                                             <td>{$date}</td>
                                             <td>{$reportEntry['name']}</td>
                                             <td class="task-name">{$task}</td>
                                             <td>{$reportEntry['comment']}</td>
                                             <td>{$timeTaken}</td>
                                             <td>{$total}</td>
{$columns}
                                          </tr>

HTML;

}


/** ----------------------------------------------------------------------------
 * Creates a HTML table row with a subtotal of the report
 *
 * @param   string   $subtotalTitle       Name of the field the total is for
 * @param   double   $sortTotal           The total amount
 * @param   double   $sortTotalHours      The total time (in hours)
 * @return  string                        Table row HTML for the report
 */
function _create_subtotal_row( $subtotalTitle, $sortTotal, $sortTotalHours ) {

   // Add a dollar sign to the currency total
   $sortTotal = lang( 'system_currency_symbol' ) . $sortTotal;

   return <<< HTML
                                          <tr class="split-row-line subtotal-row">
                                             <td colspan="4" class="text-right">{$subtotalTitle}</td>
                                             <td>{$sortTotalHours}</td>
                                             <td>{$sortTotal}</td>
                                          </tr>

HTML;

}


/** ----------------------------------------------------------------------------
 * Creates a HTML table row with the total for a single job from the report
 *
 * @param   double   $reportTotal            The total amount
 * @param   double   $reportTotalHours       The total time (in hours)
 * @return  string                           Table row HTML for the report
 */
function _create_report_total_row( $reportTotal, $reportTotalHours ) {

   // Add a dollar sign to the currency total
   $reportTotal = lang( 'system_currency_symbol' ) . $reportTotal;

   return <<< HTML
                                          <tr class="report-total-row">
                                             <td colspan="4" class="text-right"><b>Report Total</b></td>
                                             <td>{$reportTotalHours}</td>
                                             <td>{$reportTotal}</td>
                                          </tr>

HTML;

}


/** ----------------------------------------------------------------------------
 * Finds the database field that we need to have subtotals for in the report.
 *
 * @param   string   $sortOrder    The sort order used by the report
 * @return  string                 Primary sort field
 */
function _get_primary_sort_field( $sortOrder ) {

   $primarySortField = '';
   // Get the first field that the report is sorted on
   $leftSortField = explode( ", ", $sortOrder, 2 )[0];

   // Get the field to create subtotals for in the report
   switch ( $leftSortField ) {
      case 'entries.startDateTime':
         $primarySortField = 'date';
         break;
      case 'entries.taskGroup':
         $primarySortField = 'task';
         break;
      case 'entries.name':
         $primarySortField = 'name';
         break;
   }

   return $primarySortField;
}


/** ----------------------------------------------------------------------------
 * Checks for invoices attached to a particular job and builds a list of any current invoices
 *
 * Current invoices can have entries added to them, so a dropdown list is built to allow this
 *
 * @param   string   $sortOrder    The sort order used by the report
 * @return  string                 Primary sort field
 */
function _check_job_invoices( &$hasInvoices, &$invoiceSummaryHTML, $jobId ) {

   // A hidden field is used if there are no current invoices
   // This will trigger creating a new mock invoice if items are added from the job
   $invoiceHTML = '<input type="hidden" name="invoice-chooser-' . $jobId . '" id="invoice-chooser-' . $jobId . '" value="" />';
   // Stores any current invoices as select options
   $invoiceOptions = '';

   // Get a reference to the controller object to access the mock invoice
   $CI =& get_instance();
   // Get any invoice details
   $invoiceResults = $CI->mockinvoice_model->get_invoices( $jobId );

   if ( !empty( $invoiceResults ) ) {
      $hasInvoices = true;

      // Loop through and look for any current invoices. Add them as options
      foreach ( $invoiceResults as $row ) {
         if ( $row['readyToInvoice'] == 0 ) {
            $invoiceOptions .= '<option value="' . $row['mockInvoiceId'] . '">' . $row['mockInvoiceId'] . '</option>';
         }
      }
   }

   $CI->load->helper( 'report_minv' );
   $invoiceSummaryHTML = create_invoice_html( $invoiceResults );

   // There are current options so display a select dropdown so the suer can add to them
   if ( $invoiceOptions != '' ) {
      $invoiceHTML = <<< HTML
                                 <select name="invoice-chooser-{$jobId}" id="invoice-chooser-{$jobId}" class="invoice-select va-middle">
                                    <option value="">New invoice</option>
                                    {$invoiceOptions}
                                 </select>
HTML;
   }
   return $invoiceHTML;
}


/** ----------------------------------------------------------------------------
 * Updates totals as needed for a single entry
 *
 * @param   array       $totals                 Array with totals (Both currency and time)
 * @param   double      $reportEntry            The timesheet entry details from db
 * @param   boolean     $calculateJobTotals     If we are calculating the overall job totals (in control panel)
 * @param   string      $startDate              Start date of the report period in yyyy-mm-dd format
 * @param   string      $endDate                End date of the report period in yyyy-mm-dd format
 * @return  void
 */
function update_totals( &$totals, $reportEntry, $calculateJobTotals = false, $startDate = '', $endDate = '', $displayMockInvoice = false  ) {


   // Breakdown totals by entry state
   if ( $reportEntry['writtenOff'] ) {
      $totals['writtenOff'] += $totals['entry'];
      $totals['writtenOffHours'] += $reportEntry['timeTaken'];
   } elseif ( $reportEntry['archived'] ) {
      $totals['archived'] += $totals['entry'];
      $totals['archivedHours'] += $reportEntry['timeTaken'];
   } else {
      $totals['current'] += $totals['entry'];
      $totals['currentHours'] += $reportEntry['timeTaken'];
   }

   // Grand totals for the entire job
   $totals['grand'] += $totals['entry'];
   $totals['grandHours'] += $reportEntry['timeTaken'];


   // Are we totalling for the entire job in the control panel?
   if ( $calculateJobTotals ) {

      // Add to report totals only if they are within the report period.
      if ( ( ( $startDate == '' ) or ( strtotime( $startDate ) <= strtotime( $reportEntry['startDateTime'] ) ) ) and ( ( $endDate == '' ) or ( strtotime( $endDate . ' +1 day' ) >= strtotime( $reportEntry['endDateTime'] ) ) ) ) {

         // Breakdown totals by entry state in the report period
         if ( $reportEntry['archived'] ) {
            $totals['archivedReportPeriod'] += $totals['entry'];
            $totals['archivedHoursReportPeriod'] += $reportEntry['timeTaken'];
         } elseif ( $reportEntry['writtenOff'] ) {
            $totals['writtenOffReportPeriod'] += $totals['entry'];
            $totals['writtenOffHoursReportPeriod'] += $reportEntry['timeTaken'];
         } else {
            $totals['currentReportPeriod'] += $totals['entry'];
            $totals['currentHoursReportPeriod'] += $reportEntry['timeTaken'];
         }

         // Get the totals for the report in this period
         $totals['report']          += $totals['entry'];
         $totals['reportHours']     += $reportEntry['timeTaken'];
      }

   } else {  // Calculate totals for the WIP report.

      // If totalling for the mock invoice report, we exclude written off entries from the totals
      if ( $displayMockInvoice ) {
         if ( !$reportEntry['writtenOff'] ) {
            $totals['sortOption']      += $totals['entry'];
            $totals['sortOptionHours'] += $reportEntry['timeTaken'];
            $totals['report']          += $totals['entry'];
            $totals['reportHours']     += $reportEntry['timeTaken'];
         }

      // All entries are included in the report and we also total based on the sort option
      } else {
         $totals['sortOption']      += $totals['entry'];
         $totals['sortOptionHours'] += $reportEntry['timeTaken'];
         $totals['report']          += $totals['entry'];
         $totals['reportHours']     += $reportEntry['timeTaken'];
      }
   }

}


/** ----------------------------------------------------------------------------
 * Resets all totals for a single job
 *
 * @param   array    $totals         All parameters are passed by reference to be reset to zero
 * @return  void
 */
function _reset_totals( &$totals, $totalKeys ) {

   $totals = array_fill_keys( $totalKeys, 0 );
}


/** ----------------------------------------------------------------------------
 * Returns the correct id for the job
 *
 * @param   array    $entry         A single result with the job details
 * @return  int
 */
function _get_job_id( $entry ) {

   // Return the job id. This is actually the child id if it is a child job
   return ( $entry['childId'] == null ? $entry['jobId'] : $entry['childId'] );
}


/** ----------------------------------------------------------------------------
 * Creates a form with a button to print a WIP report
 *
 * @param   string   $buttonText             Button text
 * @param   string   $startDate              Start date (screen format dd/mm/yyyy)
 * @param   string   $endDate                Start date (screen format dd/mm/yyyy)
 * @param   string   $sortOrder              Sort order used in SQL query
 * @param   array    $filter                 Filters selected - current- archived, written off
 * @param   array    $jobSelect              Any jobs selected
 * @param   array    $userSelect             Any users seletced
 * @return  string
 */
function create_wip_print_button( $buttonText, $startDate, $endDate, $sortOrder, $filter, $jobSelect, $userSelect ) {

   // These may be arrays, so we need to handle properly
   $filterFields = '';
   $jobFields    = '';
   $userFields   = '';

   // Cycle through the data that might be in arrays
   // By putting multiple fields, they are passed via POST as an array
   if ( $filter != '' ) {
      foreach ( $filter as $row ) {
         $filterFields .= '<input type="hidden" name="filter[]" value="' . $row . '" />';
      }
   }
   if ( $jobSelect != '' ) {
      foreach ( $jobSelect as $row ) {
         $jobFields .= '<input type="hidden" name="jobSelect[]" value="' . $row . '" />';
      }
   }
   if ( $userSelect != '' ) {
      foreach ( $userSelect as $row ) {
         $userFields .= '<input type="hidden" name="userSelect[]" value="' . $row . '" />';
      }
   }

   // Create a form with submit button so we can pass through the report parameters
   $buttonForm = '<form name="print-report" method="POST" action="' . ROOT_RELATIVE_PATH . 'reports/printing/wip" target="_blank" class="ib">' .
         '<input type="hidden" name="startDate" value="' . $startDate . '" />' .
         '<input type="hidden" name="endDate" value="' . $endDate . '" />' .
         '<input type="hidden" name="sortOrder" value="' . $sortOrder . '" />' .
         $filterFields .
         $jobFields .
         $userFields .
         '<button type="submit" class="btn btn-primary mt-xs">' . $buttonText . '</button>' .
      '</form>';

   return $buttonForm;
}
?>