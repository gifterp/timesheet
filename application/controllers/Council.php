<?php
/**
 *  Council Controller
 *  
 *  Display the Council page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Council extends Admin_controller {


    public function __construct(){
      parent::__construct();
      $this->lang->load('council', 'english');
      $this->load->library('json_library');
      $this->load->model('council_model');
    }
 


   /** ----------------------------------------------------------------------------
    * View page of council list     
    */
   public function index(){ 

      $this->data['meta_title']         = lang( 'council_name' );
      $this->data['page_title']         = lang( 'council_sect_title' );
      $this->data['breadcrumb_items']   = array( lang( 'system_menu_adm_councils' ) );
   
      $this->data['pageCustomJS']       = $this->load->view( 'council/_council-custom-js', '', true );
      $this->data['pageVendorJS']       = $this->load->view( '_pageassets/form-vendor-js', '', true );
      $this->data['pageVendorCss']      = $this->load->view( '_pageassets/form-vendor-css', '', true );


      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'council/index' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( 'council/_council-modal' );
      $this->load->view( '_template/footer' );

   }

   
 

   /** ----------------------------------------------------------------------------
    * Adds/Update a  council entry to the database
    *
    * If council id is set it will update the entry to the database, add if no id is set
    *
    * @param   array    $_POST        The council details
    * @return  bool     true, error   Display output if success or fail
    */
   public function save() {
      // Uppercase first letter word
      $_POST['name'] = ucfirst( $_POST['name'] );
      if ( isset( $_POST ) ) {

         // Set id for add 
         $id = null;
         // Set id for update
         if ( $_POST['councilId'] != '' ) {
            $id   = $_POST['councilId'];
         } 

         $this->council_model->save( $_POST, $id );
         echo "true";
      } else {
         echo "error";
      }
   }
       

  

   /** ----------------------------------------------------------------------------
    * Returns the correct council row for council
    *
    * Display the selected row in json if not empty(var)
    *
    * @param    int     $councilId        Council id
    * @return   void                            
    *          
    */        
   public function get_single_row(){
      $councilId = $_POST['councilId'];
      // Returns a single row object with council detail
      $councilDetails = $this->council_model->get_council_details( $councilId );
      if ( !empty( $councilDetails ) ) {
         $data[] = $councilDetails;
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Retrieves all row for council   
    *
    * @return json        Return json data from database
    *          
    */
   public function get_rows_json() {
      // Get query array of object with council items
      $councilQuery = $this->council_model->get();
      // check if data from query is no null
      if ( count( $councilQuery ) ) { 
         foreach ( $councilQuery as $row ) {
            // Pass data into array
            $arrayName[] = array(
            "name"      => $row->name,
            "actions"   => '<a href="#modalForm" data-id="' . $row->councilId . '" class="modal-with-form modal-with-zoom-anim edit"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit deparment details"></i></a>',
            ); 
         }
      } else { // return $arrayName empty
         $arrayName = empty( $arrayName );
      }
      header('Content-Type: application/json');
      echo '{ "data": ' . json_encode( $arrayName ) . '}';
   }

}
