<?php 
/**
 *  Model file that will handle the database connection and function of  job invoice section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Jobinvoice_model extends MY_Model { 

      protected $_table_name = "time_job_invoice";
      protected $_order_by = 'invoiceId';
      protected $_primary_key = "invoiceId";
 
      function __construct(){
         parent::__construct();
      }
 
      /** ----------------------------------------------------------------------------
      * Get a list of job invoice details for the specific job id
      *
      * @param  int     $jobId           Job id
      * @return object                   Job invoice details
      *          
      */
      public function get_job_invoice_list( $jobId ) {
         
         $sql = " SELECT * 
                  FROM time_job_invoice 
                  WHERE jobId = '" . $jobId . "'
                  ORDER BY invoiceDate DESC, invoiceId DESC";
         return $this->db->query( $sql );
      } 

}