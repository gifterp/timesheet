<?php
/** 
 *  Timesheets Entry Addition Controller
 *  
 *  Display the admin timesheet entry addtion settings page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */ 
class Timesheet_entry_addition extends Admin_controller {

   public function __construct(){ 
      parent::__construct(); 
      $this->lang->load('timesheet_entry_addition', 'english');
      $this->lang->load('system_settings', 'english');
      $this->load->model( 'admin/timesheet_multiplier_model' );
      $this->load->helper( 'isoft' );
   } 
 


   /** ----------------------------------------------------------------------------
    * Displays the Timesheets entry addiotion
    */
   public function index() {

      $this->data['meta_title']         = lang('timesheet_entry_add_name');
      $this->data['page_title']         = lang('timesheet_entry_add_sect_title');
      $this->data['breadcrumb_items']   = array( lang('system_menu_adm_timesheet_entry_add') );

      // Page specific JavaScript and CSS
      $this->data['pageCustomJS']       = $this->load->view( 'timesheet/admin/entry-addons/_custom-js', '',  true );
      $this->data['pageCustomCSS']       = $this->load->view( 'timesheet/admin/entry-addons/_custom-css', '',  true );
      $this->data['pageVendorCss']      = $this->load->view( 'timesheet/admin/entry-addons/_vendor-css', '',  true );
      $this->data['pageVendorJS']       = $this->load->view( 'timesheet/admin/entry-addons/_vendor-js', '',  true );

       $type = array(
         "hourly"       => "Hourly Rate",
         "multiplier"   => "Multiplier",
         "fixed"        => "Fixed Amount"
      );

       // Create html select option structure starts here
      $this->data['option'] = "[";
      // Loop type data
      foreach ( $type  as $val => $options ) {
         // check if last row
         if( $val == 'fixed' ) {
            $this->data['option'] .= '{"' . $val . '":"' . $options . '"}';
         } else {
            $this->data['option'] .= '{"' . $val . '":"' . $options . '"},';
         }
      }
      $this->data['option'] .="]";
      // Create html select option structure ends here

      $this->load->view('_template/header', $this->data);
      $this->load->view('_template/page-header');
      $this->load->view('timesheet/admin/entry-addons/index', $this->data);
      $this->load->view('_template/sidebar-right');
      $this->load->view('_template/footer');

   }


   /** ----------------------------------------------------------------------------
    * Adds a new timesheet additional entry to the database
    *
    * First it adds the entry to the database, then return true if it success
    * Error if there is an error
    *
    * @param   array    $_POST        The timesheet entry addition details
    * @return  bool     true, error   Display output if success or fail
    */ 
   public function create() {
      // Save timesheet entry addition details in table if $_POST is set
      if ( isset( $_POST ) ) {

         if ( isset( $_POST['timesheetMultiplierId'] ) ) {
          $_POST['description']   = replace_empty_with_null( $_POST['description'] );
            $this->timesheet_multiplier_model->save( $_POST, $_POST['timesheetMultiplierId'] );
         } else {
            $this->timesheet_multiplier_model->save( $_POST ); 
         }
         echo "true";
      } else {
         echo "error";
      }
   }


   /** ----------------------------------------------------------------------------
    * Delete timesheet entry addition to the database
    *
    * @param    array   $_POST      timesheet entry addition id
    * @return   bool    Error       Display if delete fail
    */
   public function delete() {

      if ( isset( $_POST['timesheetMultiplierId'] ) ) {
         $this->timesheet_multiplier_model->delete( $_POST['timesheetMultiplierId'] );
      } else {
         echo "error";
      }
   }

   

   /** ----------------------------------------------------------------------------
    * Returns the correct timesheet entry addition row for timesheet entry addition
    *
    * Display the selected row in json if not empty(var)
    *
    * @param    int     $timesheetMultiplierId        timesheet multiplier id
    * @return   void                            
    *          
    */
   public function get_single_row() {
      $timesheetMultiplierId = $_POST['timesheetMultiplierId'];
      // Returns a single object with timesheet entry addition detail
      $timesheetEntryAdditionDetails = $this->timesheet_multiplier_model->get_timesheet_entry_add_row( $timesheetMultiplierId );
      if ( !empty( $timesheetEntryAdditionDetails ) ) {
         $data[] = $timesheetEntryAdditionDetails;
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Retrieves all row for timesheet entry addition   
    *
    * @return   json        Return json data from database
    *          
    */ 
   public function get_rows_json() {
      // Get query object with timesheet entry addition items
      $timesheetEntryAdditionQuery = $this->timesheet_multiplier_model->get();
      // check if data from query is no null
      if (  !empty( $timesheetEntryAdditionQuery ) ) { 
         foreach (  $timesheetEntryAdditionQuery as $row ) {
            $arrayName[] = array(
            "id"           => $row->timesheetMultiplierId,
            "name"         => $row->name,
            "description"  => $row->description,
            "type"         => $row->type,
            "value"        => '$' . number_format( $row->value, 2 ),
            "actions"      =>'<a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a> <a href="#" class=" on-default remove-icon confirmation-callback" data-id="' . $row->timesheetMultiplierId . '" ><i class="fa fa-trash-o"></i></a><a href="#" class="on-default remove-row row' . $row->timesheetMultiplierId . ' hidden" ><i class="fa fa-trash-o"></i></a>',
            );
         }
      } else { // return $arrayName empty
         $arrayName = empty( $arrayName );
      }

      header( 'Content-Type: application/json' );
      echo '{ "data": ' . json_encode( $arrayName ) . '}';
   }


  

}
