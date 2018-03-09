<?php
/**
 *  Model file that will handle the database connection and function of  checlist item section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Checklistitem_model extends MY_Model {

   protected $_table_name = "time_checklist_item";
   protected $_order_by = 'checklistItemId';
   protected $_primary_key = "checklistItemId";

   function __construct(){
      parent::__construct();
   }

   /** ----------------------------------------------------------------------------
    * Get a list of checklist item details having specific checklist checklistId
    *
    * @param   int   $checklistId   Checklist id
    * @return  object               Checklistitem details
    */
   public function get_checklistitem_details( $checklistId ) {

      $sql = " SELECT * 
               FROM time_checklist_item 
               WHERE checklistId = '" . $checklistId . "' 
               ORDER BY displayOrder ASC ";
         return $this->db->query( $sql );
   }


   /** ----------------------------------------------------------------------------
   * Get all items of a single checklist
   *
   * @param  int     $checklistId   Checklist id
   * @return array                  Checklist details
   */
   public function get_all_checklist_details( $checklistId ) {

      $sql = " SELECT *,
                  1 AS jobChecklistDisplayOrder,
                  displayOrder AS checklistItemDisplayOrder,
                  checklistItemId AS id,
                  null AS dateData,
                  0 AS checkboxData,
                  '' AS textData
               FROM time_checklist AS c
                  LEFT JOIN time_checklist_item AS ci ON c.checklistId = ci.checklistId
               WHERE c.checklistId = '" . $checklistId . "'
               ORDER BY displayOrder";
      return $this->db->query( $sql )->result_array();
   }

 

   /** ----------------------------------------------------------------------------
    * Get selected id for sorting order
    *
    * @param  int   $checklistId    Checklist id
    * @return object                Checklist item id
    *          
    */
   public function get_display_id( $value, $checklistId ) {

      $sql = " SELECT checklistItemId AS checklistItemId
               FROM time_checklist_item 
               WHERE checklistId = '" . $checklistId . "'
                  AND displayOrder = '" . $value['start'] . "'";
      return $this->db->query( $sql )->row();
   }

   /** ----------------------------------------------------------------------------
    * Swith display Order according to sort
    *
    * @param  int     $checklistId   ChecklistId id
    * @param  array   $value         Contain start and end value of display Order
    *          
    */
   public function change_display_order( $value, $checklistId ) {

      $sql = " UPDATE time_checklist_item 
               SET displayOrder = '" . $value['end'] . "'  
               WHERE displayOrder = '" . $value['start'] . "' 
                  AND checklistId = '" . $checklistId . "' ";
      $this->db->query( $sql );
   }

   /** ----------------------------------------------------------------------------
    * Delete checklist item and remove it when exist in checklist job section
    *
    * @param   int   $checklistId      Checklist id
    * @param   int   $arg              Determines if query have joins
    */
   public function delete_checklist_item( $checklistItemId, $arg ) {

      $columnTable   = "";
      $as            = "";
      $join          = "";
      $where         = "";

      // Set JOIN and ALIAS when checklist exist in job checklist data
      if ( $arg != 0 ) {
         $columnTable   = "ci, jcd";
         $as            = "AS ci ";
         $join          = "JOIN time_job_checklist_data AS jcd ON ci.checklistItemId = jcd.checklistItemId";
         $where         =  "ci.";
      }

      // Build Query
      $sql = ' DELETE ' . $columnTable . '
                  FROM time_checklist_item ' . $as . '
                  ' . $join . '         
                  WHERE ' . $where . 'checklistItemId =  "' . $checklistItemId . '" '; 
      $this->db->query( $sql );           
   }

   /** ----------------------------------------------------------------------------
    * Rearrange Display order value after deleting item
    *
    * @param   array   $value      Contain checklistId, checklistItemId, Order value
    */
   public function update_order( $value ) {

         // Decrement 
         $operator      = '-';
         $endOperator   = '>';
         
         // Build the query
         // Decrement all value of display order that is greater than to the row deleted
         $sql = " UPDATE time_checklist_item
                  SET displayOrder = displayOrder " . $operator . " 1
                  WHERE (displayOrder " . $endOperator . " '" . $value['order'] . "'
                     AND checklistId = '" . $value['checklistId'] . "'
                     AND checklistItemId != '" . $value['checklistItemId'] . "') ";
         $this->db->query( $sql );
   }

}