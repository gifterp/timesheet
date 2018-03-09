<?php 
/**
 *  Model file that will handle the database connection and function of  contact section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Contact_model extends MY_Model { 

      protected $_table_name = "time_contactperson";
      protected $_order_by = 'contactPersonId';
      protected $_primary_key = "contactPersonId";
 
      function __construct(){
         parent::__construct();
      }

      /** ----------------------------------------------------------------------------
      * Get contact details with specific contact id 
      *
      * @param  int     $contactPersonId   Contact Id
      * @return object                     Contact details
      *          
      */
      public function get_contact_details( $contactPersonId ) {
         
         $sql = " SELECT * 
                  FROM time_contactperson 
                  WHERE contactPersonId =  '" . $contactPersonId . "' ";
         return $this->db->query( $sql )->row();
      }

}