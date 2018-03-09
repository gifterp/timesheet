<?php
/**
 * Mock Invoice Category Model
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Mockinvoice_category_model extends MY_Model {

   protected $_table_name = "time_mockinvoice_category";
   protected $_order_by = 'mockInvoiceCategoryId';
   protected $_primary_key = "mockInvoiceCategoryId";

   function __construct(){
      parent::__construct();
   } 

   /** ----------------------------------------------------------------------------
    * Gets mock invoice categories details for the specific mock invoice categories id
    *
    * @param  int   $mockInvoiceCategoryId     Mock invoice categories id
    * @return object                 			Mock invoice categories details
    *
    */
   public function get_mockinvoice_categories_row( $mockInvoiceCategoryId ) {

      $sql = " SELECT *
               FROM time_mockinvoice_category
               WHERE mockInvoiceCategoryId =  '" . $mockInvoiceCategoryId . "' ";
      return $this->db->query( $sql )->row();
   } 
}