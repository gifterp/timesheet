<?php
/**
 * Mock Invoice Model
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Mockinvoice_model extends MY_Model {

   protected $_table_name = "time_mockinvoice";
   protected $_order_by = "mockInvoiceId";
   protected $_primary_key = "mockInvoiceId";

   function __construct(){
      parent::__construct();
   }


   /** ----------------------------------------------------------------------------
   * Returns a list of mock invoices attached to a job
   *
   * Orders any current ones at the start
   * Current invoices are those not yet marked as ready
   *
   * @param   int    $jobId     The job id
   * @return  array
   */
   public function get_invoices( $jobId ) {

      $this->db->select( 'mockInvoiceId, readyToInvoice, archived' );
      $this->db->where( 'jobId', $jobId );
      $this->db->order_by( 'readyToInvoice', 'ASC' );
      $this->db->order_by( 'archived', 'ASC' );
      return $this->db->get( 'time_mockinvoice' )->result_array();
   }


   /** ----------------------------------------------------------------------------
   * Returns a list of mock invoices marked as ready but not yet complete
   *
   * @return  array
   */
   public function get_ready_invoices() {

      // Get the invoices attached to parent jobs and jobs without children first
      // Then get invoices attached to child jobs and join via UNION ALL to order them
      $sql = 'SELECT invoices.*
            FROM (
               SELECT j.jobReferenceNo AS jobRef,
                  j.jobName AS jobName,
                  child.jobReferenceNo AS childRef,
                  child.jobName AS childName,
                  mockInvoiceId
               FROM time_mockinvoice AS mi
                  INNER JOIN time_job AS j ON mi.jobId = j.jobId
                  LEFT JOIN time_job AS child ON j.parentJobId = child.jobId
               WHERE j.parentJobId IS NULL
                  AND readyToInvoice = 1
                  AND mi.archived = 0
               UNION ALL
               SELECT j.jobReferenceNo AS jobRef,
                  j.jobName AS jobName,
                  child.jobReferenceNo AS childRef,
                  child.jobName AS childName,
                  mockInvoiceId
               FROM time_mockinvoice AS mi
                  INNER JOIN time_job AS child ON mi.jobId = child.jobId
                  INNER JOIN time_job AS j ON child.parentJobId = j.jobId
               WHERE readyToInvoice = 1
                  AND mi.archived = 0
            ) invoices
            ORDER BY invoices.mockInvoiceId asc';
      return $this->db->query( $sql )->result_array();
   }


   /** ----------------------------------------------------------------------------
   * Returns details of a mock invoice as an array
   *
   * @param   int    $mockInvoiceId     The id of the mock invoice to get
   * @return  array
   */
   public function get_settings_array( $mockInvoiceId ) {

      $this->db->where( 'mockInvoiceId', $mockInvoiceId );
      return $this->db->get( 'time_mockinvoice' )->result_array();
   }


   /** ----------------------------------------------------------------------------
   * Returns a list of entry ids included in a mock invoice
   *
   * @param   int    $mockInvoiceId     The id of the mock invoice
   * @return  array
   */
   public function get_entry_ids( $mockInvoiceId ) {

      $this->db->select( 'timesheetEntryId, jobId' );
      $this->db->where( 'mockInvoiceId', $mockInvoiceId );
      return $this->db->get( 'time_timesheet_entry' )->result_array();
   }


   /** ----------------------------------------------------------------------------
   * Removes a single entry from a mock invoice
   *
   * @param   int    $timesheetEntryId     The timesheet entry id
   */
   public function remove_single_entry( $timesheetEntryId ) {

      $this->db->set( 'invoiced', 0 );
      $this->db->set( 'archived', 0 );
      $this->db->set( 'writtenOff', 0 );
      $this->db->set( 'mockInvoiceId', null );
      $this->db->where( 'timesheetEntryId', $timesheetEntryId );
      $this->db->update( 'time_timesheet_entry' );
   }


   /** ----------------------------------------------------------------------------
   * Removes all entries from a mock invoice
   *
   * @param   int    $mockInvoiceId     The id of the mock invoice
   */
   public function remove_all_entries( $mockInvoiceId ) {

      $this->db->set( 'invoiced', 0 );
      $this->db->set( 'archived', 0 );
      $this->db->set( 'writtenOff', 0 );
      $this->db->set( 'mockInvoiceId', null );
      $this->db->where( 'mockInvoiceId', $mockInvoiceId );
      $this->db->update( 'time_timesheet_entry' );
   }


   /** ----------------------------------------------------------------------------
   * Archives/restores all entries from a mock invoice
   *
   * @param   int    $mockInvoiceId     The id of the mock invoice
   * @param   int    $archived          The archive state to set
   */
   public function archive_all_entries( $mockInvoiceId, $archived ) {

      $this->db->set( 'archived', $archived );
      $this->db->where( 'writtenOff', 0 );
      $this->db->where( 'mockInvoiceId', $mockInvoiceId );
      $this->db->update( 'time_timesheet_entry' );
   }


   /** ----------------------------------------------------------------------------
   * Counts both the number of entries in the mock invoice and how many rows it has
   *
   * @param   int    $mockInvoiceId       The id of the mock invoice
   * @return  array (of objects)
   */
   public function count_invoice_entries( $mockInvoiceId ) {

      // Count the number of entries in both the entire invoice and a single row
      $sql = 'SELECT
               ( SELECT COUNT(mockInvoiceId) FROM time_timesheet_entry WHERE mockInvoiceId = ' . $mockInvoiceId . ' ) AS invoiceEntryCount,
               ( SELECT COUNT(mockInvoiceRowId) FROM time_mockinvoice_row WHERE mockInvoiceId = ' . $mockInvoiceId . ' ) AS invoiceRowCount';
      return $this->db->query( $sql )->result();
   }


   /** ----------------------------------------------------------------------------
    * Gets all entries to be displayed on the mock invoice report
    *
    * @param   string   $sortOrder        Order results should be sorted by
   * @param    array    $entryarray       Array of entry ids
   * @param    array    $mockInvoiceId    A mock invoice id
    * @return  array
    */
   public function get_mock_invoice_entries( $sortOrder, $entryarray = '', $mockInvoiceId = '' ) {

      $condition = '';   // Extra conditions for query

      // Was the report restricted to certain entries?
      if ( $entryarray != '' ) {
         $condition .= 'AND timesheetEntryId IN (' . implode( ', ', $entryarray ) . ') ';
      }
      // Was the report restricted to certain entries from a mock invoice?
      if ( $mockInvoiceId != '' ) {
         $condition .= 'AND te.mockInvoiceId = ' . $mockInvoiceId . ' ';
      }


      // Get entries based on the report parameters given
      // Get the entries attached to parent jobs and jobs without children first
      // Then get entries attached to child jobs and join via UNION ALL to order them
      $sql = 'SELECT entries.*
            FROM (
               SELECT timesheetEntryId AS entryId,
                  u.userId AS userId,
                  j.jobId AS jobId,
                  tt.timesheetTaskId AS taskId,
                  j.jobReferenceNo AS jobRef,
                  j.jobName AS jobName,
                  chargeable, taskName,
                  groupName AS taskGroup,
                  te.startDateTime AS startDateTime,
                  te.endDateTime AS endDateTime,
                  te.totalHours AS timeTaken,
                  comment, disbursement,
                  te.archived AS archived,
                  writtenOff, invoiced,
                  concat(u.surname, ", ", u.firstName) AS name,
                  chargeRate,
                  child.jobId AS childId,
                  child.jobReferenceNo AS childRef,
                  child.jobName AS childName,
                  te.mockInvoiceId AS mockInvoiceId
               FROM time_timesheet_entry AS te
                  INNER JOIN time_user AS u ON te.userId = u.userId
                  INNER JOIN time_timesheet_task AS tt ON te.timesheetTaskId = tt.timesheetTaskId
                  INNER JOIN time_timesheet_taskgroup AS ttg ON tt.timesheetTaskGroupId = ttg.timesheetTaskGroupId
                  INNER JOIN time_job AS j ON te.jobId = j.jobId
                  LEFT JOIN time_job AS child ON j.parentJobId = child.jobId
               WHERE j.parentJobId IS NULL
                  AND NOT hiddenReports '
               . $condition . '
               UNION ALL
               SELECT timesheetEntryId AS entryId,
                  u.userId AS userId,
                  j.jobId AS jobId,
                  tt.timesheetTaskId AS taskId,
                  j.jobReferenceNo AS jobRef,
                  j.jobName AS jobName,
                  chargeable, taskName,
                  groupName AS taskGroup,
                  te.startDateTime AS startDateTime,
                  te.endDateTime AS endDateTime,
                  te.totalHours AS timeTaken,
                  comment, disbursement,
                  te.archived AS archived,
                  writtenOff, invoiced,
                  concat(u.surname, ", ", u.firstName) AS name,
                  chargeRate,
                  child.jobId AS childId,
                  child.jobReferenceNo AS childRef,
                  child.jobName AS childName,
                  te.mockInvoiceId AS mockInvoiceId
               FROM time_timesheet_entry AS te
                  INNER JOIN time_user AS u ON te.userId = u.userId
                  INNER JOIN time_timesheet_task AS tt ON te.timesheetTaskId = tt.timesheetTaskId
                  INNER JOIN time_timesheet_taskgroup AS ttg ON tt.timesheetTaskGroupId = ttg.timesheetTaskGroupId
                  INNER JOIN time_job AS child ON te.jobId = child.jobId
                  INNER JOIN time_job AS j ON child.parentJobId = j.jobId                   
               WHERE NOT hiddenReports '
               . $condition . '
            ) entries
            ORDER BY entries.jobRef desc, entries.childRef, ' . $sortOrder;

      return $this->db->query( $sql )->result_array();

   }

}