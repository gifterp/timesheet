<?php
/**
 *  Mock Invoice description Controller
 *  
 *  Display the admin mock invoice  description page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */ 
class Mockinvoice_description extends Admin_controller {

   public function __construct() {
      parent::__construct(); 
      $this->lang->load( 'system', 'english' );
      $this->lang->load ( 'mockinvoice_description', 'english' );
      $this->load->model( 'admin/mockinvoice_description_model' );
      $this->load->library( 'json_library' );
   }
 


  /** ----------------------------------------------------------------------------
    * View page of mock invoice description list     
    */
   public function index() { 

      $this->data['meta_title']         = lang( 'mockinvoice_description_name' );
      $this->data['page_title']         = lang( 'mockinvoice_description_sect_title' );
      $this->data['breadcrumb_items']   = array( lang( 'mockinvoice_description_name' ) );

      $this->data['pageCustomJS']       = $this->load->view( 'admin/mockinvoice-description/_custom-js', '', true );
      $this->data['pageVendorJS']       = $this->load->view( 'admin/mockinvoice-description/_vendor-js', '', true );
      $this->data['pageVendorCss']      = $this->load->view( 'admin/mockinvoice-description/_vendor-css', '', true );
   

      $this->load->view( '_template/header',  $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'admin/mockinvoice-description/index' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );

   }

    

   /** ----------------------------------------------------------------------------
    * Adds/Update a  mock invoice description entry to the database
    *
    * If mock invoice description id is set it will update the entry to the database, add if no id is set
    *
    * @param   array    $_POST        The mock invoice description details
    * @return  bool     true, error   Display output if success or fail
    */
   public function save() {
      if ( isset( $_POST ) ) {   

         // Set id for add 
         $id = null;
         // Set id for update
         if ( $_POST['mockInvoiceDescriptionId'] != '' ) {
            $id   = $_POST['mockInvoiceDescriptionId'];
         } 
         $this->mockinvoice_description_model->save( $_POST, $id );
         echo "true";
      } else {
         echo "error";
      }
   }

   


   /** ----------------------------------------------------------------------------
    * Delete mock invoice description to the database
    *
    * Check if mockInvoiceDescriptionId is set then, trigger delete function on database
    *
    * @param      array     $_POST          mock invoice description id                
    */
   public function delete_single_row() {
      
      if ( isset ( $_POST ) ) {
         $this->mockinvoice_description_model->delete( $_POST['mockInvoiceDescriptionId'] );
         echo "true";  
      } else {
         echo "error";
      }
   }
   

   /** ----------------------------------------------------------------------------
    * Returns the correct mock invoice description row for mock invoice description
    *
    * Display the selected row in json if not empty(var)
    *
    * @param    array       $_POST          mock invoice description id
    * @return   void                               
    *          
    */
   public function get_single_row(){
      $mockInvoiceDescriptionId = $_POST['mockInvoiceDescriptionId'];
      // Returns a single object with mock invoice description detail
      $descriptionDetails = $this->mockinvoice_description_model->get_mockinvoice_description_row( $mockInvoiceDescriptionId );
      if ( !empty( $descriptionDetails ) ) {
         $data[] = $descriptionDetails; 
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Retrieves all row for mock invoice description   
    *
    * @return  json        Return json data from database
    *          
    */ 
   public function get_rows_json(){
      // Get query object with mock invoice description items
      $descriptionQuery = $this->mockinvoice_description_model->get();
      // check if data from query is no null
      if ( count( $descriptionQuery ) ) { 
         foreach ( $descriptionQuery as $row ) {
            $arrayName[]   = array(
            "id"          => $row->mockInvoiceDescriptionId,
            "description"    => $row->description,
            "actions"     =>'<a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a> <a href="#" class=" on-default remove-icon confirmation-callback" data-id="' . $row->mockInvoiceDescriptionId . '"><i class="fa fa-trash-o"></i></a><a href="#" class="on-default remove-row row' . $row->mockInvoiceDescriptionId . ' hidden" ><i class="fa fa-trash-o"></i></a>'
            );
         }
      } else { // return $arrayName empty
         $arrayName = empty( $arrayName );
      }

      header('Content-Type: application/json');
      echo '{ "data": ' . json_encode( $arrayName ) . '}';

   }

}
