<?php
/**
 * Timesheet Entry Model
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Timesheet_entry_model extends MY_Model {

   protected $_table_name  = "time_timesheet_entry";
   protected $_order_by    = 'startDateTime';
   protected $_primary_key = "timesheetEntryId";

   function __construct() {
      parent::__construct();
   }


   /** ----------------------------------------------------------------------------
    * Gets the details for a single timesheet entry
    *
    * @param   int   $entryId    The id of the timesheet entry
    * @param   bool  $isChild    If the entry is attached to a child job
    * @return  array
    */
   public function get_entry_details( $entryId, $isChild ) {

      // Is the entry attached to a child job?
      if ( $isChild ) {
         // Join the entry to the child job and get the parent details
         $jobFromSQL = 'INNER JOIN time_job AS child ON te.jobId = child.jobId
                        INNER JOIN time_job AS j ON child.parentJobId = j.jobId';
      } else {
         // Join the entry to the job and return null for child details
         $jobFromSQL = 'INNER JOIN time_job AS j ON te.jobId = j.jobId
                        LEFT JOIN time_job AS child ON j.parentJobId = child.jobId';  // Always empty when not a child
      }

      // Get the full details of the timesheet entry
      $sql = 'SELECT timesheetEntryId AS entryId,
               u.userId AS userId,
               j.jobId AS jobId,
               tt.timesheetTaskId AS taskId,
               j.jobReferenceNo AS jobRef,
               j.jobName AS jobName,
               taskName, color, groupColor,
               groupName AS taskGroup,
               te.startDateTime AS startDateTime,
               te.endDateTime AS endDateTime,
               te.totalHours AS timeTaken,
               comment, disbursement,
               u.firstName AS firstName,
               u.surname AS surname,
               child.jobId AS childId,
               child.jobReferenceNo AS childRef,
               child.jobName AS childName
            FROM time_timesheet_entry AS te
               INNER JOIN time_user AS u ON te.userId = u.userId
               INNER JOIN time_timesheet_task AS tt ON te.timesheetTaskId = tt.timesheetTaskId
               INNER JOIN time_timesheet_taskgroup AS ttg ON tt.timesheetTaskGroupId = ttg.timesheetTaskGroupId
               ' . $jobFromSQL . '
            WHERE timesheetEntryId = ' . $entryId;

      return $this->db->query( $sql )->result_array();

   }


   /** ----------------------------------------------------------------------------
    * Gets all entries for a user in a given date range
    *
    * @param   int      $userId     The user id
    * @param   string   $startDate  Date in YYYY-MM-DD format
    * @param   string   $endDate    Date in YYYY-MM-DD format
    * @return  array
    */
   public function get_user_entries( $userId, $startDate, $endDate ) {

      // Get entries in the given date range
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
                  taskName, color, groupColor,
                  groupName AS taskGroup,
                  te.startDateTime AS startDateTime,
                  te.endDateTime AS endDateTime,
                  te.totalHours AS timeTaken,
                  comment, disbursement,
                  u.firstName AS firstName,
                  u.surname AS surname,
                  child.jobId AS childId,
                  child.jobReferenceNo AS childRef,
                  child.jobName AS childName
               FROM time_timesheet_entry AS te
                  INNER JOIN time_user AS u ON te.userId = u.userId
                  INNER JOIN time_timesheet_task AS tt ON te.timesheetTaskId = tt.timesheetTaskId
                  INNER JOIN time_timesheet_taskgroup AS ttg ON tt.timesheetTaskGroupId = ttg.timesheetTaskGroupId
                  INNER JOIN time_job AS j ON te.jobId = j.jobId
                  LEFT JOIN time_job AS child ON j.parentJobId = child.jobId
               WHERE u.userId = ' . $userId
               .' AND startDateTime >= "' . $startDate . ' 00:00:00"
                  AND endDateTime <= "' . $endDate . ' 00:00:00"
                  AND j.parentJobId IS NULL
               UNION ALL
               SELECT timesheetEntryId AS entryId,
                  u.userId AS userId,
                  j.jobId AS jobId,
                  tt.timesheetTaskId AS taskId,
                  j.jobReferenceNo AS jobRef,
                  j.jobName AS jobName,
                  taskName, color, groupColor,
                  groupName AS taskGroup,
                  te.startDateTime AS startDateTime,
                  te.endDateTime AS endDateTime,
                  te.totalHours AS timeTaken,
                  comment, disbursement,
                  u.firstName AS firstName,
                  u.surname AS surname,
                  child.jobId AS childId,
                  child.jobReferenceNo AS childRef,
                  child.jobName AS childName
               FROM time_timesheet_entry AS te
                  INNER JOIN time_user AS u ON te.userId = u.userId
                  INNER JOIN time_timesheet_task AS tt ON te.timesheetTaskId = tt.timesheetTaskId
                  INNER JOIN time_timesheet_taskgroup AS ttg ON tt.timesheetTaskGroupId = ttg.timesheetTaskGroupId
                  INNER JOIN time_job AS child ON te.jobId = child.jobId
                  INNER JOIN time_job AS j ON child.parentJobId = j.jobId
               WHERE u.userId = ' . $userId
               .' AND startDateTime >= "' . $startDate . ' 00:00:00"
                  AND endDateTime <= "' . $endDate . ' 00:00:00"
            ) entries
            ORDER BY entries.startDateTime';

      return $this->db->query( $sql )->result_array();

   }


   /** ----------------------------------------------------------------------------
    * Gets all entries to be displayed on the work in progress report
    *
    * @param   string   $startDate     Date in YYYY-MM-DD format
    * @param   string   $endDate       Date in YYYY-MM-DD format
    * @param   array    $jobArray      Array of job ids
    * @param   array    $userArray     Array of user ids
    * @param   string   $sortOrder     Order results should be sorted by
    * @return  array
    */
   public function get_wip_entries( $startDate, $endDate, $jobArray, $userArray, $filterArray, $sortOrder ) {

      $condition      = '';   // Conditions for the first UNION SQL (parent jobs)
      $conditionChild = '';   // Conditions for the second UNION SQL (child jobs)
      if ( $startDate != '' ) {
         $condition .= 'AND startDateTime >= "' . $startDate . ' 00:00:00" ';
      }
      if ( $endDate != '' ) {
         $condition .= 'AND endDateTime <= "' . date( 'Y-m-d', strtotime( $endDate . ' +1 day' ) ) . ' 00:00:00" ';
      }
      // Was the report restricted to certain users?
      if ( $userArray != '' ) {
         $condition .= 'AND u.userId IN (' . implode( ', ', $userArray ) . ') ';
      }

      // Was there a filter applied?
      if ( $filterArray != '' ) {
         // Ignore if all filters were applied (Same as nothing being filtered)
         if ( array_diff( ['current', 'archived', 'written-off'], $filterArray ) ) {
            if ( in_array( 'current', $filterArray ) ) {
               if ( in_array( 'archived', $filterArray ) ) {
                  // Show current and archived entries
                  $condition .= 'AND NOT writtenOff ';
               } elseif ( in_array( 'written-off', $filterArray ) ) {
                  // Show current and written off entries
                  $condition .= 'AND NOT te.archived ';
               } else {
                  // Only show current entries
                  $condition .= 'AND NOT ( te.archived OR writtenOff ) ';
               }
            } else { // Current entries were not selected
               // Were both archived and written off entries selected?
               if ( !array_diff( ['archived', 'written-off'], $filterArray ) ) {
                  $condition .= 'AND ( te.archived OR writtenOff ) ';
               } else { // Only one was selected
                  // Show archived entries if selected
                  if ( in_array( 'archived', $filterArray ) ) { $condition .= 'AND te.archived '; }
                  // Show written off entries if selected
                  if ( in_array( 'written-off', $filterArray ) ) { $condition .= 'AND writtenOff '; }
               }
            }
         }
      }
      $conditionChild = $condition;    // Both are the same to here, so we copy

      // Was the report restricted to certain jobs?
      if ( $jobArray != '' ) {
         $condition .= 'AND j.jobId IN (' . implode( ', ', $jobArray ) . ') ';
         $conditionChild .= 'AND child.jobId IN (' . implode( ', ', $jobArray ) . ') ';
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
                  u.initials AS initials,
                  chargeRate,
                  child.jobId AS childId,
                  child.jobReferenceNo AS childRef,
                  child.jobName AS childName,
                  mockInvoiceId
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
                  u.initials AS initials,
                  chargeRate,
                  child.jobId AS childId,
                  child.jobReferenceNo AS childRef,
                  child.jobName AS childName,
                  mockInvoiceId
               FROM time_timesheet_entry AS te
                  INNER JOIN time_user AS u ON te.userId = u.userId
                  INNER JOIN time_timesheet_task AS tt ON te.timesheetTaskId = tt.timesheetTaskId
                  INNER JOIN time_timesheet_taskgroup AS ttg ON tt.timesheetTaskGroupId = ttg.timesheetTaskGroupId
                  INNER JOIN time_job AS child ON te.jobId = child.jobId
                  INNER JOIN time_job AS j ON child.parentJobId = j.jobId
               WHERE NOT hiddenReports '
               . $conditionChild . '
            ) entries
            ORDER BY entries.jobRef desc, entries.childRef, ' . $sortOrder;

      return $this->db->query( $sql )->result_array();

   }


   /** ----------------------------------------------------------------------------
    * Updates entries with the mock invoice id they were added to
    *
    * @param   int      $mockInvoiceId       Mock invoice id
    * @param   array    $entryarray          Array of entry ids
    * @return  void
    */
   public function set_mock_invoice_details( $mockInvoiceId, $entryArray ) {
      // Add data to be inserted into an array
      $data = array(
         'invoiced' => 1,
         'mockInvoiceId' => $mockInvoiceId
      );
      $where = 'timesheetEntryId IN (' . implode( ', ', $entryArray ) . ')';
      $this->db->update( 'time_timesheet_entry', $data, $where );
   }

}