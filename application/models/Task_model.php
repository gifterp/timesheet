<?php
/**
 * Task Model
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Task_model extends MY_Model {

      protected $_table_name  = "time_timesheet_task";
      protected $_order_by    = 'displayOrder';
      protected $_primary_key = "timesheetTaskId";
 
      function __construct() {
         parent::__construct();
      }


      /** ----------------------------------------------------------------------------
      * Gets a list of tasks with the basic details
      *
      * We only include tasks that are active in the system
      *
      * @return array (of objects)           Task details
      */
      public function get_list() {

         $sql = "SELECT timesheetTaskId, taskName, groupName
               FROM time_timesheet_task AS tt
                  INNER JOIN time_timesheet_taskgroup AS ttg ON tt.timesheetTaskGroupId = ttg.timesheetTaskGroupId
               WHERE active
               ORDER BY ttg.displayOrder, tt.displayOrder";
         $query = $this->db->query( $sql );

         return $query->result();
      }

}