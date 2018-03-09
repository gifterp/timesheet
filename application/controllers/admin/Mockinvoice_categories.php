<?php
/**
 *  Mock invoice Categories Controller
 *  
 *  Display the admin mock invoice  categories page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */ 
class Mockinvoice_categories extends Admin_controller {

   public function __construct() {
      parent::__construct(); 
      $this->lang->load( 'system', 'english' );
      $this->lang->load ( 'mockinvoice_categories', 'english' );
      $this->load->model( 'admin/mockinvoice_category_model' );
      $this->load->library( 'json_library' );
   }
 


  /** ----------------------------------------------------------------------------
    * View page of mock invoice categories list     
    */
   public function index() { 

      $this->data['meta_title']         = lang( 'mockinvoice_categories_name' );
      $this->data['page_title']         = lang( 'mockinvoice_categories_sect_title' );
      $this->data['breadcrumb_items']   = array( lang( 'mockinvoice_categories_name' ) );

      $this->data['pageCustomJS']       = $this->load->view( 'admin/mockinvoice-categories/_custom-js', '', true );
      $this->data['pageVendorJS']       = $this->load->view( 'admin/mockinvoice-categories/_vendor-js', '', true );
      $this->data['pageVendorCss']      = $this->load->view( 'admin/mockinvoice-categories/_vendor-css', '', true );
   

      $this->load->view( '_template/header',  $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'admin/mockinvoice-categories/index' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );

   }

    

   /** ----------------------------------------------------------------------------
    * Adds/Update a  mock invoice categories entry to the database
    *
    * If mock invoice categories id is set it will update the entry to the database, add if no id is set
    *
    * @param   array    $_POST        The mock invoice categories details
    * @return  bool     true, error   Display output if success or fail
    */
   public function save() {
      // Uppercase first letter word
      $_POST['category'] = ucfirst( $_POST['category'] );
      if ( isset( $_POST ) ) {   

         // Set id for add 
         $id = null;
         // Set id for update
         if ( $_POST['mockInvoiceCategoryId'] != '' ) {
            $id   = $_POST['mockInvoiceCategoryId'];
         } 

         $this->mockinvoice_category_model->save( $_POST, $id );
         echo "true";
      } else {
         echo "error";
      }
   }

   


   /** ----------------------------------------------------------------------------
    * Delete mock invoice categories to the database
    *
    * Check if mockInvoiceCategoryId is set then, trigger delete function on database
    *
    * @param      array     $_POST          mock invoice categories id                
    */
   public function delete_single_row() {
      
      if ( isset ( $_POST ) ) {
         $this->mockinvoice_category_model->delete( $_POST['mockInvoiceCategoryId'] );
         echo "true";  
      } else {
         echo "error";
      }
   }
   

   /** ----------------------------------------------------------------------------
    * Returns the correct mock invoice categories row for mock invoice categories
    *
    * Display the selected row in json if not empty(var)
    *
    * @param    array       $_POST          mock invoice categories id
    * @return   void
    */
   public function get_single_row(){
      $mockInvoiceCategoryId = $_POST['mockInvoiceCategoryId'];
      // Returns a single object with mock invoice categories detail
      $categoriesDetails = $this->mockinvoice_category_model->get_mockinvoice_categories_row( $mockInvoiceCategoryId );
      if ( !empty( $categoriesDetails ) ) {
         $data[] = $categoriesDetails; 
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Retrieves all row for mock invoice categories   
    *
    * @return  json        Return json data from database
    */ 
   public function get_rows_json(){
      // Get query object with mock invoice categories items
      $categoriesQuery = $this->mockinvoice_category_model->get();
      // check if data from query is no null
      if ( count( $categoriesQuery ) ) { 
         foreach ( $categoriesQuery as $row ) {
            $arrayName[]   = array(
            "id"          => $row->mockInvoiceCategoryId,
            "category"    => $row->category,
            "actions"     =>'<a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a> <a href="#" class=" on-default remove-icon confirmation-callback" data-id="' . $row->mockInvoiceCategoryId . '"><i class="fa fa-trash-o"></i></a><a href="#" class="on-default remove-row row' . $row->mockInvoiceCategoryId . ' hidden" ><i class="fa fa-trash-o"></i></a>'
            );
         }
      } else { // return $arrayName empty
         $arrayName = empty( $arrayName );
      }

      header('Content-Type: application/json');
      echo '{ "data": ' . json_encode( $arrayName ) . '}';

   } 

}
