<?php
/**
 * Timesheet Group Model
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Timesheet_group_model extends MY_Model {

      protected $_table_name  = "time_timesheet_taskgroup";
      protected $_order_by    = 'timesheetTaskGroupId';
      protected $_primary_key = "timesheetTaskGroupId";
 
      function __construct() {
         parent::__construct();
      }

      /** ----------------------------------------------------------------------------
       * Gets a list of timesheet group data order by displayOrder
       * Return null hasTask if no task under that group
       *
       * @return  array of object
       */
      public function get_group_list() {
         $sql = " SELECT DISTINCT tg.*,tt.timesheetTaskGroupId AS hasTask
                  FROM time_timesheet_taskgroup AS tg 
                    LEFT JOIN time_timesheet_task AS tt ON tg.timesheetTaskGroupId = tt.timesheetTaskGroupId
                  ORDER BY tg.displayOrder ASC";
         return $this->db->query( $sql )->result();
      }

      /** ----------------------------------------------------------------------------
       * Gets the details for a single timesheet group and list of task entry under it
       *
       * @param   int   $timesheetTaskGroupId    The task group id
       * @return  array of object
       */
      public function get_group_task_list( $timesheetTaskGroupId ) {

         $sql ="  SELECT DISTINCT tt.*,ttg.timesheetTaskGroupId AS timesheetTaskGroupId,
                     ttg.groupName AS groupName,
                     ttg.groupColor AS groupColor,
                     tme.timesheetTaskId AS hasEntry
                  FROM time_timesheet_taskgroup AS ttg
                     LEFT JOIN time_timesheet_task AS tt ON ttg.timesheetTaskGroupId = tt.timesheetTaskGroupId
                     LEFT JOIN time_timesheet_entry AS tme ON tt.timesheetTaskId = tme.timesheetTaskId
                  WHERE ttg.timesheetTaskGroupId = '" . $timesheetTaskGroupId . "'
                  ORDER BY tt.displayOrder ASC";
         return $this->db->query( $sql );

      }

      /** ----------------------------------------------------------------------------
      * Get selected id for sorting order
      *
      * @param  int     $grouId       Group id
      * @return object                Group item id
      *          
      */
      public function get_display_id( $value, $groupId ) {

         $sql = " SELECT timesheetTaskGroupId 
                  FROM time_timesheet_taskgroup 
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

         $sql = " UPDATE time_timesheet_taskgroup 
                  SET displayOrder = '" . $value['end'] . "'  
                  WHERE displayOrder = '" . $value['start'] . "' 
                     AND timesheetTaskGroupId = '" . $groupId . "' ";
         $this->db->query( $sql );
      }

      /** ----------------------------------------------------------------------------
      * Rearrange Display order value after deleting item
      *
      * @param array $value          Contain timesheetTaskGroupId, Order value
      *          
      */
      public function update_order( $value ) {

         // Decrement 
         $operator      = '-';
         $endOperator   = '>';
         
         // Build the query
         $sql = " UPDATE time_timesheet_taskgroup
                  SET displayOrder = displayOrder " . $operator . " 1
                  WHERE ( displayOrder " . $endOperator . " '" . $value['displayOrder'] . "' ) ";
         $this->db->query( $sql );
      }

      /** ----------------------------------------------------------------------------
       * Check if group have task
       * Return null hasTask if no task under that group
       *
       * @return  object row
       */
      public function check_group_has_task( $timesheetTaskGroupId ) {
         $sql = " SELECT DISTINCT tt.timesheetTaskGroupId AS hasTask
                  FROM time_timesheet_taskgroup AS tg 
                    LEFT JOIN time_timesheet_task AS tt ON tg.timesheetTaskGroupId = tt.timesheetTaskGroupId
                  WHERE tg.timesheetTaskGroupId = '" . $timesheetTaskGroupId . "'";
         return $this->db->query( $sql )->row();
      }

}