<?php
/**
 * Mock Invoice Report Controller
 *
 * Shows items allocated to an invoice and allows adjustments to be made in preparation
 * for the items to be added to an invoice
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Mock_invoice extends Admin_controller {


   public function __construct() {
      parent::__construct();
      $this->load->model( 'job_model' );
      $this->load->model( 'user_model' );
      $this->load->model( 'timesheet/timesheet_entry_model' );
      $this->load->model( 'reports/mockinvoice_model' );
      $this->load->model( 'reports/mockinvoice_row_model' );
      $this->load->model( 'admin/mockinvoice_category_model' );
      $this->load->model( 'admin/mockinvoice_description_model' );
      $this->lang->load( 'report', 'english' );
      $this->lang->load( 'system', 'english' );
      $this->load->helper( 'isoft' );
      $this->load->helper( 'itime' );
      $this->load->helper( 'report_minv' );
      $this->load->helper( 'report_wip' );
      $this->load->library( 'json_library' );
   }


   /** ----------------------------------------------------------------------------
    * Work in progress report
    *
    * @param   array $_POST      Report parameters are sent via POST
    */
   public function index() {

      $this->data['meta_title']         = lang( 'report_minv_name' );
      $this->data['page_title']         = lang( 'report_minv_section_title' );
      $this->data['breadcrumb_items']   = array( lang( 'report_minv_section_title' ) );

      // The mock invoice details
      $this->data['invoiceDetails'] = $this->mockinvoice_model->get( $this->input->get( 'id' ) );

      $this->data['pageVendorCss']      = $this->load->view( 'reports/minv/_vendor-css', '', true );
      $this->data['pageCustomCss']      = $this->load->view( 'reports/minv/_custom-css', $this->data, true );
      $this->data['pageVendorJS']       = $this->load->view( 'reports/minv/_vendor-js', '', true );
      $this->data['pageCustomJS']       = $this->load->view( 'reports/minv/_custom-js', '', true );

      // Close the window if no invoice exists
      if ( empty( $this->data['invoiceDetails'] ) ) {
         echo '<script> alert( "Mock invoice #' . $this->input->get( 'id' ) . ' does not exist\n\nThis window will be closed" ); window.close(); </script>';
         die();
      }

      // List of descriptions to be used in drag/drop list.
      $this->data['descriptionArray'] = $this->mockinvoice_description_model->get( null, null, 'result_array' );

      // The entries to be shown in the Mock Invoice report
      $this->data['reportEntries'] = $this->mockinvoice_model->get_mock_invoice_entries( $this->data['invoiceDetails']->sortOrder, '', $this->input->get( 'id' ) );


      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'reports/minv/index', $this->data );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );
   }


   /** ----------------------------------------------------------------------------
    * Adds timesheet entries to a mock invoice
    *
    * Either creates a new mock invoice or adds items to an existing invoice
    *
    * @param   array $_POST      Details to be updated are sent via POST
    */
   public function add_to_invoice() {
      $mockInvoiceId = $this->input->post( 'mockInvoiceId' );
      // Array storing entry id's to do a bulk update with the mock invoice details
      $entryArray = [];


      // If there is no invoice id then a new invoice needs to be created.
      if ( $mockInvoiceId == '' ) {
         // Store the jobId as an array to pass to the save function
         $data = array(
            'jobId' => $this->input->post( 'jobId' )
         );
         // Create the new invoice
         $mockInvoiceId = $this->mockinvoice_model->save( $data );

         // Store the mockInvoiceId as an array to pass to the save function
         $data = array(
            'mockInvoiceId' => $mockInvoiceId
         );
         // Create a blank invoice row ready for initial input
         $this->mockinvoice_row_model->save( $data );
      }

      // Update all the entries with the mock invoice details
      $this->timesheet_entry_model->set_mock_invoice_details( $mockInvoiceId, array( $this->input->post( 'entries' ) ) );


      // Return the data that was passed via $_POST, but with the invoice number if a new one was created
      $invoiceArray[] = array( 'mockInvoiceId' => $mockInvoiceId, 'entries' => $this->input->post( 'entries' ), 'jobId' => $this->input->post( 'jobId' ) );

      // Send back the POST details as JSON
      $this->json_library->print_array_json_unless_empty( $invoiceArray );

   }


   /** ----------------------------------------------------------------------------
   * Returns mock invoice details as JSON
   *
   * @param $_POST    The mock invoice id is passed via POST
   */
   public function get_settings( ) {

      $settingsArray = $this->mockinvoice_model->get_settings_array( $this->input->post( 'mockInvoiceId' ) );

      // Send back the timesheet settings as JSON.
      $this->json_library->print_array_json( $settingsArray );
   }


   /** ----------------------------------------------------------------------------
   * Returns mock invoices for a single job
   *
   * @param $_POST    The job id is passed via POST
   */
   public function get_invoices( ) {

      $invoiceArray = $this->mockinvoice_model->get_invoices( $this->input->post( 'jobId' ) );

      if ( !empty( $invoiceArray ) ) {
         $invoiceHTML = create_invoice_html( $invoiceArray );
         // Push the HTML for the invoice tables onto the array being returned
         $invoiceArray[] = array( 'mockInvoiceId' => '', 'readyToInvoice' => '', 'archived' => '', 'invoiceHTML' => $invoiceHTML );
      }

      // Send back the timesheet settings as JSON.
      $this->json_library->print_array_json( $invoiceArray );
   }


   /** ----------------------------------------------------------------------------
    * Updates a single mock invoice with details passed via POST. Requires mockInvoiceId to be passed with data
    *
    * @param   array $_POST      Details to be updated are sent via POST
    */
   public function update_by_mock_invoice_id() {

      if ( isset( $_POST[ 'mockInvoiceId' ] ) ) {
         $mockInvoiceId = $this->input->post( 'mockInvoiceId' );
         unset( $_POST['mockInvoiceId'] );

         // Set any empty values to null
         array_replace_empty_with_null( $_POST );

         $updatedId = $this->mockinvoice_model->save( $_POST, $mockInvoiceId );
      }
      // Error handling - return some JSON if update successful
      if ( $updatedId ) {
         // Send back the POST details as JSON
         $this->json_library->print_array_json_unless_empty( $_POST );
      }
   }


   /** ----------------------------------------------------------------------------
    * Updates a single mock invoice row with details passed via POST. Requires mockInvoiceRowId to be passed with data
    *
    * @param   array $_POST      Details to be updated are sent via POST
    */
   public function update_by_mock_invoice_row_id() {

      if ( isset( $_POST[ 'mockInvoiceRowId' ] ) ) {
         $mockInvoiceRowId = $this->input->post( 'mockInvoiceRowId' );
         unset( $_POST['mockInvoiceRowId'] );

         // Set any empty values to null
         array_replace_empty_with_null( $_POST );

         $updatedId = $this->mockinvoice_row_model->save( $_POST, $mockInvoiceRowId );
      }
      // Error handling - return some JSON if update successful
      if ( $updatedId ) {
         // Send back the POST details as JSON
         $this->json_library->print_array_json_unless_empty( $_POST );
      }
   }


   /** ----------------------------------------------------------------------------
    * Sets an invoice as complete or incomplete
    *
    * Also archives/restores entries along with the invoice
    *
    * @param   array $_POST      The mock invoice id and complete state are sent via POST
    * @return  json              Returns all the timesheet entry id's that were a part of the invoice
    */
   public function complete_invoice() {

      // Make sure we were sent a mock invoice id
      if ( isset( $_POST[ 'mockInvoiceId' ] ) ) {
         $mockInvoiceId = $this->input->post( 'mockInvoiceId' );
         unset( $_POST['mockInvoiceId'] );

         // Get all entry ids that were part of the invoice so we can pass them back
         $entryArray = $this->mockinvoice_model->get_entry_ids( $mockInvoiceId );
         // Archive/restore all entries from the invoice (unless written off)
         $this->mockinvoice_model->archive_all_entries( $mockInvoiceId, $_POST[ 'archived' ] );
         // Archive/restore the invoice (complete/not yet complete)
         $this->mockinvoice_model->save( $_POST, $mockInvoiceId );

      }
      // Error handling - return some JSON if delete was processed
      if ( !empty( $entryArray ) ) {
         // Send back the timesheet entry ids as JSON
         $this->json_library->print_array_json_unless_empty( $entryArray );
      }
   }


   /** ----------------------------------------------------------------------------
    * Deletes an invoice, all invoice rows and removes all entries from the invoice
    *
    * @param   array $_POST      The mock invoice id sent via POST
    * @return  json              Returns all the timesheet entry id's that were a part of the invoice
    */
   public function delete_invoice() {

      // Make sure we were sent a mock invoice id
      if ( isset( $_POST[ 'mockInvoiceId' ] ) ) {

         // Get all entry ids that were part of the invoice so we can pass them back
         $entryArray = $this->mockinvoice_model->get_entry_ids( $_POST[ 'mockInvoiceId' ] );
         // Remove all entries from the invoice
         $this->mockinvoice_model->remove_all_entries( $_POST[ 'mockInvoiceId' ] );
         // Delete all mock invoice rows
         $this->mockinvoice_row_model->delete_where( 'mockInvoiceId', $_POST[ 'mockInvoiceId' ] );
         // Delete the mock invoice
         $this->mockinvoice_model->delete( $_POST[ 'mockInvoiceId' ] );

      }
      // Error handling - return some JSON if delete was processed
      if ( !empty( $entryArray ) ) {
         // Send back the timesheet entry ids as JSON
         $this->json_library->print_array_json_unless_empty( $entryArray );
      }
   }


   /** ----------------------------------------------------------------------------
    * Removes a single entry from a mock invoice
    *
    * When removing an entry, we must delete the whole invoice if it is the only entry
    *
    * @param   array $_POST      The mock invoice id and timesheet entry id are sent via POST
    * @return  json              Returns boolean values to indicate if a row or the whole invoice was also deleted
    */
   public function delete_single_entry() {

      $invoiceDeleted = false;

      // Delete the entry first
      $this->mockinvoice_model->remove_single_entry( $_POST[ 'timesheetEntryId' ] );

      // Count the remaining entries from the invoice
      $count = $this->mockinvoice_model->count_invoice_entries( $_POST[ 'mockInvoiceId' ] );

      // Delete the entire invoice if it no longer has entries
      if ( $count[0]->invoiceEntryCount == 0 ) {
         // Delete all mock invoice rows
         $this->mockinvoice_row_model->delete_where( 'mockInvoiceId', $_POST[ 'mockInvoiceId' ] );
         // Delete the mock invoice
         $this->mockinvoice_model->delete( $_POST[ 'mockInvoiceId' ] );
         $invoiceDeleted = true;
      }

      // Return the id's and if other data was deleted aside from the entry itself
      $returnArray[] = array( 'invoiceDeleted' => $invoiceDeleted, 'mockInvoiceId' => $_POST[ 'mockInvoiceId' ] );
      // Send back JSON with boolean values to tell if the invoice or row was deleted
      $this->json_library->print_array_json_unless_empty( $returnArray );
   }


   /** ----------------------------------------------------------------------------
    * Deletes a row from the mock invoice
    *
    * Deletes an invoice row, except if there is only 1 left. If a single row remains we clear the data
    * and keep it. This row is then ready for input
    *
    * @param   array $_POST      The mock invoice id and the mock invoice row id are sent via POST
    * @return  json              Returns boolean values to indicate if a row or the whole invoice was also deleted
    */
   public function delete_invoice_row() {

      $deleteFlag = false;

      // Count the entries and rows from the invoice
      $count = $this->mockinvoice_model->count_invoice_entries( $_POST[ 'mockInvoiceId' ] );

      // Don't delete, but clear the data if it is the last one
      if ( $count[0]->invoiceRowCount == 1 ) {
         // Set null values
         $data = array(
            'category' => null,
            'description' => null,
            'amount' => null
         );
         // Clear the data of the mock invoice row
         $this->mockinvoice_row_model->save( $data, $_POST[ 'mockInvoiceRowId' ] );

      // Else, more than 1 row so we delete
      } else {
         // Delete the mock invoice row
         $this->mockinvoice_row_model->delete_where( 'mockInvoiceRowId', $_POST[ 'mockInvoiceRowId' ] );
         $deleteFlag = true;
      }


      // Send back how many rows there were and if 1 was deleted
      $returnArray[] = array( 'initialCount' => $count, 'rowDeleted' => $deleteFlag );
      $this->json_library->print_array_json_unless_empty( $returnArray );
   }


   /** ----------------------------------------------------------------------------
    * Adds a row to the mock invoice
    *
    * Checks if there are any blank rows first and only adds one if none are found
    *
    * @param   array $_POST      The mock invoice id is sent via POST
    * @return  json              Returns boolean values to indicate if a row or the whole invoice was also deleted
    */
   public function add_invoice_row() {

      $addFlag = false;

      // Set null values to check for and later insertion if required
      $data = array(
         'category' => null,
         'description' => null,
         'amount' => null,
         'mockInvoiceId' => $_POST[ 'mockInvoiceId' ]
      );

      // Check if there are any rows that have nothing in them
      $row = $this->mockinvoice_row_model->get_by( $data );

      // Add a new row if there are no blank ones already
      if ( empty( $row ) ) {
         // Add a new row with empty values
         $this->mockinvoice_row_model->save( $data );
         $addFlag = true;
      }


      // Send back if a new row was added
      $returnArray[] = array( 'rowAdded' => $addFlag );
      $this->json_library->print_array_json_unless_empty( $returnArray );
   }


   /** ----------------------------------------------------------------------------
    * Prints out the HTML of the form for the user to enter invoice details
    *
    * @param   array $_POST      The mock invoice id is sent via POST
    * @return  void              Prints the HTML for the mock invoice details form
    */
   public function print_invoice_details_form() {

      // Variables to be used in heredoc syntax
      $descriptionPlaceHolder = lang( 'report_minv_fm_description_ph' );
      $amountPlaceHolder      = lang( 'report_minv_fm_amount_ph' );
      // Get any invoice row details
      $invoiceRows = $this->mockinvoice_row_model->get_by( 'mockInvoiceId = ' . @$_POST['mockInvoiceId'] );
      // Get the invoice categories. Data converted for use by the CI dropdown form helper function
      $categoryArray = create_key_value_array( $this->mockinvoice_categories_model->get( null, null, 'result_array' ), 'category', 'category', true ); // True = Add blank option at beginning

      $rowHTML = '';

      foreach ( $invoiceRows as $row ) {

         $rowHTML .= <<< HTML
                                    <div class="form-group" id="invoice-form-row-$row->mockInvoiceRowId">
                                       <div class="col-md-3 mb-xs">

HTML;

         // Category dropdown list
         $rowHTML .=  form_dropdown( 'category-' .  $row->mockInvoiceRowId, $categoryArray, $row->category, 'class="form-control invoice-category" data-invoice-row-id="' .  $row->mockInvoiceRowId . '"' );

         $rowHTML .= <<< HTML
                                       </div>
                                       <div class="col-md-6 mb-xs">
                                          <textarea name="invoice-description-{$row->mockInvoiceRowId}" id="invoice-description-{$row->mockInvoiceRowId}" rows="1" placeholder="{$descriptionPlaceHolder}" class="invoice-description form-control" data-invoice-row-id="{$row->mockInvoiceRowId}">{$row->description}</textarea>
                                       </div>
                                       <div class="col-md-2 mb-xs">
                                          <div class="input-group btn-group">
                                             <span class="input-group-addon">
                                                $
                                             </span>
                                             <input type="text" name="invoice-amount-{$row->mockInvoiceRowId}" id="invoice-amount-{$row->mockInvoiceRowId}" value="{$row->amount}" placeholder="{$amountPlaceHolder}" class="form-control numeric invoice-amount" data-invoice-row-id="{$row->mockInvoiceRowId}" />
                                          </div>
                                       </div>
                                       <div class="col-md-1 mb-xs">
                                          <a href="#" class="btn btn-default" data-invoice-row-id="{$row->mockInvoiceRowId}" data-toggle="delete-invoice-row">
                                             <i class="fa fa-trash"></i>
                                          </a>
                                       </div>
                                    </div>

HTML;
      }

      // Output the HTML if we have some
      if ( $rowHTML != '' ) {
         echo $rowHTML;
      }
   }


   /** ----------------------------------------------------------------------------
    * Prints out the HTML to display the mock invoice details as they are entered
    *
    * @param   array $_POST      The mock invoice id is sent via POST
    * @return  void              Prints the HTML for the mock invoice details table
    */
   public function print_invoice_details_table() {

      // Language variables to be used in heredoc syntax
      $tableHeaderCategory    = lang( 'report_th_category' );
      $tableHeaderDescription = lang( 'report_th_description' );
      $tableHeaderAmount      = lang( 'report_th_amount' );

      // Get the invoice row details
      $invoiceRows = $this->mockinvoice_row_model->get_by( 'mockInvoiceId = ' . @$_POST['mockInvoiceId'] );

      $tableRows = '';

      foreach ( $invoiceRows as $row ) {

         // Add currency symbol Eg. '$' to the amount if we have one
         ( $row->amount != null ? $amount = lang( 'system_currency_symbol' ) . $row->amount : $amount = '' );
         // Replace new lines with HTML <br> tags
         $description = nl2br( $row->description );

         // Check if a row has some content and add it to the table if it does
         if ( ( $row->category != null ) or ( $row->description != null ) or ( $row->amount != null ) ) {
            $tableRows .= <<< HTML
                                    <tr id="invoice-details-row-{$row->mockInvoiceRowId}">
                                       <td>{$row->category}</td>
                                       <td>{$description}</td>
                                       <td>{$amount}</td>
                                    </tr>

HTML;
         }
      }

      // Output the HTML
      if ( $tableRows == '' ) {

         // Show that we have no details yet to display
         echo '<div class="alert alert-warning">' . lang( 'report_minv_empty' ) . '</div>';
      } else {

         // Display the table with invoice details
         echo <<< HTML
                                 <table class="table table-striped table-bordered table-hover table-report">
                                 <thead>
                                    <tr class="report-heading-row">
                                       <th>{$tableHeaderCategory}</th>
                                       <th>{$tableHeaderDescription}</th>
                                       <th>{$tableHeaderAmount}</th>
                                    </tr>
                                 </thead>
                                 <tbody>

{$tableRows}

                                 </tbody>
                                 </table>

HTML;
      }
   }


   /** ----------------------------------------------------------------------------
    * Prints out the HTML to display the panel with all entries attached to a mock invoice
    *
    * @param   array $_POST      The mock invoice id is sent via POST
    * @return  void              Prints the HTML for the mock invoice details table
    */
   public function print_invoice_entries_panel() {

      // The mock invoice details
      $invoiceDetails = $this->mockinvoice_model->get( $_POST['mockInvoiceId'] );

      // The entries to be shown in the Mock Invoice report
      $reportEntries = $this->mockinvoice_model->get_mock_invoice_entries( $invoiceDetails->sortOrder, '', $_POST['mockInvoiceId'] );

      print_minv_report( $reportEntries, $invoiceDetails, $_POST['mockInvoiceId'] );
   }

}