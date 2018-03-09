<?php
/**
 * Improved Timesheets Helper
 *
 * General functions that are used in our timesheets application
 *
 * @author      John Gifter C Poja <gifter@gmail.com>
 * @copyright   Copyright (c) 2016 Genesis Software. <https://genesis.com>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0


/** ----------------------------------------------------------------------------
 * Formats the task group and task name for output
 *
 * @param   string   $taskGroup
 * @param   string   $taskName
 * @return  string                  Task group and name formatted for output
 */
function format_task_name( $taskGroup, $taskName ) {

   return '[' . $taskGroup . '] ' . $taskName;
}


/** ----------------------------------------------------------------------------
 * Creates an associative array to be used in things such as the dropdown form helper
 *
 * Jobs need to be handled differently due to possibly having children.
 *
 * @param   array (of objects)    $array     Job details
 * @return  array                            Array of key => value pairs
 */
function create_job_key_value_array( $arrayToConvert ) {

   $array  = [];
   $jobRef = '';  // Used for comparison

   foreach ( $arrayToConvert as $row ) {
      if ( $row->childReferenceNo != null ) { // It's a child job

         // Print the parent job before the children
         if ( $jobRef != $row->jobReferenceNo ) {
            $array[ $row->jobId ] = format_job_ref_name( $row->jobReferenceNo, $row->jobName );
         }

         $array[ $row->childJobId ] = format_job_ref_name( $row->jobReferenceNo, $row->jobName, $row->childReferenceNo, $row->childJobName );
      } else { // We just print job normally
         $array[ $row->jobId ] = format_job_ref_name( $row->jobReferenceNo, $row->jobName );
      }
      $jobRef = $row->jobReferenceNo;
   }

   return $array;
}


/** ----------------------------------------------------------------------------
 * Constructs a job reference number in the correct format
 *
 * Handles a different format if the job is a child with a parent job
 *
 * @param   string   $jobReferenceNo      Job reference no
 * @param   string   $childReferenceNo    Child job reference no (if exists)
 * @return  string                        Properly formatted job reference number
 */
function format_job_ref( $jobReferenceNo, $childReferenceNo = null ) {

   $formattedRefNo = '';

   if ( $childReferenceNo != null ) { // It's a child job
      $formattedRefNo = $jobReferenceNo . " >> " . $childReferenceNo;
   } else { // We just print job normally
      $formattedRefNo = $jobReferenceNo;
   }

   return $formattedRefNo;
}


/** ----------------------------------------------------------------------------
 * Constructs a job reference and name in the correct format
 *
 * Handles a different format if the job is a child with a parent job
 *
 * @param   string   $jobReferenceNo      Job reference no
 * @param   string   $jobName             Job name
 * @param   string   $childReferenceNo    Child job reference no (if exists)
 * @param   string   $childJobName        Child jobname (if exists)
 * @return  string                        Properly formatted job reference and name
 */
function format_job_ref_name( $jobReferenceNo, $jobName, $childReferenceNo = null, $childJobName = null ) {

   $formattedRefName = '';

   if ( $childReferenceNo != null ) { // It's a child job
      $formattedRefName = $jobReferenceNo . " >> " . $childReferenceNo . " - " . $childJobName;
   } else { // We just print job normally
      $formattedRefName = $jobReferenceNo . " - " . $jobName;
   }

   return $formattedRefName;
}


/** ----------------------------------------------------------------------------
 * Prints out a series of <divs> for jobs any children they have
 *
 * There will be the list of job in search result
 *
 * @param  array $arrayItems        Array of objects with job details
 * @return void                     Prints <divs> to the screen
 */
function print_job_list_filter( $arrayItems ) {

   // Used for comparison
   $refNo = "";               // The reference no of the last job we printed

   if ( $arrayItems ) {
      foreach ( $arrayItems as $row ) {

         // Print the job if we have come to a new one
         // If a job has children, it will have rows for each. (LEFT JOIN) This is why we must check
         if ( $refNo != $row->jobReferenceNo ) {


            echo "<div class=\"filtered-list-link \" data-id=" . $row->jobId . ">\n"
               ."   " . $row->jobReferenceNo . " - " . $row->jobName . "\n"
               ."   <span class=\"entry-title\">" . $row->jobReferenceNo . " - " . $row->jobName . "</span>"
               ."<span class=\"entry-job-id\">" . $row->jobId . "</span>"
               ."<span class=\"entry-job-ref\">" . $row->jobReferenceNo . "</span>"
               ."<span class=\"entry-job-name\">" . $row->jobName . "</span>"
               ."</div>\n";

            $refNo = $row->jobReferenceNo;
         }
 
         // Print the children if a job has them
         if ( $row->childJobId != "" ) {

            echo "<div class=\"filtered-list-link  sub-link\" data-id=" . $row->childJobId . ">\n"
               ."   " . $row->childReferenceNo . " - " . $row->childJobName . "\n"
               ."   <span class=\"entry-title\">[" . $row->jobReferenceNo . "] >> " . $row->childReferenceNo . " - " . $row->childJobName . "</span>"
               ."<span class=\"entry-job-id\">" . $row->jobId . "</span>"
               ."<span class=\"entry-job-ref\">" . $row->jobReferenceNo . "</span>"
               ."<span class=\"entry-job-name\">" . $row->jobName . "</span>"
               ."<span class=\"entry-child-id\">" . $row->childJobId . "</span>"
               ."<span class=\"entry-child-ref\">" . $row->childReferenceNo . "</span>"
               ."<span class=\"entry-child-name\">" . $row->childJobName . "</span>"
               
               ."</div>\n";
         }

        
      }
   } 

}


/** ----------------------------------------------------------------------------
 * Calculates the total amount for a timesheet entry
 *
 * @param  double    $timeTaken        Amount of time in hours
 * @param  double    $chargeRate       Rate the user's time is charged at
 * @param  boolean   $chargeable       If this item is chargeable
 * @param  double    $disbursement     Amount of any disbursements to be added
 * @return double
 */
function calculate_entry_total( $timeTaken, $chargeRate, $chargeable, $disbursement ) {
   $total = 0;

   if ( $chargeable ) { $total = $timeTaken * $chargeRate; }
   $total += $disbursement;

   return $total;
}
?>