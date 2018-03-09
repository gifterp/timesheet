<?php
/**
 * Timesheet Functions
 *
 * Functions used by the timesheet diary page
 * Mainly to print out <select> options and draggable lists on the diary page
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */




/** ----------------------------------------------------------------------------
 * Prints out the <select> dropdown <options> for jobs and child jobs
 *
 * The option values are printed as a comma separated list (|#| = separator) as below:
 * jobId|#|childJobId
 *
 * Any child jobs are printed in an option group <optgroup> with a label
 *
 * @param  array $arrayOption   Array of objects with job details
 * @return void                 Prints <options> to the screen
 */
function print_job_options( $arrayOption ) {

   // Used for comparison
   $refNo       = "";           // The reference no of the last job we printed
   $flagNewJob  = false;        // True if we printed a job in the current loop iteration
   $flagChild   = false;        // True if we have printed a child for the job

   foreach ( $arrayOption as $row ) {

      // Print the job if we have come to a new one
      // If a job has children, it will have rows for each. (LEFT JOIN) This is why we must check
      if ( $refNo != $row->jobReferenceNo ) {

         // Close the option group and reset flag as the last job had children
         if ( $flagChild ) {
            echo "</optgroup>\n";
            $flagChild = false;
         }

         echo "<option value=\"" . $row->jobId . "|#|\">" . $row->jobReferenceNo . " - " . $row->jobName . "</option>\n";

         $refNo = $row->jobReferenceNo;
         $flagNewJob  = true;
      }

      // Print the children if a job has them
      if ( $row->childJobId != "" ) {

         // Start an option group for the first child of a job
         if ( $flagNewJob ) {
            echo "<optgroup label=\"" . $row->jobReferenceNo . " " . lang( 'job_title_children' ) . "\">\n";
         }

         echo "   <option value=\"" . $row->jobId . "|#|" . $row->childJobId . "\">-- " . $row->childReferenceNo . " - " . $row->childJobName . "</option>\n";

         $flagChild  = true;
         $flagNewJob = false;
      }
   }

   // If the last loop iteration was for a child, we need to close the option group
   if ( $flagChild ) { echo "</optgroup>\n"; }

}




/** ----------------------------------------------------------------------------
 * Prints out the <select> dropdown <options> for tasks
 *
 * We group the tasks in an option group <optgroup> using the group name as the label
 *
 * @param  array $arrayOption   Array of objects with task details
 * @return void                 Prints <options> to the screen
 */
function print_task_options( $arrayOption ) {

   // Used for comparison
   $groupName   = "";           // The name of the last task group we printed
   $printedTask = false;        // True if we have printed a task

   foreach ( $arrayOption as $row ) {

      // Check if it's a new task group
      if ( $groupName != $row->groupName ) {

         // Close the last task option group if we had one
         if ( $printedTask ) { echo "</optgroup>\n"; }

         // Start a new task group
         echo "<optgroup label=\"" . $row->groupName . "\">\n";

      }

      // Print the task options
      echo "   <option value=\"" . $row->timesheetTaskId . "\">" . $row->taskName . "</option>\n";

      $groupName = $row->groupName;
      $printedTask  = true;

   }

   // If we have printed any tasks, we need to close the last option group
   if ( $printedTask ) { echo "</optgroup>\n"; }

}




/** ----------------------------------------------------------------------------
 * Prints out a series of <divs> for jobs any children they have
 *
 * These become draggable items that can be dropped onto the fullCalendar area to
 * create new entries
 *
 * @param  array $arrayItems        Array of objects with job details
 * @param  bool  $isPersonalList    Denotes if this draggable list is the personal job list
 * @param  bool  $advanceSearch     Detect if call from advance search
 * @return void                     Prints <divs> to the screen
 */
function print_job_draggables( $arrayItems, $isPersonalList , $advanceSearch = False) {

   // Used for comparison
   $refNo = "";               // The reference no of the last job we printed
   if ( $arrayItems ) {
      foreach ( $arrayItems as $row ) {

         // Print the job if we have come to a new one
         // If a job has children, it will have rows for each. (LEFT JOIN) This is why we must check
         if ( $refNo != $row->jobReferenceNo ) {

       

            echo "<div class=\"draggable-event event \">\n"
               ."   " . $row->jobReferenceNo . " - " . $row->jobName . "\n"
               ."   <span class=\"entry-title\">" . $row->jobReferenceNo . " - " . $row->jobName . "</span>"
               ."<span class=\"entry-job-id\">" . $row->jobId . "</span>"
               ."<span class=\"entry-job-ref\">" . $row->jobReferenceNo . "</span>"
               ."<span class=\"entry-job-name\">" . $row->jobName . "</span>"
               ."<span class=\"entry-type\">job</span>\n"
               ."</div>\n";

            $refNo = $row->jobReferenceNo;
         }

         // Print the children if a job has them
         if ( $row->childJobId != "" ) {

            echo "<div class=\"draggable-event sub-event\">\n"
               ."   " . $row->childReferenceNo . " - " . $row->childJobName . "\n"
               ."   <span class=\"entry-title\">[" . $row->jobReferenceNo . "] >> " . $row->childReferenceNo . " - " . $row->childJobName . "</span>"
               ."<span class=\"entry-job-id\">" . $row->jobId . "</span>"
               ."<span class=\"entry-job-ref\">" . $row->jobReferenceNo . "</span>"
               ."<span class=\"entry-job-name\">" . $row->jobName . "</span>"
               ."<span class=\"entry-child-id\">" . $row->childJobId . "</span>"
               ."<span class=\"entry-child-ref\">" . $row->childReferenceNo . "</span>"
               ."<span class=\"entry-child-name\">" . $row->childJobName . "</span>"
               ."<span class=\"entry-type\">job</span>\n"
               ."</div>\n";
         }
      }
   } else {
      if ( $isPersonalList ) {  // Print note about dragging jobs to personal list if empty
         echo '<div class="add-personal-job-helper"><i class="fa fa-plus fa-lg mr-sm"></i><span class="panel-subtitle">Drag jobs here to add them</span></div>';
      } else {
         // Display a hidden blank template so list.js filter plugin doesn't give an error
         echo "<div class=\"draggable-event event hidden\"></div>\n";
      }
   }

}




/** ----------------------------------------------------------------------------
 * Prints out a series of <divs> for the tasks in their respective groups
 *
 * These become draggable items that can be dropped onto the fullCalendar area to
 * create new entries
 *
 * @param  array $arrayItems    Array of objects with task details
 * @return void                 Prints <divs> to the screen
 */
function print_task_draggables( $arrayItems ) {

   // Used for comparison
   $groupName = "";

   // Group task variables (Need to store information for filtering - All tasks in hidden span for group name)
   $groupTasks = '';
   $groupDivs  = '';

   if ( $arrayItems ) {
      foreach ( $arrayItems as $row ) {

         if ( ( $groupName != $row->groupName ) && ( $groupName != '' ) ) {
            // Print option group for task groups
            echo "<div class=\"non-draggable-title\">\n"
               ."   " . $groupName . "\n"
               ."   <span class=\"entry-task-name\">" . $groupTasks . "</span>\n"
               ."</div>\n";
            // Print task group divs
            echo $groupDivs;

            // Reset after printing
            $groupTasks = '';
            $groupDivs  = '';
         }

         $groupDivs .= "<div class=\"draggable-event sub-event\">\n"
               ."   " . $row->taskName . "\n"
               ."   <span class=\"entry-title\">Adding entry for " . $row->taskName . "</span>"
               ."<span class=\"entry-task-id\">" . $row->timesheetTaskId . "</span>"
               ."<span class=\"entry-task-name\">" . $row->taskName . "</span>"
               ."<span class=\"entry-type\">task</span>\n"
               ."</div>\n";

         $groupTasks .= $row->taskName . "|";
         $groupName  = $row->groupName;

      }


      // Print the last group of tasks if there were some
      if ( $groupName != '' ) {
         // Print option group for task groups
         echo "<div class=\"non-draggable-title\">\n"
            ."   " . $groupName . "\n"
            ."   <span class=\"entry-task-name\">" . $groupTasks . "</span>\n"
            ."</div>\n";

         // Print task group divs
         echo $groupDivs;
      }

   } else {
      // Display a hidden blank template so list.js filter plugin doesn't give an error
      echo "<div class=\"draggable-event event hidden\"></div>\n";
   }

}




/** ----------------------------------------------------------------------------
 * Prints out a JavaScript object that contains the settings for fullCalendar and
 * also any language variables we need to output in the JavaScript code
 *
 * @param  array $arraySettings     Array of objects with the timesheet settings
 * @return void                     Prints a JavaScript object
 */
function print_timesheet_settings( $arraySettings ) {

   // Print the JS object - Maintains correct indenting
   echo 'var TS = {
            multiDaySelectDisabled: ' . ( $arraySettings->multiDaySelectDisabled ? "true" : "false" ) . ',
            timeFormat: "' . $arraySettings->timeFormat . '",
            businessHoursStart: "' . $arraySettings->businessHoursStart . '",
            businessHoursEnd: "' . $arraySettings->businessHoursEnd . '",
            businessDaysOfWeek: [ ' . $arraySettings->businessDaysOfWeek . ' ],
            slotDuration: "' . $arraySettings->slotDuration . '",
            slotLabelInterval: "' . $arraySettings->slotLabelInterval . '",
            defaultView: "' . $arraySettings->defaultView . '",
            scrollTime: "' . $arraySettings->scrollTime . '",
            dayViewColumnFormat: "' . $arraySettings->dayViewColumnFormat . '",
            weekViewColumnFormat: "' . $arraySettings->weekViewColumnFormat . '",
            rootRelativePath: "' . ROOT_RELATIVE_PATH . '",
            notifEntryFor: "' . lang('timesheet_notif_entry_for') . '",
            notifAddSuccess: "' . lang('timesheet_notif_add_success') . '",
            notifAddError: "' . lang('timesheet_notif_add_error') . '",
            notifEditSuccess: "' . lang('timesheet_notif_edit_success') . '",
            notifEditError: "' . lang('timesheet_notif_edit_error') . '",
            notifDeleteSuccess: "' . lang('timesheet_notif_delete_success') . '",
            notifDeleteError: "' . lang('timesheet_notif_delete_error') . '",
            notifCloneSuccess: "' . lang('timesheet_notif_clone_success') . '",
            notifCloneError: "' . lang('timesheet_notif_clone_error') . '",
            notifUpdateTimeSuccess: "' . lang('timesheet_notif_update_time_success') . '",
            notifUpdateTimeError: "' . lang('timesheet_notif_update_time_error') . '",
            notifNoMultiDay: "' . lang('timesheet_notif_no_multi_day') . '",
            notifEndTimeInvalid: "' . lang('timesheet_notif_invalid_end_time') . '",
            notifSuccessTitle: "' . lang('system_notif_success') . '",
            notifErrorTitle: "' . lang('system_notif_error') . '",
            notifNotAllowedTitle: "' . lang('system_notif_not_allowed') . '"
         }';

}
?>