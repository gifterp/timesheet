<?php
/**
 * Timesheet Task Model
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Timesheet_task_model extends MY_Model {

      protected $_table_name  = "time_timesheet_task";
      protected $_order_by    = 'timesheetTaskId';
      protected $_primary_key = "timesheetTaskId";
 
      function __construct() {
         parent::__construct();
      }

      /** ----------------------------------------------------------------------------
       * Get checklist details having specific timesheetTaskId
       *
       * @param   int   $timesheetTaskId  Tasks Id
       * @return  object                  Tasks details
       */ 
      public function get_tasks_details( $timesheetTaskId ) {

         $sql ="  SELECT * 
                  FROM time_timesheet_task 
                  WHERE timesheetTaskId = '" . $timesheetTaskId . "' ";
         return $this->db->query( $sql )->row();

      }


      /** ----------------------------------------------------------------------------
      * Get selected id for sorting order
      *
      * @param  int     $grouId       Group id
      * @return object                Group item id
      *          
      */
      public function get_display_id( $value, $groupId ) {

         $sql = " SELECT timesheetTaskId 
                  FROM time_timesheet_task 
                  WHERE timesheetTaskGroupId = '" . $groupId . "'  
                     AND displayOrder = '" . $value['start'] . "' ";
         return $this->db->query( $sql )->row();
      }


      /** ----------------------------------------------------------------------------
      * Swith display Order according to sort
      *
      * @param  int     $groupId       Group id
      * @param  array   $value         Contain start and end value of display order
      *          
      */
      public function change_display_order( $value, $groupId ) {

         $sql = " UPDATE time_timesheet_task 
                  SET displayOrder = '" . $value['end'] . "'  
                  WHERE displayOrder = '" . $value['start'] . "' 
                     AND timesheetTaskGroupId = '" . $groupId . "' ";
         $this->db->query( $sql );
      }

      /** ----------------------------------------------------------------------------
      * Rearrange Display order value after deleting item
      *
      * @param array $value          Contain timesheetTaskId, Order value
      *          
      */
      public function update_order( $value ) {

         // Decrement 
         $operator      = '-';
         $endOperator   = '>';
         
         // Build the query
         $sql = " UPDATE time_timesheet_task
                  SET displayOrder = displayOrder " . $operator . " 1
                  WHERE ( displayOrder " . $endOperator . " '" . $value['displayOrder'] . "'
                     AND timesheetTaskGroupId = '" . $value['timesheetTaskGroupId'] . "'
                     AND timesheetTaskId != '" . $value['timesheetTaskId'] . "' ) ";
         $this->db->query( $sql );
      }

      /** ----------------------------------------------------------------------------
       * Check if task is in the timesheet entry
       * Return null hasEntry if no task under that timesheet entry
       *
       * @return  object row
       */
      public function check_task_has_entry( $timesheetTaskId ) {
         $sql = " SELECT DISTINCT tme.timesheetTaskId AS hasEntry
                  FROM  time_timesheet_task AS tt 
                     LEFT JOIN time_timesheet_entry AS tme ON tt.timesheetTaskId = tme.timesheetTaskId
                  WHERE tt.timesheetTaskId = '" . $timesheetTaskId . "' ";
         return $this->db->query( $sql )->row();
      }

}