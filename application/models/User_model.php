<?php
/**
 * User Model
 *
 * Handles login/logout as well as general requests to the user table in the database
 *
 * @author      John Gifter C Poja <gifterphpdev@gmail.com>
 * @copyright   Copyright (c) 2016 Genesis Software. <https://genesis.com>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class User_model extends MY_Model {

      protected $_table_name = "time_user";
      protected $_order_by = 'userId';
      protected $_primary_key = "userId";
      public $rules = array(
         'username' => array(
            'field' => 'username',
            'label' => 'Username' , 
            'rules' => 'trim|required'
         ),
         'password' => array(
            'field' => 'password',
            'label' => 'Password' , 
            'rules' => 'trim|required'
         )

      );

      function __construct(){
         parent::__construct();
      }
   

      /** ----------------------------------------------------------------------------
      * Validate and check login details then put details into session     
      *
      * @return array          User Details
      */
      public function login() {

         $user = $this->get_by(array(
               'username' => $this->input->post('username'),
               'password' => $this->hash( $this->input->post('password') ),
            ), TRUE);

         if ( count( $user ) ) {
            // return 1 to indicate account is inactive
            if ( $user->active == 0 ) { 
               return 1; 
            } else {
               //log in user if active
               $data = array(
                  'name'         => $user->surname . ", " . $user->firstName,
                  'fullName'     => $user->firstName . " " . $user->surname,
                  'userId'       => $user->userId,
                  'accessLevel'  => $user->accessLevel,
                  'loggedin'     => TRUE,
               );
               // Will check remember me is checked and set a session to 30days
               if ( $this->input->post('rememberme') ) { 
                  $this->session->sess_expiration = 2592000;   
               // if no remember me session will be for 2 hours
               } else { 
                  $this->session->sess_expiration = 7200;   
               }

               $this->session->set_userdata( $data );

               return $data;
            }
            
         } else { 
            // return empty for incorect username or password
            return $user;
         }
      }

   
      /** ----------------------------------------------------------------------------
      * destroy session             
      */
      public function logout() {

         $this->session->sess_destroy();
      }


      /** ----------------------------------------------------------------------------
      * Gets user details with specific user id
      *
      * @param  int     $userId                 User id
      * @return object                          User details
      */
      public function get_user_details( $userId ) {

         $sql = " SELECT * 
                  FROM time_user 
                  WHERE userId =  '" . $userId . "' ";
         return $this->db->query( $sql )->row();
      }


      /** ----------------------------------------------------------------------------
      * Check if user is logged in               
      */
      public function loggedin() {

         return ( bool ) $this->session->userdata( 'loggedin' );
      }


      /** ----------------------------------------------------------------------------
      * hash the password in sha512 encryption            
      */
      public function hash( $string ) {

         return hash( 'sha512', $string.config_item( 'encryption_key' ) );
      }


      /** ----------------------------------------------------------------------------
      * Gets the name and id of users
      *
      * @return object          User Id, Name
      */
      public function get_user_list() {

         $sql = " SELECT userId, concat(firstName,  ' ', surname) AS `name`
                  FROM time_user";        
         return $this->db->query( $sql );
      }


      /** ----------------------------------------------------------------------------
      * Gets a list of active users with their basic details
      *
      * This is used when needing to list users Eg. SELECT dropdown
      *
      * @return  array (of objects)          User details
      */
      public function get_active_list() {

         $sql = " SELECT userId, concat(firstName,  ' ', surname) AS `name`, initials
                  FROM time_user
                  WHERE active = 1
                  ORDER BY firstname, surname";
         return $this->db->query( $sql )->result_array();
      }



}