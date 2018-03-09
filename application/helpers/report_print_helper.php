<?php
/**
 * Print Report Helper
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
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
 * @return  void                          Prints the HTML to display the report
 */
function print_wip_report( $reportEntries, $sortOrder, $filter, $businessName ) {

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
   // Totals for the report - Currency and time in hours are totalled
   $totalKeys = array( 'entry', 'sortOption', 'sortOptionHours', 'report', 'reportHours', 'current', 'currentHours', 'archived', 'archivedHours', 'writtenOff', 'writtenOffHours', 'currentReportPeriod', 'currentHoursReportPeriod', 'archivedReportPeriod', 'archivedHoursReportPeriod', 'writtenOffReportPeriod', 'writtenOffHoursReportPeriod', 'grand', 'grandHours' );
   $totals = array_fill_keys( $totalKeys, 0 );

   // Print the report panel header
   _print_header( $businessName );

   for ( $i = 0; $i < $totalEntries; $i++ ) {
      // Set the job reference and the field we will subtotal on so we can compare them
      $jobRef = format_job_ref( $reportEntries[$i]['jobRef'], $reportEntries[$i]['childRef'] );
      $subtotalName = _create_subtotal_name( $sortField, $reportEntries[$i] );

      // Calculate the total for the timesheet entry
      $totals['entry'] = calculate_entry_total( $reportEntries[$i]['timeTaken'], $reportEntries[$i]['chargeRate'], $reportEntries[$i]['chargeable'], $reportEntries[$i]['disbursement'] );

      // Add a single entry data to a table row. Then update our totals accordingly
      $tableRows .= _create_entry_row( $reportEntries[$i], $totals['entry'] );
      update_totals( $totals, $reportEntries[$i] );


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
         _print_job_panel( $reportEntries[$i], $tableRows, $jobId, $businessName );

        

         // Clear all totals at the end of a job
         _reset_totals( $totals, $totalKeys );

         // Reset our table row data and invoice flag for next job
         $tableRows   = '';
         $hasInvoices = false;
      }
   }
      // Print the report panel footer
      _print_footer();

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


function _print_header( $businessName ) {

   // Set Header data
   $reportTitle         = lang( 'report_wip_name' );
   $dateRange           = @$_POST['startDate'] . ' to ' .  @$_POST['endDate'];
   $sortOrder           = ucwords( str_replace( array( 'entries.', 'start', 'taskName', 'Group', 'Time' ), '', $_POST['sortOrder'] ) );
   $filter              = 'All Selected';

   echo <<<HTML
            <div class="invoice" >
               <header class="clearfix">
                  <div class="row">
                     <div class="col-sm-6 mt-md">
                        <h4 class="h4 m-none p-none text-dark header-font"><strong>$businessName</strong></h4>
                        <h4 class="h4 m-none p-none text-dark header-font" ><strong>$reportTitle</strong></h4>
                     </div>
                     <div class="col-sm-6 text-right header-margin header-font">
                        <strong>Date Range:</strong> $dateRange <br/>
                        <strong>Sort Order:</strong> $sortOrder<br/>
                        <strong>Filter:</strong> $filter
                     </div>
                  </div>
               </header>
            <div class="table-responsive">
HTML;

}

function _print_footer( ) { 
   echo <<<HTML
         </div>
      </div>
HTML;
}


/** ----------------------------------------------------------------------------
 * Outputs a panel with report information for a single job in the report
 *
 * @param   array    $reportEntry    Array with entry details
 * @return  void
 */
function _print_job_panel( $reportEntry, $tableRows, $jobId, $businessName ) {

   // Place CI language variables into php variables to use in heredoc syntax
   $archivedTooltip     = lang( 'report_wip_ttip_archived_col' );
   $writtenOffTooltip   = lang( 'report_wip_ttip_written_off_col' );
   $invoiceTooltip      = lang( 'report_wip_ttip_invoice_col' );

   // Create the title for the panel
   $panelSubTitle = '';  // We only have a subtitle if it is a child job. The subtitle will list the parent job
   if ( $reportEntry['childRef'] != null ) { $panelSubTitle = '<p class="panel-subtitle">Sub file of ' . format_job_ref_name( $reportEntry['jobRef'], $reportEntry['jobName'] ) . '</p>'; }

   $tableJobTitle = '<strong>' . format_job_ref_name( $reportEntry['jobRef'], $reportEntry['jobName'], $reportEntry['childRef'], $reportEntry['childName'] ) . '</strong>' . $panelSubTitle;

   echo <<<HTML
               <table class="report-tbl invoice-items">
                  <thead>
                     <tr class="h4 text-dark">
                        <td colspan="9" class="tbl-job-header tbl-padding">
                           $tableJobTitle 
                        </td>
                     </tr>
                     <tr class="h4 text-dark content-font">
                        <th class="text-weight-semibold tbl-padding tbl-border tbl-date">Date </th>
                        <th class="text-weight-semibold tbl-padding tbl-border tbl-staff">Staff</th>
                        <th class="text-weight-semibold tbl-padding tbl-border tbl-task">Task</th>
                        <th class="text-weight-semibold tbl-padding tbl-border tbl-desc">Description</th>
                        <th class="text-weight-semibold tbl-padding tbl-border tbl-hr">Hrs</th>
                        <th class="text-weight-semibold tbl-padding tbl-border tbl-total">Total</th>
                        <th class="text-weight-semibold tbl-padding tbl-border tbl-arc">Arc</th>
                        <th class="text-weight-semibold tbl-padding tbl-border tbl-wo">W/O</th>
                        <th class="text-weight-semibold tbl-padding tbl-border tbl-inv">Inv</th>
                     </tr>
                  </thead>
                  <tbody>
{$tableRows}                
                  </tbody>
               </table>
           
HTML;

}




/** ----------------------------------------------------------------------------
 * Creates a HTML table row. Displays the data of a single entry of the report
 *
 * @param   array   $reportEntry    Array with entry details
 * @param   double  $total          The total currency amount for the entry
 * @return  string                  Table row HTML for the report
 */
function _create_entry_row( $reportEntry, $total ) {

   // Variables to be used in heredoc syntax
   $date  = format_db_date( $reportEntry['startDateTime'] );
   $task  = format_task_name( $reportEntry['taskGroup'], $reportEntry['taskName'] );
   $total = '$' . $total;
   $timeTaken = trim_trailing_zeros( $reportEntry['timeTaken'] );

   // Set checkbox images
   $checked    = '<img src="' . ROOT_RELATIVE_PATH . 'assets-system/img/reports/check.png" height="11" width="11">&nbsp;';
   $unchecked  = '<img src="' . ROOT_RELATIVE_PATH . 'assets-system/img/reports/unchecked.png" height="10" width="10">&nbsp;';

   // Handling of entry status
   $rowClass        = '';
   $archiveState    = $unchecked;
   $writeOffState   = $unchecked;
   $invoiceState    = $unchecked;
   if ( $reportEntry['archived'] ) {
      //$rowClass      =  'archived-row';
      $archiveState  =  $checked;
   } else if ( $reportEntry['writtenOff'] ) {
      //$rowClass      = 'written-off-row';
      $writeOffState = $checked;
   }

   // Check if entry is attached to a mock invoice and display the link if it is
   // If we show a link, we hide the checkbox (We hide it so later if we remove the link and show the checkbox, the change event will still fire)
   // Checkbox shown by default, no link to an invoice
   $invoiceVal = $unchecked;
   if ( $reportEntry['mockInvoiceId'] != null ) {
      // It's attached to an invoice so display a link
      $invoiceVal = $reportEntry['mockInvoiceId'];
      // Disable both the archive and write off checkboxes. Leave archived checked if it is archived (Not possible to be written off and attached to an invoice)
      ( $archiveState == ' checked' ? $archiveState = $checked : $archiveState = $unchecked );
   }




   return <<< HTML
                                          <tr class="h4 text-dark content-font {$rowClass}">
                                             <th class="text-weight-semibold tbl-padding tbl-border tbl-date">{$date}</th>
                                             <th class="text-weight-semibold tbl-padding tbl-border tbl-staff"> {$reportEntry['initials']} </th>
                                             <th class="text-weight-semibold tbl-padding tbl-border tbl-task"> {$task} </th>
                                             <th class="text-weight-semibold tbl-padding tbl-border tbl-desc">{$reportEntry['comment']}</th>
                                             <th class="text-weight-semibold tbl-padding tbl-border tbl-hr">{$timeTaken}</th>
                                             <th class="text-weight-semibold tbl-padding tbl-border tbl-total">{$total}</th>
                                             <th class="text-weight-semibold tbl-padding tbl-border tbl-arc">{$archiveState}</th>
                                             <th class="text-weight-semibold tbl-padding tbl-border tbl-wo">{$writeOffState}</th>
                                             <th class="text-weight-semibold tbl-padding tbl-border tbl-inv">{$invoiceVal}</th>
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
   $sortTotal = '$' . $sortTotal;

   return <<< HTML
                                          <tr class="content-font row-sub-total">
                                             <td colspan="4" class="text-right tbl-padding tbl-padding-right">{$subtotalTitle}</td>
                                             <td class="tbl-padding">{$sortTotalHours}</td>
                                             <td class="tbl-padding">{$sortTotal}</td>
                                             <td class="tbl-padding"></td>
                                             <td class="tbl-padding"></td>
                                             <td class="tbl-padding"></td>
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
   $reportTotal = '$' . $reportTotal;

   return <<< HTML
                                       <tr class="content-font row-final-total">
                                          <td colspan="4" class="text-right tbl-padding tbl-border tbl-padding-right">Report Total</td>
                                          <td class="tbl-padding tbl-border">{$reportTotalHours}</td>
                                          <td class="tbl-padding tbl-border">{$reportTotal}</td>
                                          <td class="tbl-padding tbl-border"></td>
                                          <td class="tbl-padding tbl-border"></td>
                                          <td class="tbl-padding tbl-border"></td>
                                       </tr>

HTML;

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
function update_totals( &$totals, $reportEntry, $calculateJobTotals = false, $startDate = '', $endDate = ''  ) {


   // Breakdown totals by entry state
   if ( $reportEntry['archived'] ) {
      $totals['archived'] += $totals['entry'];
      $totals['archivedHours'] += $reportEntry['timeTaken'];
   } elseif ( $reportEntry['writtenOff'] ) {
      $totals['writtenOff'] += $totals['entry'];
      $totals['writtenOffHours'] += $reportEntry['timeTaken'];
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

      // All entries are included in the report and we also total based on the sort option
      $totals['sortOption']      += $totals['entry'];
      $totals['sortOptionHours'] += $reportEntry['timeTaken'];
      $totals['report']          += $totals['entry'];
      $totals['reportHours']     += $reportEntry['timeTaken'];
   }

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
?>