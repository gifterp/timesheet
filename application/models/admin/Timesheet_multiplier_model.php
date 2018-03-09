<?php 
/**
 *  Model file that will handle the database connection and function of  timesheet entry addition section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial 
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Timesheet_multiplier_model extends MY_Model { 

      protected $_table_name = "time_timesheet_multiplier";
      protected $_order_by = 'name';
      protected $_primary_key = "timesheetMultiplierId";
 
      function __construct(){
         parent::__construct();
      }

      /** ----------------------------------------------------------------------------
      * Gets timesheet entry addition details for the specific timesheet entry addition id
      *
      * @param  int     $timesheetMultiplierId     Timesheet Entry Additional id
      * @return object                             Timesheet Entry Additional Details
      *          
      */
      public function get_timesheet_entry_add_row( $timesheetMultiplierId ) {

         $sql = " SELECT * 
                  FROM $_table_name 
                  WHERE timesheetMultiplierId =  '" . $timesheetMultiplierId . "' ";
         return $this->db->query( $sql )->row();
      }

     

}