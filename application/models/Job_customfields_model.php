<?php 
/**
 *  Model file that will handle the database connection and function of  job customfields
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Job_customfields_model extends MY_Model { 

      protected $_table_name = "time_job_customfields";
      protected $_order_by = 'jobCustomFieldsId';
      protected $_primary_key = "jobCustomFieldsId";
 
      function __construct(){
         parent::__construct();
      }


}