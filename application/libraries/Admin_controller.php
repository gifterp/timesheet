<?php
/**
 *  Controller file that extends to my controller that hold dynamic functions
 *
 * 
 *
 * @author      John Gifter C Poja <gifterphpdev@gmail.com>
 * @copyright   Copyright (c) 2016 Genesis Software. <https://genesis.com>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Admin_controller extends MY_Controller {

  function __construct(){
      parent::__construct();
      
      $this->load->helper('form');
      $this->load->library('form_validation');
      $this->load->library('session'); 
      $this->load->library('pagination');
      $this->load->helper('directory'); 
      $this->load->model('user_model'); 





        
  

      //Login Check
      $exception_uris = array(
         'user/login', 
         'user/logout'
      );
      if (in_array(uri_string(), $exception_uris) == FALSE) {
         if ($this->user_model->loggedin() == FALSE) {
            redirect('user/login');
         }
      }
      $this->data['pageVendorJS'] = '';
      $this->data['pageCustomJS'] = '';
      $this->data['pageVendorCss'] = '';
      $this->data['pageCustomCss'] = '';
         
      
   }

   


   
}