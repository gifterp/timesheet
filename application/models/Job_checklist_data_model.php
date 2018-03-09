<?php
/**
 * Job Checklist Data Model
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

class Job_checklist_data_model extends MY_Model {

      protected $_table_name = "time_job_checklist_data";
      protected $_order_by = 'jobChecklistDataId';
      protected $_primary_key = "jobChecklistDataId";

      function __construct(){
         parent::__construct();
      }

      /** ----------------------------------------------------------------------------
      * Delete job checklist data with row for specific jobchecklist id 
      *
      * @param  int $jobChecklistId          Jobchecklist id
      *          
      */ 
      public function delete_job_checklist_data( $jobChecklistId ) {

         $sql = " DELETE
                  FROM time_job_checklist_data
                  WHERE jobChecklistId = '" . $jobChecklistId . "'";
         $this->db->query( $sql );
      }

      /** ----------------------------------------------------------------------------
       * Get all job checklist details and join data from checklist data, checklist item
       *
       * @param   int   $jobChecklistId      Job checklist id
       * @return  object                     Job checklist details
       */
      public  function get_checklist_data_details( $jobChecklistId ) {

         $sql = "SELECT jcd.jobChecklistDataId AS jobChecklistDataId,
                  jcd.dateData AS dateData,
                  jcd.checkboxData AS checkboxData,
                  jcd.textData AS textData,
                  cli.newRow AS newRow,
                  cli.fieldType AS fieldType,
                  cli.fieldTitle AS fieldTitle,
                  cli.fieldWidth AS fieldWidth
               FROM time_job_checklist AS jc
                  LEFT JOIN time_job_checklist_data AS jcd ON jc.jobChecklistId = jcd.jobChecklistId
                  LEFT JOIN time_checklist_item AS cli ON jcd.checklistItemId = cli.checklistItemId
               WHERE jc.jobChecklistId = '" . $jobChecklistId . "'
               ORDER BY cli.displayOrder ASC";
         return $this->db->query( $sql );
      }

      /** ----------------------------------------------------------------------------
      * Check if job checklist data exist with the checklistitemId 
      *
      * @param  int     $checklistItemId    Jobchecklist Id
      * @return array                       Checklist Item Id
      *          
      */
      public function check_item_list( $checklistItemId ) {
         
         $sql = " SELECT checklistItemId
                  FROM time_job_checklist_data
                  WHERE checklistItemId = '" . $checklistItemId . "'";
         return $this->db->query( $sql )->result_array();
      }

}