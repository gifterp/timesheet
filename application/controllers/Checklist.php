<?php
/** 
 *  Checklist Controller 
 *
 *  Displays the Checklist page and handles the AJAX calls to the database
 *  
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial 
 * @version     Release: 1.0.0 
 * @since       File available since Release 1.0.0 
 */
class Checklist extends Admin_controller { 
          
     
   public function __construct() {
      parent::__construct(); 
      $this->lang->load( 'checklist', 'english' );
      $this->load->helper( 'checklist' ); 
      $this->load->model( 'checklist_model' ); 
      $this->load->model( 'checklistitem_model' );
      $this->load->model( 'job_checklist_data_model' );
      $this->load->model( 'job_checklist_model' );
      $this->load->library( 'json_library' );
   }  
 
   /** ----------------------------------------------------------------------------
    * View page of checklist list   
    */ 
   public function index() {

      $this->data['meta_title']         = lang( 'checklist_name' );
      $this->data['page_title']         = lang( 'checklist_sect_title' );
      $this->data['breadcrumb_items']   = array( lang( 'system_menu_adm_checklists' ) );

      $this->data['pageVendorJS']       = $this->load->view( '_pageassets/form-vendor-js', '', true);
      $this->data['pageCustomJS']       = $this->load->view( 'checklist/_checklist-custom-js', '', true);
      $this->data['pageVendorCss']      = $this->load->view( '_pageassets/form-vendor-css', '', true);
      $this->data['pageCustomCss']      = $this->load->view( 'checklist/_checklist-custom-css', '', true);
      
     
      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'checklist/index' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( 'checklist/_checklist-modal' );
      $this->load->view( '_template/footer' );

   }


   /** ----------------------------------------------------------------------------
    * Displays the checklist item page
    */
   public function view( $checklistId ) {

      $this->data['meta_title']         = lang( 'checklist_item_name' );
      $this->data['page_title']         = lang( 'checklist_sect_title' );
      $this->data['breadcrumb_items']   = array( '<a href="' . ROOT_RELATIVE_PATH . 'checklist">' . lang( 'system_menu_adm_checklists' ) . "</a>", lang( 'checklist_edit_sect_title' ) );
      $this->data['pageVendorJS']       = $this->load->view( '_pageassets/form-vendor-js', '' , true );
      $this->data['pageCustomJS']       = $this->load->view( 'checklist/_checklist-item-custom-js', '', true );
      $this->data['pageVendorCss']      = $this->load->view( '_pageassets/form-vendor-css', '', true );
      $this->data['pageCustomCss']      = $this->load->view( 'checklist/_checklist-custom-css', '', true);
      // Get query object with checklist items
      $this->data['checklistDetails'] = $this->checklist_model->get_checklist_details( $checklistId );
 
      // Pass number of checklist items to view
      $this->data['checklistItemCount'] = $this->checklistitem_model->get_checklistitem_details( $checklistId )->num_rows();
      
      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'checklist/item_list' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );

   }

    

   /** ----------------------------------------------------------------------------
    * Add / Update a  checklist entry to the database
    *
    * Save data checklist entry on database 
    *
    * @param   array    $_POST        The checklist details
    * @return  bool     true, error   Display output if success or fail
    */ 
   public function save() {

      // Save checklist  once $_POST is set
      if ( isset( $_POST ) ) {
        
         $id  = null;
         // Set checklist form status
         $status     = 'new'; 
         if ( $_POST['checklistId'] != '' ) {
            $id      = $_POST['checklistId'];
            $status  = 'update';
            $link    = ROOT_RELATIVE_PATH . 'checklist/';
         } 
         $checklistId = $this->checklist_model->save( $_POST, $id ); 
         // Redirect to view job page for new job entry
         if ( $status == 'new') { $link       = ROOT_RELATIVE_PATH . 'checklist/view/' . $checklistId; } 
         $result  = array( 'id' => $checklistId, 'link' => $link, 'type' => 'checklist', 'status' => $status );
         echo json_encode( $result ); 

      } else {
         echo "error";
      }
   }

    

   /** ----------------------------------------------------------------------------
    * Adds a new checklist item to the database
    *
    * Error if there is an error
    *
    * @param      array       $_POST      The checklist item details
    * @return     json                    Checklist id, link
    * @return     bool        error       Display once its not saves on database
    */   
   public function create_item_checklist() {

      // Save checklist item if $_POST is set
      if ( isset( $_POST ) ) {
        
         $id = $this->checklistitem_model->save( $_POST );
            // Return latest count of display order
            $result_row = $this->checklistitem_model->get_last_display_order( 'displayOrder', 'checklistId', $_POST['checklistId'] );
            //Latest count + 1
            $displayOrder = $result_row->displayOrder + 1;     
            $this->checklistitem_model->update_single_field( 'displayOrder', $displayOrder, $id ); 

            // Return jobChecklistId
            $row_item = $this->job_checklist_model->check_checklist( $_POST['checklistId'] );
            // Add checklist item in job checklist section if $row_item is not empty
            if ( !empty( $row_item ) ) {
               foreach ($row_item as $row) {
                  $value['jobChecklistId']   = $row->jobChecklistId;
                  $value['checklistItemId']  = $id;
                  $this->job_checklist_data_model->save( $value );
               }
            }

         $result  = array( 'id' => $_POST['checklistId'], 'link' => ROOT_RELATIVE_PATH . 'checklist/' );
         echo json_encode( $result );
      } else {
         echo "error";
      }
   }



   /** ----------------------------------------------------------------------------
    * Delete a  checklist item to the database
    *
    * Error if there is an error
    *
    * @param      array       $_POST      The checklist item details
    * @return     json                    Checklist id, link
    * @return     bool        error       Display once its not saves on database
    */ 
   public function delete_item_checklist() {

      // Delete once checklistItemId is set
      if ( isset( $_POST['checklistItemId'] ) ) {
         $arg = 0;
         // Check if there is checklist item in job checklist
         $item = $this->job_checklist_data_model->check_item_list( $_POST['checklistItemId'] );
         if ( !empty( $item ) ) {
            $arg = 1;
         }
         // // Update displayOrder after delete row
         $this->checklistitem_model->update_order( $_POST );
         // // Delete checklist item,if $arg = 1 will delete also checklist in job checklist
         $this->checklistitem_model->delete_checklist_item( $_POST['checklistItemId'], $arg );

         $result  = array( 'status' => 'delete', 'link' => ROOT_RELATIVE_PATH . "checklist/" ); 
         echo json_encode( $result );
      } else {
         echo "error";
      }
   }



  


    /** ----------------------------------------------------------------------------
    * Update a  checklist item to the database
    *
    * Error if there is an error
    *
    * @param      array       $_POST      The checklist item details
    * @return     json                    Checklist id, link
    * @return     bool        error       Display once its not saves on database
    */   
   public function update_checklist_item(){

      // Update checklist Item data once $_POST is set
      if ( isset( $_POST ) ) {
         $this->checklistitem_model->save( $_POST, $_POST['checklistItemId'] );
         $result  = array( 'status' => 'update','id' => $_POST['checklistItemId'], 'link' => ROOT_RELATIVE_PATH . 'checklist/' );
         echo json_encode( $result );   
      } else {
         echo "error";
      }
   }


   /** ----------------------------------------------------------------------------
    * Update checklistitem data to the database        
    *
    * Check if checklistItemId is set then, trigger save function on database
    * return update when success and error if fail to save
    *
    * @param      array       $_POST            Checklist item details
    * @return     bool        error             Display once its not updated on database
    * @return     string                        Html output row order of checklist Item
    *          
    */  
   public function update_checklist_item_order() { 

      // Update Checklist Item order once $_POST is set
      if ( isset( $_POST ) ) {
         $checklistId = $_POST['checklistId'];
         $checklistitemQuery =  $this->checklistitem_model->get_display_id( $_POST, $checklistId );
         // Update displayOrder
         $this->checklistitem_model->change_display_order( $_POST, $checklistId );
         if ( !empty( $checklistitemQuery ) ) {
             // arrange new display order
            $field   = 'displayOrder';
            $id      = $checklistitemQuery->checklistItemId;
            $where   = "checklistId = '" . $checklistId . "' ";
            $start   = $_POST['start'];
            $end     = $_POST['end'];
            $this->checklistitem_model->reorder( $field, $where, $start, $end, $id );
            // return html cheklist item view
         }       
         echo $this->get_multiple_row( $checklistId ); 
      } else {
         echo "error";
      }
   }



   /** ----------------------------------------------------------------------------
    * Returns the correct checklist row for checklist
    *
    * Display the selected row in json if not empty(var)
    *
    * @param      array       $_POST          Checklist id
    * @return     void                       
    *          
    */
   public function get_single_row() {

      $checklistId = $this->input->post('checklistId');

      // Returns a single object with checklist detail
      $data[] = $this->checklist_model->get_checklist_details( $checklistId );

      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Creates the HTML for displaying items from a checklist
    *
    * Create a HTML output for the checklist item return as JSON
    *
    * @param      array       $_POST         Checklist id
    * @return     void                       Print valid JSON
    */
   public function get_multiple_row() {

      $htmlContainer = '';
      $newLine       = "\n";
      $typeOptions   = array( 'Checkbox' => 'Checkbox', 'Date' => 'Date', 'Input' => 'Text' );
      $widthOptions  = array( 'col-md-2' => '1/6 wide', 'col-md-4' => '1/3 wide', 'col-md-6' => 'Half width', 'col-md-8' => '2/3 wide', 'col-md-10' => '5/6 wide', 'col-md-12' => 'Full width' );

      $checklistId = $_POST['checklistId'];

      // Get query object with checklist items
      $checklistItemQuery = $this->checklistitem_model->get_checklistitem_details( $checklistId );

      // creates html output there are items in the checklist
      if ( !empty( $checklistItemQuery ) ) {

         $htmlContainer .= "<ul class=\"list-unstyled sortable\">\n";

         foreach ( $checklistItemQuery->result() as $row ) {

            // Set the attributes for the field type select dropdown
            $typeAttributes = 'class="form-control input-sm field-type" data-link="' . ROOT_RELATIVE_PATH . 'checklist/update-checklist-item" data-id="' . $row->checklistItemId . '" data-checklist="' . $row->checklistId . '"';

            // Set the attributes for the column width select dropdown
            $widthAttributes = 'class="form-control input-sm column" data-link="' . ROOT_RELATIVE_PATH . 'checklist/update-checklist-item" data-id="' . $row->checklistItemId . '"';

            if ( $row->newRow == 0 ) {
               $checked = '';
               $c_val = 1;
            } else {
               $checked    = ' checked';
               $c_val      = 0 ;
            }

            // start: left panel checklist
            $htmlContainer .= '   <li id="' . $checklistId . '" data-order="' . $row->displayOrder . '" data-url="' . ROOT_RELATIVE_PATH . 'checklist/" class="checklist-row" data-error ="' . lang( 'checklist_notif_sort_error' ) . '" data-error-notif="' . lang( 'system_notif_error' ) . '">' . $newLine
               .'      <div class="row">' . $newLine
               .'         <div class="col-md-12 margin-left-minus-5">' . $newLine
               .'            <div class="col-md-2 div-mt-xxs" >' . $newLine
               .'               <div class="checkbox-custom checkbox-default">' . $newLine
               .'                  <input id="right-box-' . $row->checklistItemId . '" data-id="' . $row->checklistItemId . '" data-link="' . ROOT_RELATIVE_PATH . 'checklist/update_checklist_item" name="newRow" class="new-row" value="' . $c_val . '"  type="checkbox"' . $checked .' >' . $newLine
               .'                  <label for="right-box-' . $row->checklistItemId . '" class="row-font">New row</label>' . $newLine
               .'               </div>' . $newLine
               .'            </div>' . $newLine
               .'            <div class="col-md-3 p-xxs">' . $newLine
               .'               <div class="form-group">' . $newLine
               . form_dropdown( 'fieldType', $typeOptions, $row->fieldType, $typeAttributes )
               .'               </div>' . $newLine
               .'            </div>' . $newLine
               .'            <div class="col-md-3 p-xxs">' . $newLine
               .'               <div class="form-group">' . $newLine
               .'                  <input class="form-control input-sm field-input" data-id="' . $row->checklistItemId . '" id="date-field' . $row->checklistItemId . '" maxlength="20" value="' . $row->fieldTitle . '" name="fieldTitle" placeholder="Enter an optional title" type="text" data-link="' . ROOT_RELATIVE_PATH . 'checklist/update_checklist_item">' . $newLine
               .'               </div>' . $newLine
               .'            </div>' . $newLine
               .'            <div class="col-md-3 p-xxs">' . $newLine
               .'               <div class="form-group">' . $newLine
               . form_dropdown( 'fieldWidth', $widthOptions, $row->fieldWidth, $widthAttributes )
               .'               </div>' . $newLine
               .'            </div>' . $newLine
               .'            <div class="col-md-1 p-xxs" >' . $newLine
               .'               <div class="form-group checklist-icon">' . $newLine
               .'                  <a class="delete-row text-sm marg_right cursor" data-container="body" data-id="' . $row->checklistItemId . '" data-order="' . $row->displayOrder . '" data-checklist-id="' . $row->checklistId . '" data-link="' . ROOT_RELATIVE_PATH . 'checklist/delete_item_checklist"><i class="fa fa-trash-o"></i></a>' . $newLine
               .'                  <a class="text-sm cursor"><i class="fa fa-bars handle cursor-move" role="button" aria-hidden="true"></i></a>' . $newLine
               .'               </div>' . $newLine
               .'            </div>' . $newLine
               .'         </div>' . $newLine
               .'         <input type="hidden" value="' . $row->checklistItemId . '" name="checklistitemId" id="check-list-item-id' . $row->checklistItemId . '">' . $newLine
               .'      </div>' . $newLine
               .'   </li>' . $newLine;
            // end: left panel checklist
         }
         $htmlContainer .= "</ul>";

         // Display HTML in json output
         echo json_encode( $htmlContainer );

      } else {
         echo "error";
      }
   }


   /** ----------------------------------------------------------------------------
    * Gets details of a single checklist and outputs the HTML
    *
    * @param      array       $_POST        Checklist id
    * @return     html                      HTML for the checklist form if it has some items
    */
   public function print_checklist( ) {

      $checklistId = $this->input->post('checklistId');

      // Get the checklists for this job as an array
      $checklistItems = $this->checklistitem_model->get_all_checklist_details( $checklistId );

      // Output the checklist html if this job has them attached
      if ( !empty( $checklistItems ) ) {
         echo print_checklist_forms( $checklistItems );
      }
   }

   /** ----------------------------------------------------------------------------
    * Retrieves all row for checklist   
    *
    * @return json                Return json data from database
    *          
    */
   public function get_rows_json() {
      // Get query array of object with checklist item items
      $checklistqQuery = $this->checklist_model->get();
      // check if data from query is no null
      if ( count( $checklistqQuery ) ) {  
         foreach ( $checklistqQuery as $row ) {
            // Pass data into array
            $arrayName[] = array(
            "checklistId"   => $row->checklistId,
            "title"         => $row->title,
            "description"   => $row->description,
            "actions"       => '<a href="#modalForm" data-id="' . $row->checklistId . '" class="modal-with-form modal-with-zoom-anim edit"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit checklist details"></i></a>',
            );

         } 
      } else { // return $arrayName null
         $arrayName = empty( $arrayName );
      }
      header('Content-Type: application/json');
      echo '{ "data": ' . json_encode( $arrayName ) . '}';
   }
    


}