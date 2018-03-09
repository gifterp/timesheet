<?php
/**
 *  User/Login Controller
 *  
 *  Display the User/Login page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class User extends Admin_controller {

     
   public   $rules_admin = array(
      'username' => array(
         'field' => 'email', 
         'label' => 'Email', 
         'rules' => 'trim|required|valid_email|callback__unique_email|xss_clean'
      ),  
      'password' => array(
         'field' => 'password', 
         'label' => 'Password', 
         'rules' => 'trim|matches[password_confirm]'
      ), 
      'first_name' => array(
         'field' => 'firstname',  
         'label' => 'First Name',  
         'rules' => 'trim|required'
      ), 
      'mid_name' => array(
         'field' => 'midname', 
         'label' => 'Middle Name', 
         'rules' => 'trim|required'
      ), 
      'last_name' => array(
         'field' => 'lastname', 
         'label' => 'Last Name', 
         'rules' => 'trim|required'
      )
   );
    
   public function __construct(){
      parent::__construct();
      $this->lang->load( 'staff', 'english' );
      $this->lang->load( 'login', 'english' );
      $this->load->model( 'user_model' );
      $this->load->helper( 'isoft' );
      $this->load->library( 'json_library' );
   }

   /** ----------------------------------------------------------------------------
    * View page of User list     
    */ 
   public function index(){

      if ( $_SESSION['accessLevel'] < 50 ) {
         redirect( ROOT_RELATIVE_PATH . 'search/' );
      } 

      $this->data['meta_title']         = lang( 'user_name' );
      $this->data['page_title']         = lang( 'user_sect_title' );
      $this->data['breadcrumb_items']   = array( lang( 'system_menu_adm_users' ) );

      $this->data['pageVendorJS']      = $this->load->view( '_pageassets/form-vendor-js', '', true );
      $this->data['pageCustomJS']      = $this->load->view( 'user/_user-custom-js', '', true );
      $this->data['pageVendorCss']     = $this->load->view( '_pageassets/form-vendor-css', '', true );


      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'user/index' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( 'user/_user-modal' );
      $this->load->view( 'user/_user-password-modal' );
      $this->load->view( '_template/footer' );

   }

   

   /** ----------------------------------------------------------------------------
    * Adds/ Update a  user entry to the database
    *
    * If user id is set it will update the entry to the database, add if no id is set
    *
    * @param   array    $_POST        The user details
    * @return  bool     true, error   Display output if success or fail
    */
   public function save() {

      if( isset( $_POST ) ) {
         // Set user id for add
         $id = null;
         // Set null if empty value
         $_POST['email']   = replace_empty_with_null( $_POST['email'] );
         // Update mode
         if( $_POST['userId'] != '' ) {
            // Set user id for update
            $id = $_POST['userId'];
            // Check if update password form is trigger
            if( isset( $_POST['password'] ) ) {
               $_POST['password'] = $this->user_model->hash( $_POST['password'] ); 
               unset( $_POST['confirm_password'] ); 
            } else {
               $_POST['firstName']  = ucfirst( $_POST['firstName'] );
               $_POST['surname']    = ucfirst( $_POST['surname'] );
               $_POST['initials']   = ucfirst( $_POST['initials'] );
            }
         } else {

            $_POST['password'] = $this->user_model->hash( $_POST['password'] );
            $_POST['firstName'] = ucfirst( $_POST['firstName'] );
            $_POST['surname'] = ucfirst( $_POST['surname'] );
            $_POST['initials'] = ucfirst( $_POST['initials'] );

         }
         
         $this->user_model->save( $_POST, $id );
         echo "true";
      } else {
         echo "error";
      }

   }

   

   /** ----------------------------------------------------------------------------
    * Returns the correct user row for user
    *
    * Display the selected row in json if not empty(var)
    *
    * @param  int $_POST['userId']          User id
    * @return void                         
    *          
    */
    public function get_single_row() {
      $userId = $_POST['userId'];
      // Returns a single object with user detail
      $userDetails = $this->user_model->get_user_details( $userId );
      if( !empty( $userDetails ) ) {
         $data[] = $userDetails;
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
    public function get_rows_json(){
      // Get query array of object with user items
      $userQuery = $this->user_model->get();
      // check if data from query is no null
      if ( count( $userQuery ) ) {  
         foreach ( $userQuery as $row ) {
            switch ( $row->accessLevel ) {
               case '99':
                  $type = "Super Admin";
               break;
               case '50':
                  $type = "Admin";
               break;
               case '0':
                  $type = "User";
               break;
            }
            $active = ( ( $row->active == 1 ) ? "Active" : "Inactive" );

            $arrayName[] = array(
              "name"       => $row->firstName . ' ' . $row->surname . ' (' . $row->initials . ')',
              "username"   => $row->username,
              "chargeRate" => $row->chargeRate,
              "accessLevel"=> $type,
              "status"     => $active,
              "actions"    => '<a href="#modalForm" data-id="' . $row->userId . '" class="modal-with-form modal-with-zoom-anim edit"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit user details"></i></a> <a href="#modalFormPassword" data-id="' . $row->userId . '" class="modal-with-form modal-with-zoom-anim update-password"><i class="fa fa-key" data-toggle="tooltip" data-placement="top" title="" data-original-title="Update password"></i></a>',
            );
         }
      }else{ // return $arrayName empty
        $arrayName = empty( $arrayName );     
      }
      header( 'Content-Type: application/json' );
      echo '{ "data": ' . json_encode( $arrayName ) . '}';

    }

   /** ----------------------------------------------------------------------------
    * Login Verification   
    *
    * Verify Account username and password and pass details into session
    *          
    */
   public function login() {

      $this->data['pageVendorJS']      = $this->load->view( '_pageassets/form-vendor-js', '', true );
      $this->data['pageVendorCss']     = $this->load->view( '_pageassets/form-vendor-css', '', true );
      $search = 'search/';

      // Validate if someone is logged in then redirect if session still logged in
      $this->user_model->loggedin() == FALSE || redirect( 'search/' );

      // Set form rules
      $rules = $this->user_model->rules;
      $this->form_validation->set_rules( $rules );
      // Process the form
      if ( $this->form_validation->run() == TRUE ) {
      //  We can login and redirect

         $sess = $this->user_model->login();
         if ( $this->user_model->loggedin() == TRUE ) {
            redirect($search);
         } else {
            // User is inactive
            if( empty ( $sess ) ) { 
               $this->session->set_flashdata( 'error', 'That username/password combination does not exist' );
               redirect( 'user/login', 'refresh' );
               $this->session->flashdata( 'error' );
            } else {
               $this->session->set_flashdata( 'error', 'Your account is inactive' );
               redirect( 'user/login', 'refresh' );
               $this->session->flashdata( 'error' );
            }

         }
      }
      // Load View
      $this->load->view( 'login/index', $this->data );
   }

   /** ----------------------------------------------------------------------------
    * logout and destroy the session       
    */
   public function logout(){ 
      $this->user_model->logout();
      redirect( 'user/login' );
   }






}