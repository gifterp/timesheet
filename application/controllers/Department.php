<?php
/**
 *  Department Controller
 *  
 *  Display the Department page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Department extends Admin_controller {

   public function __construct() {
      parent::__construct();
      $this->lang->load( 'department', 'english' );
      $this->load->model( 'department_model' );
      $this->load->library( 'json_library' );
   }
 
 

   /** ----------------------------------------------------------------------------
    * View page of department list     
    */ 
   public function index() { 

      $this->data['meta_title']         = lang( 'department_name' );
      $this->data['page_title']         = lang( 'department_sect_title' );
      $this->data['breadcrumb_items']   = array( lang( 'system_menu_adm_departments' ) );

      $this->data['pageCustomJS']       = $this->load->view( 'department/_department-custom-js', '', true );
      $this->data['pageVendorJS']       = $this->load->view( '_pageassets/form-vendor-js', '', true );
      $this->data['pageVendorCss']      = $this->load->view( '_pageassets/form-vendor-css', '', true );
   

      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'department/index' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( 'department/_department-modal' );
      $this->load->view( '_template/footer' );

   } 

   

   /** ----------------------------------------------------------------------------
    * Adds/Update a  deparment entry to the database
    *
    * If deparment id is set it will update the entry to the database, add if no id is set
    *
    * @param   array    $_POST        The department details
    * @return  bool     true, error   Display output if success or fail
    */
   public function save(){
      // Uppercase first letter word
      $_POST['name'] = ucfirst( $_POST['name'] );
      if ( isset( $_POST ) ) {
         // Set id for add 
         $id = null;
         // Set id for update
         if ( $_POST['departmentId'] != '' ) {
            $id = $_POST['departmentId'];
         }
         $this->department_model->save( $_POST, $id );
         echo "true";
      } else {
         echo "error";
      }
   }

   

   /** ----------------------------------------------------------------------------
    * Returns the correct department row for department
    *
    * Display the selected row in json if not empty(var)
    *
    * @param    int     $departmentId        Department id
    * @return   void                            
    *          
    */
   public function get_single_row() {
      $departmentId = $_POST['departmentId'];
      // Returns a single row object with department detail
      $departmentDetails = $this->department_model->get_department_details( $departmentId );
      if ( !empty( $departmentDetails ) ) {
         $data[] = $departmentDetails->row();
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Retrieves all row for department   
    *
    * @return json        Return json data from database
    *          
    */  
   public function get_rows_json() {
      // Get query array of object with checklist item items
      $departmentQuery = $this->department_model->get();
      // check if data from query is no null
      if ( count( $departmentQuery ) ) { 
         foreach ( $departmentQuery as $row ) {
            $arrayName[] = array(
            "name"      => $row->name ,
            "actions"   => '<a href="#modalForm" data-id="' . $row->departmentId . '" class="modal-with-form modal-with-zoom-anim edit"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit deparment details"></i></a>',
            ); 
         }
      } else { 
         // return $arrayName empty
         $arrayName = empty( $arrayName );
      }
      header('Content-Type: application/json');
      echo '{ "data": ' . json_encode( $arrayName ) . '}';

   }

}
