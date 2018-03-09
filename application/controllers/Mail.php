<?php 
/**
 *  Mail Controller
 *  
 *  Display the Mail page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Mail extends Admin_controller {

   
    public function __construct(){
      parent::__construct();
      $this->lang->load( 'mail', 'english' );
      $this->load->model( 'mail_model' );
      $this->load->helper( 'isoft' );
      $this->load->library( 'json_library' );
    }
  


   /** ----------------------------------------------------------------------------
    * View page of mail list     
    */   
   public function index(){ 

      $this->data['meta_title']         = lang( 'mail_name' );
      $this->data['page_title']         = lang( 'mail_sect_title' );
      $this->data['breadcrumb_items']   = array( lang( 'mail_name' ) );

      $this->data['pageCustomJS']       = $this->load->view( 'mail/_mail-custom-js', '',  true );
      $this->data['pageCustomCSS']       = $this->load->view( 'mail/_mail-custom-css', '',  true );
      $this->data['pageVendorCss']      = $this->load->view( '_pageassets/form-vendor-css', '',  true );
      $this->data['pageVendorJS']       = $this->load->view( 'mail/_mail-vendor-js', '',  true );
   

      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'mail/index' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );

   }

   

   /** ----------------------------------------------------------------------------
    * Adds a new mail entry to the database
    *
    * First it adds the entry to the database, then return true if it success
    * Error if there is an error
    *
    * @param   array    $_POST        The mail details
    * @return  bool     true, error   Display output if success or fail
    */ 
   public function create() {
      // Save Mail details in table if $_POST is set
      if ( isset( $_POST ) ) {
         // Change sendDate format to yyyy-mm-dd
         $_POST['sendDate'] = create_int_date_format( $_POST['sendDate'] );

         if ( isset( $_POST['mailId'] ) ) {
            $this->mail_model->save( $_POST, $_POST['mailId'] );
         } else {
            $this->mail_model->save( $_POST ); 
         }
         echo "true";
      } else {
         echo "error";
      }
   }


   /** ----------------------------------------------------------------------------
    * Delete mail to the database
    *
    * @param    array   $_POST      Mail id
    * @return   bool    Error       Display if delete fail
    */
   public function delete() {

      if ( isset( $_POST['mailId'] ) ) {
         $this->mail_model->delete( $_POST['mailId'] );
      } else {
         echo "error";
      }
   }

   

   /** ----------------------------------------------------------------------------
    * Returns the correct mail row for mail
    *
    * Display the selected row in json if not empty(var)
    *
    * @param    int     $mailId        Mail id
    * @return   void                            
    *          
    */
   public function get_single_row() {
      $mailId = $_POST['mailId'];
      // Returns a single object with mail detail
      $mailDetails = $this->mail_model->get_mail_row( $mailId );
      if ( !empty( $mailDetails ) ) {
         $data[] = $mailDetails;
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Retrieves all row for mail   
    *
    * @return   json        Return json data from database
    *          
    */ 
   public function get_rows_json() {
      // Get query object with mail items
      $mailQuery = $this->mail_model->get_mail_list();
      // check if data from query is no null
      if (  !empty( $mailQuery ) ) { 
         foreach (  $mailQuery as $row ) {
            list( $year, $month, $day ) = explode( '-', $row->sendDate );
            $arrayName[] = array(
            "id"          => $row->mailId,
            "sendDate"    => $day . '/' . $month . '/' . $year,
            "recipient"   => $row->recipient,
            "description" => $row->description,
            "actions"     =>'<a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a> <a href="#" class=" on-default remove-icon confirmation-callback" data-id="' . $row->mailId . '"><i class="fa fa-trash-o"></i></a><a href="#" class="on-default remove-row row' . $row->mailId . ' hidden" ><i class="fa fa-trash-o"></i></a>',
            );
         }
      } else { // return $arrayName empty
         $arrayName = empty( $arrayName );
      }

      header( 'Content-Type: application/json' );
      echo '{ "data": ' . json_encode( $arrayName ) . '}';
   }

}
