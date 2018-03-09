<?php
/**
 * Administration - Timesheet Settings Controller
 *
 * Allows the settings for the timesheets to be modified
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Timesheet_settings extends Admin_controller {


   public function __construct() {
      parent::__construct();
      $this->load->model('timesheet/timesheet_settings_model');
      $this->load->library('json_library');
      $this->lang->load('timesheet', 'english');
   }


   /** ----------------------------------------------------------------------------
    * Displays the timesheet settings page
    */
   public function index() {

      $this->data['meta_title']         = lang('timesheet_adm_set_name');
      $this->data['page_title']         = lang('timesheet_adm_set_sect_title');
      $this->data['breadcrumb_items']   = array( lang('timesheet_adm_set_sect_title') );

      // Page specific JavaScript and CSS
      $this->data['pageVendorJS']       = $this->load->view( 'timesheet/admin/settings/_vendor-js', '', true );
      $this->data['pageCustomJS']       = $this->load->view( 'timesheet/admin/settings/_custom-js', '', true );
      $this->data['pageVendorCss']      = $this->load->view( 'timesheet/admin/settings/_vendor-css', '', true );
      $this->data['pageCustomCss']      = $this->load->view( 'timesheet/_timesheet-custom-css', '', true );


      $this->load->view('_template/header', $this->data);
      $this->load->view('_template/page-header');
      $this->load->view('timesheet/admin/settings/index', $this->data);
      $this->load->view('_template/sidebar-right');
      $this->load->view('_template/footer');

   }


   /** ----------------------------------------------------------------------------
   * Returns the timesheet settings as JSON
   *
   * The settings are stored in the table with an id of 1
   */
   public function get_settings() {

      $settingsArray = $this->timesheet_settings_model->get_settings_array( 1 );

      // Send back the timesheet settings as JSON.
      $this->json_library->print_array_json( $settingsArray );
   }


   /** ----------------------------------------------------------------------------
   * Updates the timesheet settings in the database
   *
   * @param array $_POST      POST array with settings to be updated
   */
   public function update_settings() {

      $this->timesheet_settings_model->save( $_POST, 1 );

      // Send back a simple JSON response to confirm.
      $array[] = array( 'type' => 'Timesheet settings updated' );
      $this->json_library->print_array_json( $array );
   }


   /** ----------------------------------------------------------------------------
   * Gets default settings from database and resets them back to default
   *
   * The default settings are permanently stored as another row with id of 2
   */
   public function reset_default_settings() {

      // Get the default settings from the database
      $row = $this->timesheet_settings_model->get( 2, true );

      // Build an array with the default settings to be restored
      $defaultSettings = [];
      foreach ( $row as $key => $value ) {
         // Add all data except the primary key to the array
         if ( $key != 'timesheetSettingsId' ) {
            $defaultSettings[ $key ] = $value;
         }
      }

      // Update the settings to default values
      $this->timesheet_settings_model->save( $defaultSettings, 1 );

      // Send back a simple JSON response to confirm.
      $array[] = array( 'type' => 'Timesheet settings reset to default' );
      $this->json_library->print_array_json( $array );
   }

}