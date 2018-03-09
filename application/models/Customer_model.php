<?php 
/**
 *  Model file that will handle the database connection and function of  customer section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Customer_model extends MY_Model {

      protected $_table_name = "time_customer";
      protected $_order_by = 'customerId';
      protected $_primary_key = "customerId";
 
      function __construct(){
         parent::__construct();
      }

      /** ----------------------------------------------------------------------------
      * Get customer details for specific customer id
      *
      * @param  int     $customerId    Customer Id
      * @return object                 Customer details
      *          
      */
      public function get_customer_details( $customerId ) {

         $sql = " SELECT c.name AS customerName,
                     c.address1 AS customerAddress,
                     c.city AS customerCity,
                     c.region AS customerRegion,
                     c.postCode AS customerPostCode,
                     c.phone AS customerPhone,
                     c.email AS customerEmail,
                     c.customerType AS customerType
                  FROM time_customer c
                  WHERE c.customerId =  '" . $customerId . "' ";
         return $this->db->query( $sql )->row();
      }

}