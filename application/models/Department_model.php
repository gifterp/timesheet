<?php 
/**
 *  Model file that will handle the database connection and function of  department section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Department_model extends MY_Model { 

      protected $_table_name = "time_department";
      protected $_order_by = 'departmentId';
      protected $_primary_key = "departmentId";
 
      function __construct(){
         parent::__construct();
      }

      /** ----------------------------------------------------------------------------
      * Get department 
      *
      * @param  int   $departmentId      Department Id
      * @return object                   Department details
      *          
      */
      public function get_department_details( $departmentId ) {

         $sql = " SELECT * 
                  FROM time_department 
                  WHERE departmentId =  '" . $departmentId . "' ";
         return $this->db->query( $sql );
      }

}