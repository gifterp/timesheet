<?php
/**
 * Print Report Controller
 *
 * Displays the report page 
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Printing extends Admin_controller {


   public function __construct() {
      parent::__construct();
      $this->load->model( 'job_model' );
      $this->load->model( 'user_model' );
      $this->load->model( 'timesheet/timesheet_entry_model' );
      $this->load->model( 'reports/mockinvoice_model' );
      $this->load->model( 'reports/mockinvoice_row_model' );
      $this->lang->load( 'report', 'english' );
      $this->lang->load( 'system', 'english' );
      $this->load->helper( 'isoft_helper' );
      $this->load->helper( 'itime_helper' );
      $this->load->helper( 'report_print_helper' );
      $this->load->library( 'json_library' );
   }


   public function wip() {
      
      $this->data['reportEntries'] =  $this->timesheet_entry_model->get_wip_entries(
            create_int_date_format( $this->input->post('startDate') ),
            create_int_date_format( $this->input->post('endDate') ),
            $this->input->post('jobSelect'),
            $this->input->post('userSelect'),
            $this->input->post('filter'),
            $this->input->post('sortOrder') );


      $this->data['meta_title']         = lang( 'report_wip_name' );
      $this->data['page_title']         = lang( 'report_wip_section_title' );
      $this->data['breadcrumb_items']   = array( lang( 'system_menu_reports_wip' ) );

      $this->data['pageVendorCss']      = $this->load->view( 'reports/forms/_vendor-css', '', true);
      $this->data['pageCustomCss']      = $this->load->view( 'reports/forms/_custom-css', '', true);
      $this->data['pageVendorJS']       = $this->load->view( 'reports/forms/_vendor-js', '', true);
      $this->data['pageCustomJS']       = $this->load->view( 'reports/forms/_custom-js', '', true);

    


      
      $this->load->view( 'reports/print/wip-print',$this->data);
      
   }




}