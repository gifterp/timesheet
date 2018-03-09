<?php
/**
 * Timesheet Controller
 *
 * Displays the timesheet diary page and handles the AJAX calls to the database
 * 
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Timesheet extends Admin_controller {


   public function __construct() {
      parent::__construct();
      $this->load->model('job_model');
      $this->load->model('task_model');
      $this->load->model('timesheet/timesheet_entry_model');
      $this->load->model('timesheet/timesheet_settings_model');
      $this->load->model('timesheet/user_savedjob_model');
      $this->load->library('json_library');
      $this->lang->load('timesheet', 'english');
      $this->lang->load('job', 'english');
   }


   /** ----------------------------------------------------------------------------
    * Displays the timesheet diary page
    */
   public function index() {

      $this->data['meta_title']         = lang('timesheet_name');
      $this->data['page_title']         = lang('timesheet_sect_title') . '<span class="timesheet-user-name">' . $_SESSION['fullName'] . '</span>';
      $this->data['breadcrumb_items']   = array(lang('system_menu_timesheets'));
      // List of jobs to be used in dropdowns and side list of draggables
      $this->data['jobObjArray']        = $this->job_model->get_list();
      // List of tasks to be used in dropdowns and side list of draggables
      $this->data['taskObjArray']       = $this->task_model->get_list();
      // Timesheet settings to be used by the fullCalendar plugin
      $this->data['timesheetSettings']  = $this->timesheet_settings_model->get( 1, true );
      // Page specific JavaScript and CSS
      $this->data['pageVendorJS']       = $this->load->view('timesheet/_timesheet-vendor-js', '', true);
      $this->data['pageCustomJS']       = $this->load->view('timesheet/_timesheet-custom-js', $this->data, true);
      $this->data['pageVendorCss']      = $this->load->view('timesheet/_timesheet-vendor-css', '', true);
      $this->data['pageCustomCss']      = $this->load->view('timesheet/_timesheet-custom-css', '', true);


      $this->load->view('_template/header', $this->data);
      $this->load->view('_template/page-header');
      $this->load->view('timesheet/index', $this->data);
      $this->load->view('_template/sidebar-right');
      $this->load->view('_template/footer');

   }


   /** ----------------------------------------------------------------------------
    * Returns timesheet entries in JSON format for the jQuery fullCalendar plugin to display
    *
    * @param   $_POST     Start date, end date and user id
    * @return  void
    */
   public function get_entries_json() {

      $userId     = $this->input->post('userId');
      $startDate  = $this->input->post('start');
      $endDate    = $this->input->post('end');

      // Get the full entry detail set to pass back to the timesheet diary
      $entryArray = $this->timesheet_entry_model->get_user_entries( $userId, $startDate, $endDate );

      // Send back the entry details as JSON
      $this->json_library->print_array_json( $entryArray );

   }

 
   /** ----------------------------------------------------------------------------
    * Adds a new timesheet entry to the database
    *
    * First it adds the entry to the database, then retrieves and returns the full
    * set of data needed from the database in JSON format
    *
    * @param   $_POST      The entry details
    * @return  void
    */
   public function add_timesheet_entry() {

      // Save the entry details
      $data = array(
         'jobId' => $this->_get_entry_job_id(),
         'userId' => $this->input->post( 'userId' ),
         'timesheetTaskId' => $this->input->post( 'timesheetTaskId' ),
         'startDateTime' => $this->input->post( 'startDateTime' ),
         'endDateTime' => $this->input->post( 'endDateTime' ),
         'totalHours' => $this->input->post( 'totalHours' ),
         'comment' => $this->input->post( 'comment' )
      );
      $entryId = $this->timesheet_entry_model->save( $data );

      // Cut other entries if they shouldn't overlap
      $cut = $this->input->post( 'cut' );
      if ( $cut == 'true' ) { $this->_cut_overlapping_entries( $data, $entryId ); }

      // Get the full entry details to pass back to the timesheet diary
      $entryArray = $this->timesheet_entry_model->get_entry_details( $entryId, $this->_check_if_child_job() );
      $entryArray[0]['haveCut'] = $cut;

      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $entryArray );

   }


   /** ----------------------------------------------------------------------------
    * Updates timesheet entry in the database
    *
    * Updates the entry details in the database, then retrieves and returns the full
    * set of data needed from the database in JSON format
    *
    * @param   $_POST      The entry details
    * @return  void
    */
   public function update_timesheet_entry() {

      $entryId = $this->input->post( 'entryId' );

      // Save the entry details
      $data = array(
         'jobId' => $this->_get_entry_job_id(),
         'timesheetTaskId' => $this->input->post( 'timesheetTaskId' ),
         'startDateTime' => $this->input->post( 'startDateTime' ),
         'endDateTime' => $this->input->post( 'endDateTime' ),
         'totalHours' => $this->input->post( 'totalHours' ),
         'comment' => $this->input->post( 'comment' ),
         'disbursement' => $this->input->post( 'disbursement' )
      );
      $this->timesheet_entry_model->save( $data, $entryId );

      // Get the full entry details to pass back to the timesheet diary
      $entryArray = $this->timesheet_entry_model->get_entry_details( $entryId, $this->_check_if_child_job() );

      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $entryArray );

   }


   /** ----------------------------------------------------------------------------
    * Updates timesheet entry times in the database
    *
    * Called when a user drag and drops the entry to adjust the length of time
    *
    * @param   $_POST      The entry details
    * @return  void
    */
   public function update_timesheet_entry_times() {

      // Save the entry details
      $data = array(
         'startDateTime' => $this->input->post( 'startDateTime' ),
         'endDateTime' => $this->input->post( 'endDateTime' ),
         'totalHours' => $this->input->post( 'totalHours' )
      );
      $entryId = $this->timesheet_entry_model->save( $data, $this->input->post( 'entryId' ) );

      // Cut other entries if they shouldn't overlap
      $cut = $this->input->post( 'cut' );
      if ( $cut == 'true' ) { $this->_cut_overlapping_entries( $data, $entryId ); }

      $entryArray[] = array( 'entryId' => $entryId, 'haveCut' => $cut );

      // Send back the entry id as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $entryArray );

   }


   /** ----------------------------------------------------------------------------
    * Clones a timesheet entry in the database with a new start and end time
    *
    * Users can clone a timesheet entry by holding down the SHIFT key and dragging an
    * entry. It will create an exact copy with a new start and end time.
    *
    * @param   $_POST      The entry details
    * @return  void
    */
   public function clone_timesheet_entry() {

      // Save the entry details
      $data = array(
         'jobId' => $this->_get_entry_job_id(),
         'timesheetTaskId' => $this->input->post( 'timesheetTaskId' ),
         'userId' => $this->input->post( 'userId' ),
         'startDateTime' => $this->input->post( 'startDateTime' ),
         'endDateTime' => $this->input->post( 'endDateTime' ),
         'totalHours' => $this->input->post( 'totalHours' ),
         'comment' => $this->input->post( 'comment' ),
         'disbursement' => $this->input->post( 'disbursement' )
      );
      $entryId = $this->timesheet_entry_model->save( $data );

      $entryArray[] = array( 'entryId' => $entryId );

      // Send back the new entry id as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $entryArray );

   }


   /** ----------------------------------------------------------------------------
    * Deletes a timesheet entry from the database
    *
    * Returns the entryId as JSON but checks it was deleted. If it is still in the
    * database it will return an empty response and trigger an error notification
    *
    * @param   $_POST      The entry details
    * @return  void
    */
   public function delete_timesheet_entry() {

      $entryId = $this->input->post('entryId');
      $entryArray[] = array( 'entryId' => $entryId );

      // Delete the timesheet entry
      $this->timesheet_entry_model->delete( $entryId );

      // Check the entry was deleted
      if ( count( $this->timesheet_entry_model->get( $entryId, true ) ) == 1 ) {
         // Set entry array to empty if it wasn't deleted
         $entryArray = [];
      }

      // Send back the entry id as JSON. Error if empty
      $this->json_library->print_array_json_unless_empty( $entryArray );

   }


   /** ----------------------------------------------------------------------------
    * Generates HTML for jobs saved by the user as a series of draggable divs
    *
    * These jobs are displayed in their personal job list to drag onto the timesheets
    * and create new entries
    *
    * @param   int   $userId           Passed via POST
    * @return  void
    */
   public function personal_job_list_html() {
          
      $count      = 0;  // Number of jobs in list
      $userId     = $this->input->post('userId');
      $arrayJobs  = $this->job_model->get_user_jobs( $userId );

      foreach ( $arrayJobs as $row ) {

         // Print the job if it's one
         if ( $row["parentJobId"] == "" ) {

            echo "<div class=\"draggable-event event personal-event\">\n"
               ."   " . $row['jobReferenceNo'] . " - " . $row['jobName'] . "\n"
               ."   <span class=\"entry-title\">" . $row['jobReferenceNo'] . " - " . $row['jobName'] . "</span>"
               ."   <span class=\"entry-job-id\">" . $row['jobId'] . "</span>"
               ."   <span class=\"entry-job-ref\">" . $row['jobReferenceNo'] . "</span>"
               ."   <span class=\"entry-job-name\">" . $row['jobName'] . "</span>"
               ."   <span class=\"entry-db-id\">" . $row['draggableId'] . "</span>"
               ."   <span class=\"entry-type\">job</span>\n"
               ."</div>\n";
         }

         // Print the child if it is one
         if ( $row["parentJobId"] != "" ) {

               echo "<div class=\"draggable-event child-event personal-event\">\n"
               ."   [" . $row['parentReferenceNo'] . "] >> " . $row['jobReferenceNo'] . " - " . $row['jobName'] . "\n"
               ."   <span class=\"entry-title\">[" . $row['parentReferenceNo'] . "] >> " . $row['jobReferenceNo'] . " - " . $row['jobName'] . "</span>"
               ."   <span class=\"entry-job-id\">" . $row['parentJobId'] . "</span>"
               ."   <span class=\"entry-job-ref\">" . $row['parentReferenceNo'] . "</span>"
               ."   <span class=\"entry-job-name\">" . $row['parentJobName'] . "</span>"
               ."   <span class=\"entry-child-job-id\">" . $row['jobId'] . "</span>"
               ."   <span class=\"entry-child-job-ref\">" . $row['jobReferenceNo'] . "</span>"
               ."   <span class=\"entry-child-job-name\">" . $row['jobName'] . "</span>"
               ."   <span class=\"entry-db-id\">" . $row['draggableId'] . "</span>"
               ."   <span class=\"entry-type\">job</span>\n"
               ."</div>\n";
         }
         $count += 1;

      }
      // The user has no jobs to display
      if ( $count == 0 ) {
         echo '<div class="add-personal-job-helper"><i class="fa fa-plus fa-lg mr-sm"></i><span class="panel-subtitle">' . lang('timesheet_list_personal_empty') . '</span></div>';
      }

   }


   /** ----------------------------------------------------------------------------
    * Saves a job to the users personal saved job list
    *
    * These jobs are displayed in their personal job list to drag onto the timesheets
    * and create new entries
    *
    * @param   $_POST         userId, jobId and title
    * @return  void           Prints valid JSON
    */
   public function save_user_job() {

      // Save the job details
      $data = array(
        'jobId' => $this->input->post( 'jobId' ),
        'userId' => $this->input->post( 'userId' )
      );
      $this->user_savedjob_model->save( $data );

      // Save the title to use in the notification
      $titleArray[] = array( 'title' => $this->input->post( 'title' ) );

      // Send back the title as JSON
      $this->json_library->print_array_json( $titleArray );

   }


   /** ----------------------------------------------------------------------------
    * Deletes a job from the users personal saved job list
    *
    * These jobs are displayed in their personal job list to drag onto the timesheets
    * and create new entries
    *
    * @param   $_POST         userId, jobId and title
    * @return  void           Prints valid JSON
    */
   public function delete_user_job() {

      // Delete the job details
      $this->user_savedjob_model->delete( $this->input->post( 'entryId' ) );

      // Save the title to use in the notification
      $titleArray[] = array( 'title' => $this->input->post( 'title' ) );

      // Send back the title as JSON
      $this->json_library->print_array_json( $titleArray );

   }


   /** ----------------------------------------------------------------------------
    * Returns the correct job id for a timesheet entry
    *
    * Checks if the entry is a parent or a child and assigns the correct id
    *
    * @param   $_POST         jobId and childId
    * @return  string         The job id
    */
   private function _get_entry_job_id() {

      $jobId = $this->input->post( 'jobId' );

      // Update the job id if it's a child job
      if ( !empty( $this->input->post('childId') ) ) {
         $jobId = $this->input->post( 'childId' );
      }

      return $jobId;
   }


   /** ----------------------------------------------------------------------------
    * Checks if the timesheet entry is attached to a child job
    *
    * @param   $_POST         childId
    * @return  bool           If it's a child
    */
   private function _check_if_child_job() {

      $isChild = false;

      // Check if it is a child
      if ( !empty( $this->input->post('childId') ) ) {
         $isChild = true;
      }

      return $isChild;
   }


   /** ----------------------------------------------------------------------------
    * Checks for overlapping entries and cuts any of those that do overlap
    *
    * It will create a new entry for any that are split
    * It also updates the total hours taken
    *
    * @param   array $data       Has the start and end date/times to cut
    * @param   int   $entryId    Id of the original entry, to avoid it being modified
    * @return  void
    */
   private function _cut_overlapping_entries( $data, $entryId ) {

      // First we delete any entries that are overwritten completely
      $this->_delete_overwritten_entries( $data, $entryId );

      // Then trim entries from the start if needed
      $this->_trim_entries( $data, $entryId, true );

      // Then trim entries from the end if needed
      $this->_trim_entries( $data, $entryId, false );

      // Finally, split any entries if needed
      $this->_split_entries( $data, $entryId );
   }


   /** ----------------------------------------------------------------------------
    * Checks for entries totally within the start and end time and deletes them
    *
    * @param   array $data       Has the start and end date/times to delete
    * @return  void
    */
   private function _delete_overwritten_entries( $data, $entryId ) {

      // Delete entries within the start and end times
      $this->db->where( 'startDateTime >=', $data['startDateTime'] );
      $this->db->where( 'endDateTime <=', $data['endDateTime'] );
      $this->db->where( 'timesheetEntryId !=', $entryId );
      $this->db->delete('time_timesheet_entry');
   }


   /** ----------------------------------------------------------------------------
    * Checks for entries where the start or end overlaps another entry and trims them
    *
    * It also updates the total hours taken
    *
    * @param   array $data          Has the start and end date/times to delete
    * @param   int   $entryId       Id of the original entry, to avoid it being modified
    * @param   bool  $fromStart     true = trim from start, false = trim from end of entries
    * @return  void
    */
   private function _trim_entries( $data, $entryId, $fromStart ) {

      // Get entries within the start and end times
      $this->db->select('timesheetEntryId, startDateTime, endDateTime');
      if ( $fromStart ) {  // Trim from the start of the entries
         $this->db->where( 'startDateTime >=', $data['startDateTime'] );
         $this->db->where( 'startDateTime <', $data['endDateTime'] );
         $this->db->where( 'endDateTime >', $data['endDateTime'] );
      } else {             // Trim from the end of the entries
         $this->db->where( 'endDateTime <=', $data['endDateTime'] );
         $this->db->where( 'endDateTime >', $data['startDateTime'] );
         $this->db->where( 'startDateTime <', $data['startDateTime'] );
      }
      $this->db->where( 'timesheetEntryId !=', $entryId ); // Won't be selected, but just in case
      $query = $this->db->get( 'time_timesheet_entry' );
      $entries = $query->result();

      if ( count( $entries ) > 0 ) {
         // Loop through entries to be updated and change the following data
         // startDateTime, endDateTime, totalHours
         foreach ( $entries as $row ) {
            // Set to current start and end date/time for the entry being updated
            $startDateTime = $row->startDateTime;
            $endDateTime   = $row->endDateTime;

            if ( $fromStart ) {
               // Trim from start. Update the start date/time
               $this->db->set( 'startDateTime', $data['endDateTime'] );
               $startDateTime = $data['endDateTime'];
            } else {
               // Trim from end. Update the end date/time
               $this->db->set( 'endDateTime', $data['startDateTime'] );
               $endDateTime = $data['startDateTime'];
            }

            // Get the new totalHours figure and set to update
            $totalHours = $this->_calculate_total_hours( $startDateTime, $endDateTime );
            $this->db->set( 'totalHours', $totalHours );

            // Update the entry
            $this->db->where( 'timesheetEntryId', $row->timesheetEntryId );
            $this->db->update( 'time_timesheet_entry' );

         }
      }
   }


   /** ----------------------------------------------------------------------------
    * Checks for entries that will be split in the middle
    *
    * It shortens the entry and updates the total hours taken, then creates
    * another entry and places it after the entry overwriting it.
    *
    * @param   array $data          Has the start and end date/times to delete
    * @param   int   $entryId       Id of the original entry, to avoid it being modified
    * @return  void
    */
   private function _split_entries( $data, $entryId ) {

      // Get entries that will be split
      $this->db->select('*');
      $this->db->where( 'startDateTime <', $data['startDateTime'] );
      $this->db->where( 'endDateTime >', $data['endDateTime'] );
      $this->db->where( 'timesheetEntryId !=', $entryId ); // Won't be selected, but just in case
      $query = $this->db->get( 'time_timesheet_entry' );
      $entries = $query->result();

      // Loop through entries to be split if we have any
      if ( count( $entries ) > 0 ) {
         foreach ( $entries as $row ) {

            // Update the entry being overwritten and trim the end of it
            $this->db->set( 'endDateTime', $data['startDateTime'] );
            // Get the new totalHours figure and set to update
            $totalHours = $this->_calculate_total_hours( $row->startDateTime, $data['startDateTime'] );
            $this->db->set( 'totalHours', $totalHours );
            // Update the entry
            $this->db->where( 'timesheetEntryId', $row->timesheetEntryId );
            $this->db->update( 'time_timesheet_entry' );

            // Now create a new entry with the same details, but it fills the time after
            // the entry that created the split
            $this->db->set( 'jobId', $row->jobId );
            $this->db->set( 'userId', $row->userId );
            $this->db->set( 'timesheetTaskId', $row->timesheetTaskId );
            $this->db->set( 'startDateTime', $data['endDateTime'] );
            $this->db->set( 'endDateTime', $row->endDateTime );
            // Get the totalHours figure and set to update
            $totalHours = $this->_calculate_total_hours( $data['endDateTime'], $row->endDateTime );
            $this->db->set( 'totalHours', $totalHours );
            $this->db->set( 'comment', $row->comment );
            $this->db->set( 'disbursement', $row->disbursement );
            $this->db->set( 'archived', $row->archived );
            $this->db->set( 'writtenOff', $row->writtenOff );
            $this->db->set( 'invoiced', $row->invoiced );
            $this->db->set( 'mockInvoiceId', $row->mockInvoiceId );
            $this->db->set( 'mockInvoiceRowId', $row->mockInvoiceRowId );
            $this->db->insert( 'time_timesheet_entry' );
         }
      }
   }


   /** ----------------------------------------------------------------------------
    * Calculates the hours between 2 date/time variables as a decimal number
    *
    * @param   string $startDateTime         The start date/time value
    * @param   string $endDateTime           The end date/time value
    * @return  double
    */
   private function _calculate_total_hours( $startDateTime, $endDateTime ) {

      $difference = strtotime( $endDateTime ) - strtotime( $startDateTime );
      $totalHours = $difference/3600;
      return $totalHours;
   }

}