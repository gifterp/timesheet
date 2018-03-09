<?php
/**
 * Checklist Model
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

class Checklist_model extends MY_Model {

      protected $_table_name = "time_checklist";
      protected $_order_by = 'checklistId';
      protected $_primary_key = "checklistId";

      function __construct(){
         parent::__construct();
      }


      /** ----------------------------------------------------------------------------
       * Get checklist details having specific checklistId
       *
       * @param   int   $checklistId  Checklist Id
       * @return  object              Checklist details
       */   
      public function get_checklist_details( $checklistId ) {

         $sql = " SELECT * 
                  FROM time_checklist 
                  WHERE checklistId = '" . $checklistId . "'";
         return $this->db->query( $sql )->row();
      }

      /** ----------------------------------------------------------------------------
       * Gets checklists that have not already been attached to a job
       *
       * @param   int   $jobId         Job id
       * @return  object               Checklist Details
       */
      public function get_unused_checklist( $jobId ) {

         // Get checklists except those attached to the job
         $sql = " SELECT *
                  FROM time_checklist
                  WHERE checklistId
                    IN
                       (  SELECT checklistId
                          FROM time_checklist_item )
                    AND checklistId NOT IN
                       (  SELECT checklistId
                          FROM time_job_checklist
                          WHERE jobId = '" . $jobId . "' )";
         return $this->db->query( $sql );
      }

}