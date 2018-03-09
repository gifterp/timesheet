<?php
/**
 *  Contact Controller
 *  
 *  Display the Contact page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Contact extends Admin_controller {

   
   public function __construct() {
      parent::__construct();
      $this->load->model( 'contact_model' );
      $this->load->helper( 'isoft' );
      $this->lang->load( 'contact', 'english' );
      $this->load->library( 'json_library' );
   }
  


   /** ----------------------------------------------------------------------------
    * View page of contact list     
    */ 
   public function index() { 

      $this->data['meta_title']         = lang( 'contact_name' );
      $this->data['page_title']         = lang( 'contact_sect_title' );
      $this->data['breadcrumb_items']   = array( lang( 'system_menu_contacts' ) );

      $this->data['pageCustomJS']       = $this->load->view( 'contact/_contact-custom-js' , '' , true);
      $this->data['pageVendorCss']      = $this->load->view( '_pageassets/form-vendor-css' , '' , true);
      $this->data['pageVendorJS']       = $this->load->view( '_pageassets/form-vendor-js' , '' , true);

      $this->load->view( '_template/header' , $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'contact/index' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( 'contact/_contact-modal' );
      $this->load->view( '_template/footer' );

   }

   
   /** ----------------------------------------------------------------------------
    * Adds/Update a  contact entry to the database
    *
    * If contact id is set it will update the entry to the database, add if no id is set
    *
    * @param   array    $_POST        The Contact details
    * @return  bool     true, error   Display output if success or fail
    */
   public function save() {
      // Uppercase first letter word
      $_POST['name'] = ucfirst( $_POST['name'] );
      if ( isset( $_POST ) ) {
         // Set id for add 
         $id = null;
         // Set id for update
         if ( $_POST['contactPersonId'] != '' ) {
            $id   = $_POST['contactPersonId'];
         } 
         array_replace_empty_with_null( $_POST );

         $this->contact_model->save( $_POST, $id );
        echo "true";
      }else{
         echo "error";
      }
   }


   

   /** ----------------------------------------------------------------------------
    * Returns the correct contact row for contact
    *
    * Display the selected row in json if not empty(var)
    *
    * @param    int     $contactPersonId        Contact id
    * @return   void                            
    *          
    */ 
   public function get_single_row() {
      $contactPersonId = $_POST['contactPersonId'];
      // Returns a single row object with checklist detail
      $contactDetails = $this->contact_model->get_contact_details( $contactPersonId );
      if ( !empty( $contactDetails ) ) {
         $data[] = $contactDetails;
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Retrieves all row for contact   
    *
    * @return json        Return json data from database
    *          
    */
   public function get_rows_json() {

      // Get query array of object with contact items
      $contactQuery = $this->contact_model->get();

      // check if data from query is no null
      if ( count( $contactQuery ) ) {
         foreach ( $contactQuery as $row ) {
            // Pass data into array
            $arrayName[] = array(
               "name"      => $row->name,
               "company"   => $row->company,
               "phone"     => $row->phone,
               "mobile"    => $row->mobile,
               "email"     => $row->email,
               "actions"   => '<a href="#modalForm" data-id="' . $row->contactPersonId . '" class="modal-with-form modal-with-zoom-anim edit"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit contact details"></i></a>',
            );
         }
      } else { 
         // return $arrayName empty
         $arrayName = empty( $arrayName );
      }

      // Output the array as nested JSON with the title, 'data'.
      // dataTables plugin requires the JSON data to be nested
      $this->json_library->print_array_json( $arrayName, 'data' );
   }

}
