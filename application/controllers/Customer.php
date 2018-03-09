<?php
/**
 *  Customer Controller
 *  
 *  Display the Customer page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Customer extends Admin_controller {
  
   public function __construct(){ 
      parent::__construct();
      $this->lang->load( 'client', 'english' );
      $this->load->helper( 'isoft' );
      $this->load->model( 'customer_model' );
      $this->load->library( 'json_library' );
   }  
 
   /** ----------------------------------------------------------------------------
    * View page of customer list     
    */ 
   public function index(){

      $this->data['meta_title']         = lang( 'customer_name' );
      $this->data['page_title']         = lang( 'customer_sect_title' );
      $this->data['breadcrumb_items']   = array( lang( 'system_menu_customer' ) );


      $this->data['pageVendorJS']       = $this->load->view( '_pageassets/form-vendor-js', '', true );
      $this->data['pageCustomJS']       = $this->load->view( 'customer/_customer-custom-js', '', true );
      $this->data['pageVendorCss']      = $this->load->view( '_pageassets/form-vendor-css', '', true );

      $this->load->view('_template/header', $this->data);
      $this->load->view('_template/page-header');
      $this->load->view('customer/index');
      $this->load->view('_template/sidebar-right');
      $this->load->view('customer/_customer-modal');
      $this->load->view('_template/footer');

   }

   

   /** ----------------------------------------------------------------------------
    * Adds/Update a  customer entry to the database
    *
    * If customer id is set it will update the entry to the database, add if no id is set
    *
    * @param   array    $_POST        The Customer details
    * @return  bool     true, error   Display output if success or fail
    */  
   public function save() {
      // Uppercase first letter word
      $_POST['name'] = ucfirst( $_POST['name'] );

      if ( isset( $_POST ) ) {

         // Set id for add 
         $id = null;
         // Set id for update
         if ( $_POST['customerId'] != '' ) {
            $id   = $_POST['customerId'];
         } 

         // Set null if empty value
         array_replace_empty_with_null( $_POST );

         $this->customer_model->save( $_POST, $id );
         echo "true";
      } else {
         echo "error";
      }
   }

   

   /** ----------------------------------------------------------------------------
    * Returns the correct customer row for customer
    *
    * Display the selected row in json if not empty(var)
    *
    * @param    int     $customerId        Customer id
    * @return   void                            
    *          
    */
   public function get_single_row(){
      $customerId = $_POST['customerId'];
      // Returns a single row object with customer detail
      $customerDetails = $this->customer_model->get( $customerId );
      if ( !empty( $customerDetails ) ) {
         $data[] = $customerDetails;
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
    }

   /** ----------------------------------------------------------------------------
    * Retrieves all row for customer   
    *
    * @return   json        Return json data from database
    *          
    */
   public function get_rows_json() {
      // Get query array of object with customer items
      $customerQuery = $this->customer_model->get();
      // check if data from query is no null
      if ( count( $customerQuery ) ) {  
         foreach ( $customerQuery as $row ) {
            $arrayName[] = array(
            "name"    => $row->name,
            "customer"=> $row->customerType,
            "email"   => $row->email,
            "actions" => '<a href="#modalForm" data-id="' . $row->customerId . '" class="modal-with-form modal-with-zoom-anim edit"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit customer details"></i></a>',
            );
         } 
      } else { // return $arrayName null
         $arrayName = empty( $arrayName );
      }
      header( 'Content-Type: application/json' );
      echo '{ "data": ' . json_encode( $arrayName ) . '}';
   }
   
} 