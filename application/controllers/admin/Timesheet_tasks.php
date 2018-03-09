<?php
/**
 * Administration - Timesheet Manage Task Controller
 *
 * Allows the Manage task for the timesheets to be modified
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Timesheet_tasks extends Admin_controller {


   public function __construct() {
      parent::__construct();
      $this->load->model('timesheet/timesheet_group_model');
      $this->load->model('timesheet/timesheet_task_model');
      $this->lang->load('timesheet', 'english');
      $this->load->library( 'json_library' );
   }


   /** ----------------------------------------------------------------------------
    * Displays the timesheet task management page 
    */
   public function index() {

      $this->data['meta_title']         = lang( 'timesheet_adm_task_name' );
      $this->data['page_title']         = lang( 'timesheet_adm_task_name' );
      $this->data['breadcrumb_items']   = array( lang( 'timesheet_adm_task_sect_title' ) );

      // Page specific JavaScript and CSS
      $this->data['pageVendorJS']       = $this->load->view( 'timesheet/admin/task/_vendor-js', '', true );
      $this->data['pageCustomJS']       = $this->load->view( 'timesheet/admin/task/_custom-js', '', true );
      $this->data['pageVendorCss']      = $this->load->view( 'timesheet/admin/task/_vendor-css', '', true );
      $this->data['pageCustomCss']      = $this->load->view( 'timesheet/_timesheet-custom-css', '', true );


      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'timesheet/admin/task/index', $this->data );
      $this->load->view( 'timesheet/admin/task/_timesheet-task-modal' );
      $this->load->view( 'timesheet/admin/task/_timesheet-group-modal' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );

   }

   /** ----------------------------------------------------------------------------
    * Return JSON group list structure in html
    *
    * @return     json                   
    *          
    */
   public function get_group_list() {

      // Get query array of object with timesheet group items
      $timesheetGroupQuery = $this->timesheet_group_model->get_group_list();
      
      $newLine    = "\n";
      $groupHtml  = '<ul class="list-unstyled sortable">';
      foreach ( $timesheetGroupQuery as $groupRow ) {
         // Creates Group Structure list
         $groupHtml  .= '<li id="' . $groupRow->timesheetTaskGroupId . '" class="handle-task cursor" data-type="Group" data-order="' . $groupRow->displayOrder . '" 
                        data-url="' . ROOT_RELATIVE_PATH . 'admin/timesheet-tasks/"  data-error="' . lang( 'timesheet_adm_notif_sort_error' ) . '" data-error-notif="' . lang( 'system_notif_error' ) . '" data-success-notif="' . lang( 'system_notif_success' ) . '">' . $newLine
                     .'<div class="row">' . $newLine
                     .'    <div class="col-md-12">' . $newLine 
                     .'       <div class="alert alert-default p-xs pr-sm pl-sm mb-xs group-head group-head' . $groupRow->timesheetTaskGroupId . '" style="background-color:'. $groupRow->groupColor .' !important;">'. $newLine
                     .'          <div class="col-md-8">' . $newLine
                     .'             <strong class="group-name' . $groupRow->timesheetTaskGroupId . ' white-font" >' . $groupRow->groupName . '</strong>' . $newLine
                     .'             <div class="form-group group-title' . $groupRow->timesheetTaskGroupId . ' hidden pull-left stop-handle-task-trigger" >' . $newLine
                     .'                <div class="col-sm-12">' . $newLine
                     .'                   <input type="text " name="name" id="group-name' . $groupRow->timesheetTaskGroupId . '" maxlength="50" class="form-control input-group-name" value="' . $groupRow->groupName . '"  placeholder="Group Name" />' . $newLine
                     .'                </div>' . $newLine
                     .'             </div>' . $newLine
                     .'             <div class="input-group  color-div' . $groupRow->timesheetTaskGroupId . ' hidden stop-handle-task-trigger pull-left color-picker-head " data-plugin-colorpicker >'. $newLine
                     .'                <span class="input-group-addon color-picker-icon"><i></i></span>'. $newLine
                     .'                <input type="text" class="form-control input-color shade' . $groupRow->timesheetTaskGroupId . ' color-input color-picker-input" value="' . $groupRow->groupColor . '" >'. $newLine
                     .'             </div>'. $newLine
                     .'          </div>'. $newLine
                     .'          <div class="col-md-4 text-right">'. $newLine
                     .'             <a class="cursor mt-xs mr-xs">'. $newLine
                     .'                <i class="fa fa-pencil handle-edit save' . $groupRow->timesheetTaskGroupId . ' white-font" data-id="' . $groupRow->timesheetTaskGroupId . '"  role="button" aria-hidden="true"></i>'. $newLine
                     .'             </a>'. $newLine;
                     if( $groupRow->hasTask == NULL ) {
         $groupHtml  .= '<a  class="stop-handle-task-trigger cursor handle-delete mt-xs mr-xs" data-container="body"  data-order="' . $groupRow->displayOrder . '" data-id="' . $groupRow->timesheetTaskGroupId . '" data-url="' . ROOT_RELATIVE_PATH . 'admin/timesheet-tasks/delete_group"  role="button" data-column-id="#timesheetTaskGroupId" data-type="taskgroup" >'. $newLine
                     .'<i class="fa fa-trash  white-font" data-id="' . $groupRow->timesheetTaskGroupId . '"></i>'. $newLine
                     .'             </a>'. $newLine;
                     }
         $groupHtml  .='             <a class="cursor mt-xs mr-xs group-sort handle-sort">'. $newLine
                     .'                <i class="fa fa-bars white-font" data-id="' . $groupRow->timesheetTaskGroupId . '" role="button" aria-hidden="true"></i>'. $newLine
                     .'             </a>'. $newLine
                     .'             <a class="cursor mt-xs mr-xs">'. $newLine
                     .'                <i class="fa fa-times group-cancel' . $groupRow->timesheetTaskGroupId . ' handle-cancel white-font hidden stop-handle-task-trigger" data-id="' . $groupRow->timesheetTaskGroupId . '"  role="button" aria-hidden="true"></i>'. $newLine
                     .'             </a>'. $newLine

                     .'          </div>'. $newLine
                     .'       </div>'. $newLine
                     .'    </div>'. $newLine
                     .'</div>'
                     .'</li>';
      }
         $groupHtml  .= '</ul>';
      echo json_encode( $groupHtml );
   }

   /** ----------------------------------------------------------------------------
    * Return JSON task list structure in html
    *
    * @return     json                   
    *          
    */
   public function get_selected_group() {

      $htmlStructure = '';
      $newLine       = "\n";
      $timesheetTaskGroupId = $this->input->post( 'timesheetTaskGroupId' );
      // Get query object with timesheet group items
      $timesheetGroupQuery = $this->timesheet_group_model->get_group_task_list( $timesheetTaskGroupId );
     
      // Set Header Group Bar
      $htmlStructure .= '<div class="row">' . $newLine
                     .' <div class="col-md-12">' .$newLine
                     .'    <div class="alert alert-default p-xs pr-sm pl-sm mb-xs white-font" style="background-color:' . $timesheetGroupQuery->row()->groupColor . ' !important;">' . $newLine
                     .'       <strong>' . $timesheetGroupQuery->row()->groupName . '</strong>' . $newLine
                     .'       <a href="#taskmodal"  class="modal-with-form modal-with-zoom-anim add" data-color="' . $timesheetGroupQuery->row()->groupColor . '" data-title="' . lang( 'timesheet_adm_task_add_mdl_title' ) . '"   data-id="' . $timesheetGroupQuery->row()->timesheetTaskGroupId . '">' . $newLine
                     .'          <i class="fa fa-plus handle pull-right mt-xs mr-xs white-font"    role="button" aria-hidden="true"></i>' . $newLine
                     .'       </a>' . $newLine
                     .'    </div>' . $newLine
                     .' </div>' . $newLine
                     .' </div>' . $newLine
                     .' <ul class="list-unstyled sortable">' . $newLine;
      if ( $timesheetGroupQuery->row()->timesheetTaskId != NULL ) {
         foreach ( $timesheetGroupQuery->result() as $timesheetRow ) {
             
            if ( $timesheetRow->color != NULL || $timesheetRow->color == ' ') {
               $color = $timesheetRow->color;
            } else {
               $color = $timesheetGroupQuery->row()->groupColor;
            }

         $htmlStructure .='<li id="' . $timesheetRow->timesheetTaskGroupId . '" data-type="Task" data-order="' . $timesheetRow->displayOrder . '" 
                        data-url="' . ROOT_RELATIVE_PATH . 'admin/timesheet-tasks/"  data-error="' . lang( 'timesheet_adm_notif_sort_error' ) . '" data-error-notif="' . lang( 'system_notif_error' ) . '" data-success-notif="' . lang( 'system_notif_success' ) . '">' . $newLine
                        .' <div class="row">'. $newLine
                        .'      <div class="col-md-10 col-md-offset-1">' . $newLine
                        .'          <header class="panel-heading mb-xs p-xs task-header" style="background-color:' . $color . ' !important;">' . $newLine
                        .'             <div class="text-left col-md-8">' . $newLine
                        .'             <strong class="panel-title white-font fa-md"> ' . $timesheetRow->taskName . ' </strong>' . $newLine
                        .'          </div>' . $newLine
                        .'          <div class="col-md-4 text-right pb-sm">' . $newLine
                        .'             <a href="#taskmodal"  class="modal-with-form modal-with-zoom-anim edit task-name" data-title="' . lang( 'timesheet_adm_task_edit_mdl_title' ) . '" data-id="' . $timesheetRow->timesheetTaskId . '" >' . $newLine
                        .'                <i class="fa fa-pencil handle mt-xs white-font"  role="button" aria-hidden="true"></i>' . $newLine;
         if( $timesheetRow->hasEntry == NULL ) {
         $htmlStructure .= '             <a class="cursor handle-delete " data-container="body" data-placement="left" data-type="task" data-column-id="#timesheetTaskId" data-order="' . $timesheetRow->displayOrder . '" data-group-id="' . $timesheetRow->timesheetTaskGroupId . '" data-id="' . $timesheetRow->timesheetTaskId . '"  data-url="' . ROOT_RELATIVE_PATH . 'admin/timesheet-tasks/delete_task" role="button"  >'. $newLine
                        .'                <i class="fa fa-trash white-font" aria-hidden="true" ></i>'. $newLine
                        .'             </a>'. $newLine;
         }
         $htmlStructure .='<a class="cursor  task-name">' . $newLine
                        .'                <i class="fa fa-bars handle-sort mt-xs mr-xs white-font" role="button" aria-hidden="true"></i>' . $newLine
                        .'             </a>' . $newLine
                        .'             </a>' . $newLine
                        .'            </div>' . $newLine
                        .'            </header>' . $newLine
                        .'          </div>' . $newLine
                        .'       </div>' . $newLine
                        .'</li>' . $newLine
                        .'</div> ' . $newLine;

         }

      }
      $htmlStructure .='</ul>';

      echo json_encode( $htmlStructure );

   }

   /** ----------------------------------------------------------------------------
    * Save a new timesheet group to the database
    *
    * First it adds the entry to the database, then return true if it success
    * Error if there is an error
    *
    * @param      array       $_POST      The group details
    * @return     json                    Type of form
    */  
   public function save_group() {

      $id   = null;
      $val  = '';
      if (  $this->input->post( 'timesheetTaskGroupId' ) ) {
         $id  = $this->input->post( 'timesheetTaskGroupId' );
      } 

      // Save the entry details
         $data = array(
            'groupName'             => $this->input->post( 'groupName' ),
            'groupColor'            => $this->input->post( 'groupColor' ),
            'timesheetTaskGroupId'  => $id
         );

         $timesheetTaskGroupId =  $this->timesheet_group_model->save( $data, $id );
         // Update display order value for add function only,
         // if id is not null its update function that is trigger
         if ( $id == NULL ) {
            $result_row = $this->timesheet_group_model->get_last_display_order( 'displayOrder', NULL, NULL);
            // Latest count + 1
            $displayOrder = $result_row->displayOrder + 1;     
            $this->timesheet_group_model->update_single_field( 'displayOrder', $displayOrder, $timesheetTaskGroupId ); 
         }
         $result  = array( 'type' => 'timesheet-group', 'link' => ROOT_RELATIVE_PATH . 'admin/timesheet-tasks/' );
         echo json_encode( $result );
   }

   /** ----------------------------------------------------------------------------
    * Adds a new timesheet task to the database
    *
    * First it adds the entry to the database, then return true if it success
    * Error if there is an error
    *
    * @param      array       $_POST      The task details
    * @return     json                    Type of form
    */
   public function save_task() {

      $id   = null;
      if (  $this->input->post( 'timesheetTaskId' ) ) {
         $id  = $this->input->post( 'timesheetTaskId' );
      } 

      // Save the entry details
         $data = array(
            'taskName'              => $this->input->post( 'taskName' ),
            'taskDescription'       => $this->input->post( 'taskDescription' ),
            'timesheetTaskGroupId'  => $this->input->post( 'timesheetTaskGroupId' ),
            'color'                 => $this->input->post( 'color' ),
            'chargeable'            => $this->input->post( 'chargeable' ),
            'hiddenReports'         => $this->input->post( 'hiddenReports' ),
            'active'                => $this->input->post( 'active' ),
            'timeTaken'             => $this->input->post( 'timeTaken' ),
            'createButton'          => $this->input->post( 'createButton' ),
         );

         $timesheetTaskId =  $this->timesheet_task_model->save( $data, $id );
         // Update display order value for add function only,
         // if id is not null its update function that is trigger
         if ( $id == NULL ) {
             $result_row = $this->timesheet_task_model->get_last_display_order( 'displayOrder', 'timesheetTaskGroupId', $this->input->post( 'timesheetTaskGroupId' ) );
            // Latest count + 1
            $displayOrder = $result_row->displayOrder + 1;     
            $this->timesheet_task_model->update_single_field( 'displayOrder', $displayOrder, $timesheetTaskId ); 
         }
        

         $result  = array( 'id' => $this->input->post( 'timesheetTaskGroupId' ), 'type' => 'timesheet-tasks', 'link' => ROOT_RELATIVE_PATH . 'admin/timesheet-tasks/' );
         echo json_encode( $result );
   }

   /** ----------------------------------------------------------------------------
    * Returns the correct task row for timesheet tasks
    *
    * Display the selected row in json if not empty(var)
    *
    * @param  array $_POST['timsheetTaskId']          Timesheet tasks id
    * @return void                          
    *          
    */
    public function get_single_row() {

      $timsheetTaskId = $_POST['timsheetTaskId'];
      // Returns a single object with tasks detail
      $tasksQuery = $this->timesheet_task_model->get_tasks_details( $timsheetTaskId );
      if( !empty( $tasksQuery ) ) {
         $data[] = $tasksQuery;
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Update timesheet group item data to the database        
    *
    * return update when success and error if fail to save
    *
    * @param      array     $_POST         Group id
    * @return     bool      error          Display if update fail
    */   
   public function update_timesheet_group_list_order(){

      // Update job checklist item once $_POST is set
      if ( isset( $_POST ) ) {
         
         $groupId = $_POST['id'] ;
         $groupQuery =  $this->timesheet_group_model->get_display_id( $_POST, $groupId );
         // Update displayOrder base on start order number and end order number
         $this->timesheet_group_model->change_display_order( $_POST, $groupId );
         // arrange new display order
         $field   = 'displayOrder';
         $id      = $groupQuery->timesheetTaskGroupId;
         $where   = 1;
         $start   = $_POST['start'];
         $end     = $_POST['end'];
         $this->timesheet_group_model->reorder( $field, $where, $start, $end, $id );
         // return success message
         $result  = array( 'type' => 'Group', 'link' => ROOT_RELATIVE_PATH . 'admin/timesheet-tasks/', 'msg' => lang('timesheet_adm_notif_sort_success') );
         echo json_encode( $result );
      } else {
         echo "error";
      }
   }


   /** ----------------------------------------------------------------------------
    * Update timesheet task item data to the database        
    *
    * return update when success and error if fail to save
    *
    * @param      array     $_POST         Task id
    * @return     bool      error          Display if update fail
    */   
   public function update_timesheet_task_list_order(){

      // Update job checklist item once $_POST is set
      if ( isset( $_POST ) ) {
         
         $groupId = $_POST['id'] ;
         $taskQuery =  $this->timesheet_task_model->get_display_id( $_POST, $groupId );
         // Update displayOrder base on start order number and end order number
         $this->timesheet_task_model->change_display_order( $_POST, $groupId );
         //arrange new display order
         $field   = 'displayOrder';
         $id      = $taskQuery->timesheetTaskId;
         $where   = "timesheetTaskGroupId = '" . $groupId . "' ";
         $start   = $_POST['start'];
         $end     = $_POST['end'];
         $this->timesheet_task_model->reorder( $field, $where, $start, $end, $id );
         //return success message
         $result  = array( 'type' => 'Task', 'link' => ROOT_RELATIVE_PATH . 'admin/timesheet-tasks/', 'msg' => lang('timesheet_adm_notif_sort_success'), 'id' => $groupId  );
         echo json_encode( $result );
      } else {
         echo "error";
      }
   }


   /** ----------------------------------------------------------------------------
    * Delete a  timesheet group that has no task to the database
    *
    * First it delete the entry to the database, then return true if it success
    * Error if there is an error
    *
    * @param      array       $_POST      The group details
    * @return     json                    Type of form
    */  
   public function delete_group() {
      
      if (  $this->input->post( 'timesheetTaskGroupId' ) ) {
         $groupQuery = $this->timesheet_group_model->check_group_has_task( $this->input->post( 'timesheetTaskGroupId' ) );
         // Set safety restriction, delete only once no task under that group
         if ( $groupQuery->hasTask == NULL ) {
            $id = $this->input->post( 'timesheetTaskGroupId' );
            // Reorder checklist before delete
            $this->timesheet_group_model->update_order( $_POST );
            $this->timesheet_group_model->delete( $id );
            $result  = array( 'type' => 'timesheet-group', 'link' => ROOT_RELATIVE_PATH . 'admin/timesheet-tasks/', 'caption' => 'delete' );
            echo json_encode( $result );
         } 
      } 
         
   }

   /** ----------------------------------------------------------------------------
    * Delete a  timesheet task that has no timesheet entry to the database
    *
    * First it delete the entry to the database, then return true if it success
    * Error if there is an error
    *
    * @param      array       $_POST      The task details
    * @return     json                    Type of form
    */  
   public function delete_task() {
      
      if (  $this->input->post( 'timesheetTaskId' ) ) {
          $taskQuery = $this->timesheet_task_model->check_task_has_entry( $this->input->post( 'timesheetTaskId' ) );
         // Set safety restriction, delete only once no task under that timesheet entry
         if ( $taskQuery->hasEntry == NULL ) {
            $id      = $this->input->post( 'timesheetTaskId' );
            $groupId = $this->input->post( 'timesheetTaskGroupId' );
            // Reorder checklist before delete
            $this->timesheet_task_model->update_order( $_POST );
            $this->timesheet_task_model->delete( $id );
            $result  = array( 'type' => 'timesheet-tasks', 'link' => ROOT_RELATIVE_PATH . 'admin/timesheet-tasks/', 'caption' => 'delete', 'id' => $groupId );
            echo json_encode( $result );
         }
      } 
   }

}