<?php
/**
 * Mock Invoice Description Model
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Mockinvoice_description_model extends MY_Model {

   protected $_table_name = "time_mockinvoice_description";
   protected $_order_by = 'mockInvoiceDescriptionId';
   protected $_primary_key = "mockInvoiceDescriptionId";

   function __construct(){
      parent::__construct();
   } 

   /** ----------------------------------------------------------------------------
      * Gets mockinvoice description details for the specific mockinvoice description id
      *
      * @param  int   $mockInvoiceDescriptionId     Mock invoice description id
      * @return object                 			Mocki nvoice description details
      *          
      */
      public function get_mockinvoice_description_row( $mockInvoiceDescriptionId ) {
         
         $sql = " SELECT * 
                  FROM time_mockinvoice_description 
                  WHERE mockInvoiceDescriptionId =  '" . $mockInvoiceDescriptionId . "' ";
         return $this->db->query( $sql )->row();
      }

   
}