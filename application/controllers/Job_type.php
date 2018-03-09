<?php
/**
 *  Job Type Controller
 *  
 *  Display the Job Type page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Job_type extends Admin_controller {


   public function __construct() {
      parent::__construct();
      $this->lang->load('job_type', 'english');
      $this->load->model('job_type_model');
      $this->load->library('json_library');
   }
  
 

   /** ----------------------------------------------------------------------------
    * View page of department list     
    */
   public function index() { 

      $this->data['meta_title']         = lang( 'job_type_name' );
      $this->data['page_title']         = lang( 'job_type_sect_title' );
      $this->data['breadcrumb_items']   = array( lang( 'system_menu_adm_job_types' ) );

      $this->data['pageCustomJS']       = $this->load->view( 'job-type/_job-type-custom-js', '', true );
      $this->data['pageVendorJS']       = $this->load->view( '_pageassets/form-vendor-js', '', true );
      $this->data['pageVendorCss']      = $this->load->view( '_pageassets/form-vendor-css', '', true );
   

      $this->load->view( '_template/header',  $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'job-type/index' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( 'job-type/_job-type-modal' );
      $this->load->view( '_template/footer' );

   }

    

   /** ----------------------------------------------------------------------------
    * Adds/Update a  job type entry to the database
    *
    * If job type id is set it will update the entry to the database, add if no id is set
    *
    * @param   array    $_POST        The job type details
    * @return  bool     true, error   Display output if success or fail
    */
   public function save() {
      // Uppercase first letter word
      $_POST['name'] = ucfirst( $_POST['name'] );
      if ( isset( $_POST ) ) {   

         // Set id for add 
         $id = null;
         // Set id for update
         if ( $_POST['jobTypeId'] != '' ) {
            $id   = $_POST['jobTypeId'];
         } 

         $this->job_type_model->save( $_POST, $id );
         echo "true";
      } else {
         echo "error";
      }
   }

   


   /** ----------------------------------------------------------------------------
    * Delete job type to the database
    *
    * Check if jobTypeId is set then, trigger delete function on database
    *
    * @param      array     $_POST          Job type id                
    */
   public function delete_single_row() {
      
      if ( isset ( $_POST ) ) {
         $this->job_type_model->delete( $_POST['jobTypeId'] );
         echo "true";  
      } else {
         echo "error";
      }
   }
   

   /** ----------------------------------------------------------------------------
    * Returns the correct job type row for job type
    *
    * Display the selected row in json if not empty(var)
    *
    * @param    array       $_POST          Job type id
    * @return   void                               
    *          
    */
   public function get_single_row(){
      $jobTypeId = $_POST['jobTypeId'];
      // Returns a single object with job type detail
      $jobTypeDetails = $this->job_type_model->get_job_type_row( $jobTypeId );
      if ( !empty( $jobTypeDetails ) ) {
         $data[] = $jobTypeDetails; 
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Retrieves all row for job type   
    *
    * @return  json        Return json data from database
    *          
    */ 
   public function get_rows_json(){
      // Get query object with job type items
      $jobTypeQuery = $this->job_type_model->get();
      // check if data from query is no null
      if ( count( $jobTypeQuery ) ) { 
         foreach ( $jobTypeQuery as $row ) {
            $arrayName[]   = array(
            "name"         => $row->name,
            "actions"      => '<a href="#modalForm" data-id="' . $row->jobTypeId . '" class="modal-with-form modal-with-zoom-anim edit"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit job type details"></i></a> <a href="#"  data-id="' . $row->jobTypeId . '" data-toggle="confirmation" class="confirmation-callback"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete job type details"></i></a>'
            );
         }
      } else { // return $arrayName empty
         $arrayName = empty( $arrayName );
      }

      header('Content-Type: application/json');
      echo '{ "data": ' . json_encode( $arrayName ) . '}';

   }

}
