<?php
/**
 * Timesheet Settings Model
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Timesheet_settings_model extends MY_Model {

   protected $_table_name  = "time_timesheet_settings";
   protected $_order_by    = 'timesheetSettingsId';
   protected $_primary_key = "timesheetSettingsId";

   function __construct() {
      parent::__construct();
   }


   /** ----------------------------------------------------------------------------
   * Returns a timesheet settings row of data as an array
   *
   * @param   int    $timesheetSettingsId     The id of the timesheet settings row to get
   * @return  array
   */
   public function get_settings_array( $timesheetSettingsId ) {
      $this->db->where( 'timesheetSettingsId', $timesheetSettingsId );
      return $this->db->get( 'time_timesheet_settings' )->result_array();
   }

}