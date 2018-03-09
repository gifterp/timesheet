<?php
/**
 * Work in Progress Report Controller
 *
 * Displays the work in progress report which is an interactive report to manage the timesheet
 * entries and how they should be invoiced
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Wip extends Admin_controller {


   public function __construct() {
      parent::__construct();
      $this->load->model( 'job_model' );
      $this->load->model( 'user_model' );
      $this->load->model( 'timesheet/timesheet_entry_model' );
      $this->load->model( 'reports/mockinvoice_model' );
      $this->load->model( 'reports/mockinvoice_row_model' );
      $this->lang->load( 'report', 'english' );
      $this->lang->load( 'system', 'english' );
      $this->load->helper( 'isoft' );
      $this->load->helper( 'itime' );
      $this->load->helper( 'report_wip' );
      $this->load->library( 'json_library' );
   }


   /** ----------------------------------------------------------------------------
    * Work in progress report
    *
    * @param   array $_POST      Report parameters are sent via POST
    */
   public function index() {

      $this->data['meta_title']         = lang( 'report_wip_name' );
      $this->data['page_title']         = lang( 'report_wip_section_title' );
      $this->data['breadcrumb_items']   = array( lang( 'system_menu_reports_wip' ) );

      $this->data['pageVendorCss']      = $this->load->view( 'reports/wip/_vendor-css', '', true );
      $this->data['pageCustomCss']      = $this->load->view( 'reports/wip/_custom-css', '', true );
      $this->data['pageVendorJS']       = $this->load->view( 'reports/wip/_vendor-js', '', true );
      $this->data['pageCustomJS']       = $this->load->view( 'reports/wip/_custom-js', '', true );

      // List of current jobs/child jobs to be used in select dropdown. Data converted for use by the CI dropdown form helper function
      $this->data['jobArray'] = create_job_key_value_array( $this->job_model->get_list() );

      // List of active users to be used in select dropdown. Data converted for use by the CI dropdown form helper function
      $this->data['userArray'] = create_key_value_array( $this->user_model->get_active_list(), 'userId', 'name' );

      // The entries to be shown in the WIP report
      $this->data['reportEntries'] = $this->timesheet_entry_model->get_wip_entries( create_int_date_format( $this->input->post('startDate') ), create_int_date_format( $this->input->post('endDate') ), $this->input->post('jobSelect'), $this->input->post('userSelect'), $this->input->post('filter'), $this->input->post('sortOrder') );

      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'reports/wip/index', $this->data );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );
   }


   /** ----------------------------------------------------------------------------
    * Updates a timesheet entry with details passed via POST. Requires entryId to be passed with data
    *
    * @param   array $_POST      Details to be updated are sent via POST
    */
   public function update_by_entry_id() {

      if ( isset( $_POST[ 'entryId' ] ) ) {
         $entryId = $this->input->post( 'entryId' );
         unset( $_POST['entryId'] );

         $updatedId = $this->timesheet_entry_model->save( $_POST, $entryId );
      }
      // Error handling - return some JSON if update successful
      if ( $updatedId ) {
         // Send back the POST details as JSON
         $this->json_library->print_array_json_unless_empty( $_POST );
      }
   }


   /** ----------------------------------------------------------------------------
    * Outputs a table holding the totals for an entire job.
    *
    * It has columns for totals within the reporting period and for all time.
    *
    * @param   array $_POST      Details to be updated are sent via POST
    */
   public function create_job_total_html() {

      // Language variables for heredoc syntax
      $currencySymbol = lang( 'system_currency_symbol' );

      // Totals for the report - Currency and time in hours are totalled
      $totalKeys = array( 'entry', 'sortOption', 'sortOptionHours', 'report', 'reportHours', 'current', 'currentHours', 'archived', 'archivedHours', 'writtenOff', 'writtenOffHours', 'currentReportPeriod', 'currentHoursReportPeriod', 'archivedReportPeriod', 'archivedHoursReportPeriod', 'writtenOffReportPeriod', 'writtenOffHoursReportPeriod', 'grand', 'grandHours' );
      $totals = array_fill_keys( $totalKeys, 0 );

      // Get the relevant timesheet entries for this job from the database
      $reportEntries = $this->timesheet_entry_model->get_wip_entries( '', '', array( $this->input->post('jobId') ), '', '', 'entries.startDateTime' );

      // Loop through the results and calculate the totals
      $totalEntries = (int)count( $reportEntries );
      for ( $i = 0; $i < $totalEntries; $i++ ) {
         // Calculate the total for each timesheet entry then update our overall totals
         $totals['entry'] = calculate_entry_total( $reportEntries[$i]['timeTaken'], $reportEntries[$i]['chargeRate'], $reportEntries[$i]['chargeable'], $reportEntries[$i]['disbursement'] );
         update_totals( $totals, $reportEntries[$i], true, $this->input->post('startDate'), $this->input->post('endDate') );
      }

      // Return the html
      echo <<< HTML
                                    <div class="table-responsive">
                                       <table class="table table-striped table-bordered table-hover">
                                       <thead>
                                          <tr class="report-heading-row">
                                             <th>Overall totals</th>
                                             <th colspan="2">Report period</th>
                                             <th colspan="2">The entire job</th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <tr>
                                             <th class="total-subheading">Current</th>
                                             <td>{$totals['currentHoursReportPeriod']} Hrs</td>
                                             <td>{$currencySymbol}{$totals['currentReportPeriod']}</td>
                                             <td>{$totals['currentHours']} Hrs</td>
                                             <td>{$currencySymbol}{$totals['current']}</td>
                                          </tr>
                                          <tr>
                                             <th class="total-subheading">Archived</th>
                                             <td>{$totals['archivedHoursReportPeriod']} Hrs</td>
                                             <td>{$currencySymbol}{$totals['archivedReportPeriod']}</td>
                                             <td>{$totals['archivedHours']} Hrs</td>
                                             <td>{$currencySymbol}{$totals['archived']}</td>
                                          </tr>
                                          <tr>
                                             <th class="total-subheading">Written Off</th>
                                             <td>{$totals['writtenOffHoursReportPeriod']} Hrs</td>
                                             <td>{$currencySymbol}{$totals['writtenOffReportPeriod']}</td>
                                             <td>{$totals['writtenOffHours']} Hrs</td>
                                             <td>{$currencySymbol}{$totals['writtenOff']}</td>
                                          </tr>
                                          <tr class="dark">
                                             <th>Grand Total</th>
                                             <td>{$totals['reportHours']} Hrs</td>
                                             <td>{$currencySymbol}{$totals['report']}</td>
                                             <td>{$totals['grandHours']} Hrs</td>
                                             <td>{$currencySymbol}{$totals['grand']}</td>
                                          </tr>
                                       </tbody>
                                       </table>
                                    </div>

HTML;
   } 

}