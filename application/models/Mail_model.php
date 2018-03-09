<?php 
/**
 *  Model file that will handle the database connection and function of  mail section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial 
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Mail_model extends MY_Model { 

      protected $_table_name = "time_mail";
      protected $_order_by = 'sendDate';
      protected $_primary_key = "mailId";
 
      function __construct(){
         parent::__construct();
      }

      /** ----------------------------------------------------------------------------
      * Gets mail details for the specific mail id
      *
      * @param  int     $mailId     Mail id
      * @return object              Mail Details
      *          
      */
      public function get_mail_row( $mailId ) {

         $sql = " SELECT * 
                  FROM time_mail 
                  WHERE mailId =  '" . $mailId . "' ";
         return $this->db->query( $sql )->row();
      }

      /** ----------------------------------------------------------------------------
      * Get a list of mail with all details
      *
      * @param  int     $id                Mail id
      * @return array   (of objects)       Mail Details
      *          
      */
      public function get_mail_list() {
         
         $sql = " SELECT * 
                  FROM time_mail 
                  ORDER BY sendDate desc,mailId desc ";
         return $this->db->query( $sql )->result();
      }

}