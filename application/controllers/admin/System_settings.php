<?php
/**
 *  System Settings Controller
 *  
 *  Display the admin system settings page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */ 
class System_settings extends Admin_controller {

   public function __construct() {
      parent::__construct(); 
      $this->lang->load( 'system', 'english' );
      $this->lang->load ( 'system_settings', 'english' );
      $this->load->model( 'admin/system_settings_model' );
      $this->load->library( 'json_library' );
   }
 


   /** ----------------------------------------------------------------------------
    * Displays the System settings
    */
   public function index() {

      $this->data['meta_title']         = lang('system_settings_adm_set_name');
      $this->data['page_title']         = lang('system_settings_adm_set_sect_title');
      $this->data['breadcrumb_items']   = array( lang('system_settings_adm_set_name') );

      // Page specific JavaScript and CSS
      $this->data['pageVendorJS']       = $this->load->view( 'admin/system-settings/_vendor-js', '', true );
      $this->data['pageVendorCss']      = $this->load->view( 'admin/system-settings/_vendor-css', '', true );
      $this->data['pageCustomJS']       = $this->load->view( 'admin/system-settings/_custom-js', '', true );



      $this->load->view('_template/header', $this->data);
      $this->load->view('_template/page-header');
      $this->load->view('admin/system-settings/index', $this->data);
      $this->load->view('_template/sidebar-right');
      $this->load->view('_template/footer');

   }

   public function get_default(){

      $settingsArray = $this->system_settings_model->get();

      // Send back the system settings as JSON.
      $this->json_library->print_array_json( $settingsArray );

   }

   /** ----------------------------------------------------------------------------
   * Updates the system settings in the database
   *
   * @param array $_POST      POST array with settings to be updated
   */
   public function update_settings() {

      $this->system_settings_model->save( $_POST, 1 );

      // Send back a simple JSON response to confirm.
      $array[] = array( 'type' => 'System settings updated' );
      $this->json_library->print_array_json( $array );
   }


  

}
