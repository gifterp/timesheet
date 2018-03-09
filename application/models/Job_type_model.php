<?php 
/**
 *  Model file that will handle the database connection and function of  job type section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Job_type_model extends MY_Model { 

      protected $_table_name = "time_jobtype";
      protected $_order_by = 'jobTypeId';
      protected $_primary_key = "jobTypeId";
 
      function __construct(){
         parent::__construct();
      }

      /** ----------------------------------------------------------------------------
      * Gets job type details for the specific job type id
      *
      * @param  int   $jobTypeId       Job type id
      * @return object                 Job type details
      *          
      */
      public function get_job_type_row( $jobTypeId ) {
         
         $sql = " SELECT * 
                  FROM time_jobtype 
                  WHERE jobTypeId =  '" . $jobTypeId . "' ";
         return $this->db->query( $sql )->row();
      }

}