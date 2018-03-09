<?php
/**
 * System Settings Model
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class System_settings_model extends MY_Model {

   protected $_table_name = "time_system_settings";
   protected $_order_by = 'systemSettingsId';
   protected $_primary_key = "systemSettingsId";

   function __construct(){
      parent::__construct();
   } 


   
}