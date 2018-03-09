<?php
/**
 * Mock Invoice Row Model
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Mockinvoice_row_model extends MY_Model {

   protected $_table_name  = "time_mockinvoice_row";
   protected $_order_by    = "mockInvoiceRowId";
   protected $_primary_key = "mockInvoiceRowId";

   function __construct(){
      parent::__construct();
   }

}