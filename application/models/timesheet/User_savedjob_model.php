<?php
/**
 * User Saved Job Model
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class User_savedjob_model extends MY_Model {

      protected $_table_name  = "time_user_savedjob";
      protected $_order_by    = "userSavedJobId";
      protected $_primary_key = "userSavedJobId";
 
      function __construct() {
         parent::__construct();
      }

}