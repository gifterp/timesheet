<?php 
/**
 *  Model file that will handle the database connection and function of  council section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Council_model extends MY_Model { 

      protected $_table_name = "time_council";
      protected $_order_by = 'councilId';
      protected $_primary_key = "councilId";
 
      function __construct(){
         parent::__construct();
      }

      /** ----------------------------------------------------------------------------
      * Get council details with specific council id  
      *
      * @param  int     $councilId    Council id
      * @return object                Council details
      *          
      */
      public function get_council_details( $councilId ) {
         
         $sql = " SELECT * 
                  FROM time_council 
                  WHERE councilId =  '" . $councilId . "' ";
         return $this->db->query( $sql )->row();
      }

}