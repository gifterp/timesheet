<?php 
/**
 *  Model file that will handle the database connection and function of  job notes under job  section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Jobnote_model extends MY_Model { 

      protected $_table_name = "time_jobnote";
      protected $_order_by = 'jobNoteId';
      protected $_primary_key = "jobNoteId";
 
      function __construct(){
         parent::__construct();
      }
 
      /** ----------------------------------------------------------------------------
      * Get a list of job note details for the specific job id
      *
      * @param  int     $jobId         Job id
      * @return object                 Job note details
      *          
      */
      public function get_job_note_list( $jobId ){

         $sql = " SELECT * 
                  FROM time_jobnote AS n
                     LEFT JOIN time_user AS u ON n.userId = u.userId 
                  WHERE n.jobId = '" . $jobId . "'
                  ORDER BY n.noteDate DESC,n.jobNoteId DESC ";
         return $this->db->query( $sql );
      }
      
}