<?php
/**
 *  Model file that will handle the database connection and function of  job checklist section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Job_checklist_model extends MY_Model { 

      protected $_table_name = "time_job_checklist";
      protected $_order_by = 'jobChecklistId';
      protected $_primary_key = "jobChecklistId";
 
      function __construct(){
         parent::__construct();
      }

 
      /** ----------------------------------------------------------------------------
      * Get a list of job checklist for the specific job id
      *
      * @param  int     $jobId      Job id
      * @return object              Job checklist details
      */
      public function get_job_checklist_details( $jobId ) {

         $sql = "SELECT jc.checklistId AS checklistId,
                     jc.jobChecklistId AS jobChecklistId,
                     jc.displayOrder AS displayOrder,
                     jc.jobId AS jobId,
                     c.title AS title
                  FROM time_job_checklist jc
                     LEFT JOIN time_checklist c ON jc.checklistId = c.checklistId 
                  WHERE jc.jobId =  '" . $jobId . "'
                  ORDER BY jc.displayOrder ASC";
         return $this->db->query( $sql );
      }


      /** ----------------------------------------------------------------------------
      * Get all checklists and their details for a job
      *
      * @param  int     $jobId      Job id
      * @return array               Checklist details
      */
      public function get_all_checklist_details( $jobId ) {

         $sql = " SELECT DISTINCT jcd.jobChecklistDataId AS id,
                     jc.jobChecklistId AS jobChecklistId,
                     jc.displayOrder AS jobChecklistDisplayOrder,
                     ci.displayOrder AS checklistItemDisplayOrder,
                     c.title AS title,
                     c.checklistId AS checklistId,
                     ci.newRow AS newRow,
                     ci.fieldWidth AS fieldWidth,
                     ci.fieldType AS fieldType,
                     ci.fieldTitle AS fieldTitle,
                     jcd.dateData AS dateData,
                     jcd.textData AS textData,
                     jcd.checkboxData AS checkboxData
                  FROM time_job_checklist AS jc
                     INNER JOIN time_checklist AS c ON jc.checkListId = c.checklistId
                     LEFT JOIN time_checklist_item AS ci ON c.checklistId = ci.checklistId
                     INNER JOIN time_job_checklist_data AS jcd ON ci.checklistItemId = jcd.checklistItemId
                  WHERE jc.jobChecklistId = jcd.jobChecklistId
                     AND jc.jobId = '" . $jobId . "'
                  ORDER BY jc.displayOrder, ci.displayOrder";
         return $this->db->query( $sql )->result_array();
      }


      /** ----------------------------------------------------------------------------
      * Get selected id for sorting order
      *
      * @param  int     $jobId        Job id
      * @return object                Job checklist id
      */
      public function get_display_id( $value, $jobId ) {

         $sql = " SELECT jobChecklistId 
                  FROM time_job_checklist 
                  WHERE jobId = '" . $jobId . "'
                     AND displayOrder = '" . $value['start'] . "' ";
         return $this->db->query( $sql )->row();
      }


      /** ----------------------------------------------------------------------------
      * Swith display Order according to sort
      *
      * @param  int     $jobId         Job id
      * @param  array   $value         Contain start and end value of display order
      */
      public function change_display_order( $value, $jobId ) {

         $sql = " UPDATE time_job_checklist 
                  SET displayOrder = '" . $value['end'] . "'  
                  WHERE displayOrder = '" . $value['start'] . "' 
                     AND jobId = '" . $jobId . "' ";
         $this->db->query( $sql );
      }


      /** ----------------------------------------------------------------------------
      * Check if checklist exist in job checklist
      *
      * @param  int     $checklistId     Checklist id
      * @return object                   Job checklist id
      */
      public function check_checklist ( $checklistId ) {
         
         $sql = " SELECT jobChecklistId 
                  FROM time_job_checklist 
                  WHERE checklistId = '" . $checklistId . "' ";
         return $this->db->query( $sql )->result();
      }


      /** ----------------------------------------------------------------------------
      * Rearrange Display order value after deleting item
      *
      * @param array $value          Contain jobId, jobChecklistid, Order value
      */
      public function update_order( $value ) {

         // Decrement 
         $operator      = '-';
         $endOperator   = '>';
         
         // Build the query
         $sql = " UPDATE time_job_checklist
                  SET displayOrder = displayOrder " . $operator . " 1
                  WHERE ( displayOrder " . $endOperator . " '" . $value['order'] . "'
                     AND jobId = '" . $value['jobId'] . "'
                     AND jobChecklistId != '" . $value['jobChecklistId'] . "' ) ";
         $this->db->query( $sql );
      }

}