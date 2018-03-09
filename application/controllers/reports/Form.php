<?php
/**
 * Reports Form Controller
 *
 * Allows the user to generate various reports
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Form extends Admin_controller {


   public function __construct() {
      parent::__construct();
      $this->load->model( 'job_model' );
      $this->load->model( 'user_model' );
      $this->load->model( 'reports/mockinvoice_model' );
      $this->lang->load( 'report', 'english' );
      $this->lang->load( 'system', 'english' );
      $this->load->helper( 'isoft' );
      $this->load->helper( 'itime' );
      $this->load->helper( 'report_minv' );
   }


   /** ----------------------------------------------------------------------------
    * Work in progress report
    *
    * Displays a form with parameters to run the WIP report
    */
   public function wip() {

      $this->data['meta_title']         = lang( 'report_wip_name' );
      $this->data['page_title']         = lang( 'report_wip_section_title' );
      $this->data['breadcrumb_items']   = array( lang( 'system_menu_reports_wip' ) );

      $this->data['pageVendorCss']      = $this->load->view( 'reports/forms/_vendor-css', '', true);
      $this->data['pageCustomCss']      = $this->load->view( 'reports/forms/_custom-css', '', true);
      $this->data['pageVendorJS']       = $this->load->view( 'reports/forms/_vendor-js', '', true);
      $this->data['pageCustomJS']       = $this->load->view( 'reports/forms/_custom-js', '', true);

      // List of current jobs/child jobs to be used in select dropdown. Data converted for use by the CI dropdown form helper function
      $this->data['jobArray'] = create_job_key_value_array( $this->job_model->get_list() );

      // List of active users to be used in select dropdown. Data converted for use by the CI dropdown form helper function
      $this->data['userArray'] = create_key_value_array( $this->user_model->get_active_list(), 'userId', 'name' );


      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'reports/forms/wip', $this->data );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );
   }


}