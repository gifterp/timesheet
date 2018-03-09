<?php
/**
 *  Controller file that extends to CI controller that hold other functions
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class MY_Controller extends CI_Controller {

   public $data     = array();
   public $settings = array();

   function __construct(){
		parent::__construct();

      $this->load->model( 'admin/system_settings_model' );

      $this->data['errors'] = array();
      $this->data['site_name'] = config_item( 'site_name' );
      $this->data['settings'] = $this->system_settings_model->get( '1' );
      $this->data['meta_title'] = $this->data['settings']->businessName;
}
}