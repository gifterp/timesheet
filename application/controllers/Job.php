<?php
/** 
 *  Job Controller
 *  
 *  Display the job page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */  
class Job extends Admin_controller {
  
   public function __construct() {
      parent::__construct();
      $this->lang->load( 'job', 'english' ); 
      $this->lang->load( 'checklist', 'english' );
      $this->load->helper( 'checklist' ); 
      $this->load->helper( 'job' );
      $this->load->helper( 'isoft' );
      $this->load->helper( 'itime' );
      $this->load->library( 'json_library' );
      $this->load->model( 'job_model' );
      $this->load->model( 'jobnote_model' );
      $this->load->model( 'job_customfields_model' );
      $this->load->model( 'jobinvoice_model' );
      $this->load->model( 'department_model' );
      $this->load->model( 'customer_model' );
      $this->load->model( 'job_type_model' );
      $this->load->model( 'checklist_model');
      $this->load->model( 'council_model');
      $this->load->model( 'checklistitem_model' );
      $this->load->model( 'job_checklist_model' );
      $this->load->model( 'job_checklist_data_model' );
      $this->load->model( 'job_cadastral_model' );
      $this->load->model( 'system_settings_model' );
   }

   /** ----------------------------------------------------------------------------
    * View page of job list     
    */
   public function index() {

      $this->data['meta_title']       = lang( 'job_name' );
      $this->data['page_title']       = lang( 'job_sect_title' );
      $this->data['breadcrumb_items'] = array( lang( 'system_menu_jobs' ) );
      
      $this->data['pageCustomJS']      = $this->load->view( 'job/_job-custom-js', '', true );
      $this->data['pageVendorJS']      = $this->load->view( 'job/_job-vendor-js', '', true );
      $this->data['pageCustomCss']     = $this->load->view( 'job/_job-custom-css', '', true);
      $this->data['pageVendorCss']     = $this->load->view( 'job/_job-vendor-css', '', true );

      // Get query array of object with customer details list
      $this->data['customer']          = $this->customer_model->get();
   
      // Get query array of object with job type list
      $this->data['jobType']           = $this->job_type_model->get();

      // Get query object with user items
      $this->data['user']              = $this->user_model->get_user_list();

      // Get query object with department items
      $this->data['department']        = $this->department_model->get();
      
      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'job/index', $this->data );
      $this->load->view( 'job/_job-add-modal' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );
      
   }

   

   /** ----------------------------------------------------------------------------
    * View page of job list data
    *
    * Summary View of job details, checklist, customer details, sub files,
    * job notes, job invoice
    *    
    */
   public function view( $jobId = '' ) {

      $this->data['meta_title']        = lang( 'job_name' );
      $this->data['page_title']        = lang( 'job_view_title' );
      $this->data['breadcrumb_items']  = array('<a href="' . ROOT_RELATIVE_PATH . 'job">' . lang('system_menu_jobs') . "</a>", lang('job_view_title'));
 

      $this->data['pageCustomJS']      = $this->load->view( 'job/_job-custom-js', '', true );
      $this->data['pageVendorCss']     = $this->load->view( 'job/_job-vendor-css', '', true );
      $this->data['pageVendorJS']      = $this->load->view( 'job/_job-vendor-js', '', true );
      $this->data['pageCustomCss']     = $this->load->view( 'job/_job-custom-css', '', true);
      // Get the job details
      $this->data['jobDetails']        = $this->job_model->get_job_details( $jobId );

      if( $this->data['settings']->hasCadastral == 0 ) {

         $this->data['cadastralPanelHidden']  = 'hidden';
         $this->data['cadastralButtonHidden'] = 'hidden';

      } else {
         // Check if cadastral details are attached to this job
         if ( $this->data['jobDetails']->cadastralId == NULL ) {
            // Hide cadastral panel and display button to add them
            $this->data['cadastralPanelHidden']  = 'hidden';
            $this->data['cadastralButtonHidden'] = '';
         } else {
            // Show the cadastral details
            $this->data['cadastralPanelHidden']  = '';
            $this->data['cadastralButtonHidden'] = 'hidden';
         }
        
      }

      

      // Get query array of object with customer details list
      $this->data['customer']          = $this->customer_model->get();
  
      // Get query array of object with job type list
      $this->data['jobType']           = $this->job_type_model->get();

      // Get query object with department items
      $this->data['department']        = $this->department_model->get();

      // Get query object with user items
      $this->data['user']              = $this->user_model->get_user_list();
      // Create html select option structure starts here
      $this->data['option'] = "[";
      $last = $this->data['user']->num_rows();
      $counter = 1;

      // Loop user result data
      foreach ( $this->data['user']->result()  as $row ) {
         // check if last row
         if( $counter == $last ) {
            $this->data['option'] .= '{"'.$row->userId.'":"'.$row->name.'"}';
         } else {
            $this->data['option'] .= '{"'.$row->userId.'":"'.$row->name.'"},';
         }
         $counter++;
      }
      $this->data['option'] .="]";
      // Create html select option structure ends here

      // Valid id then pass to string jobId
      $this->data['jobId'] =  $jobId;

      // Get query array of object with council details list
      $this->data['council'] = $this->council_model->get();

      // Get query array of object with checklist list
      $this->data['checklist'] = $this->checklist_model->get();
      
      // Get query object with job checklist items
      $this->data['job_checklist']  = $this->job_checklist_model->get_job_checklist_details( $jobId );

      // Get query object with job checklist items
      $this->data['job_checklist']  = $this->job_checklist_model->get_job_checklist_details( $jobId );

      // hide checklist panel on the job view page if there are no checklist
      $this->data['countList']  = $this->data['job_checklist']->num_rows();

      if ( count( $this->data['checklist'] ) == 0 ) {
         $this->data['checklistButtonHidden']  = 'hidden';
          $this->data['checklistPanelHidden']  = 'hidden';
      } else {
         if ( $this->data['countList'] == 0 ) {
            $this->data['checklistPanelHidden']  = 'hidden';
            $this->data['checklistButtonHidden']  = '';
         } else {
            $this->data['checklistPanelHidden']  = '';
            $this->data['checklistButtonHidden']  = 'hidden';
         }
      }

     

      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'job/view', $this->data);
      $this->load->view( 'job/_job-add-checklist-modal' );
      $this->load->view( 'job/_job-add-cadastral-modal' );
      $this->load->view( 'job/_job-add-modal' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );
   }

   /** ----------------------------------------------------------------------------
    * Gets details of  a job and outputs the HTML
    *
    * @param      array       $_POST        Job id
    * @return     html                      HTML for the job details
    */
   public function print_job_details( ) {

     
      $jobId = $this->input->post( 'jobId' );

      // Get the details for this job as an array
      $jobDetails = $this->job_model->get_job_details( $jobId );

      // Set Ref and job name if parent not exist
      $jobName = format_job_ref_name( $jobDetails->jobReferenceNo, $jobDetails->jobName );
      // Call isoft helper function if job parent exist
      if ( $jobDetails->parentJobId != null ) {
         $jobName = format_job_ref_name( $jobDetails->childRef, $jobDetails->childName, $jobDetails->jobReferenceNo, $jobDetails->jobName );
      }

      // Output the job details in html 
      if ( !empty( $jobDetails ) ) {
         $panel  =  print_job_details_html( $jobDetails, $jobName );
         $header = print_job_header_details( $jobName );
 
        

         $result = array( 'header' => $header, 'panel' => $panel );
         echo json_encode( $result );
      }
   }


   /** ----------------------------------------------------------------------------
    * Gets details of  a customer and outputs the HTML
    *
    * @param      array       $_POST        Job id
    * @return     html                      HTML for the customer details
    */
   public function print_customer_details( ) {

      $id   = $this->input->post( 'id' );
      $form   = $this->input->post( 'form' );

      // Check if request is from change customer
      if ( $form == 1 ) {
         // Get the details for this customer as an array for change form
         $customerDetails = $this->customer_model->get_customer_details( $id );
      } else {
         // Get the details for this customer as an array
         $customerDetails = $this->job_model->get_job_details( $id );
      }
      
      // Output the customer details in html 
      if ( !empty( $customerDetails ) ) {
         echo print_customer_job_details( $customerDetails );
      } 

   }

   /** ----------------------------------------------------------------------------
    * Gets details of  a child job and outputs the HTML
    *
    * @param      array       $_POST        parent id
    * @return     html                      HTML for the job details
    */
   public function print_child_job_details( ) {

      $parentJobId   = $this->input->post( 'parentJobId' );
      $haveParent    = $this->input->post( 'haveParent' );


      // Set details if child or parent
      if ( ($haveParent == null && $haveParent == '' && isset( $haveParent )) ||  $haveParent == 'undefined' ) {
          // Get the details for this child job as an array
         $childDetails = $this->job_model->get_child_job_details( $parentJobId );
         // Output the child job details in html 
         if ( !empty( $childDetails ) ) {
            echo print_child_job_details( $childDetails );
         }
      } else {
         // Get the details of parent job 
         $parentDetails = $this->job_model->get_job_details( $parentJobId );
         // Output the child job details in html 
         if ( !empty( $parentDetails ) ) {
            echo print_parent_job_details( $parentDetails );
         }
      }
     
      
   }

   /** ----------------------------------------------------------------------------
    * Gets details of  a job cadastral and outputs the HTML
    *
    * @param      array       $_POST        Cadastral id
    * @return     html                      HTML for the job cadastral details
    */
   public function print_cadastral_details( ) {

      $jobId = $this->input->post( 'jobId' );

      // Get the cadastral for this job as an array
      $cadastralDetails = $this->job_model->get_job_details( $jobId );

      // Output the cadastral in html
      if ( !empty( $cadastralDetails ) ) {
         echo print_job_cadastral_details( $cadastralDetails );
      }
   }


   /** ----------------------------------------------------------------------------
    * Structure a html for dropdown checklist
    *
    * Output json  dropdown forms of job checklist list
    *
    * @param      array       $_POST       Job id
    * @return     json                     Return json data from database of the specific checklistId
    *          
    */
   public function create_dropdown_checklist() {
     
      $jobId = $this->input->post( 'jobId' );
      // Get checklists not already used by this job as a query object
      $checklists = $this->checklist_model->get_unused_checklist( $jobId );
      // Create html select option structure starts here
      $option = '';
      $status = 'List';
      if ( !empty( $checklists ) ) {
         // Loop user result data
         foreach ( $checklists->result() as $row ) {
            $option .= "<option value=" . $row->checklistId . ">" . $row->title . "</option>";
         }
         // if no option value will return empty status to disabled dropdown
         if( $option == '' ) {
            $status = 'Empty';
            $option = "<option value=''>All Checklist is Selected</option>";
         }
      } 

      $result = array( 'option' => $option, 'status' => $status );
      echo json_encode( $result );
     
   } 


   /** ----------------------------------------------------------------------------
    * Gets details of any checklists attached to a job and outputs the HTML
    *
    * @param      array       $_POST        Job id
    * @return     html                      HTML for the checklist forms if there are any
    */
   public function print_checklists( ) {

      $jobId = $this->input->post('jobId');

      // Get the checklists for this job as an array
      $jobChecklists = $this->job_checklist_model->get_all_checklist_details( $jobId );

      // Output the checklist html if this job has them attached
      if ( !empty( $jobChecklists ) ) {
         echo print_checklist_forms( $jobChecklists, $jobId );
      }
   }


   /** ----------------------------------------------------------------------------
    * Gets details of any checklists attached to a job and count it
    *
    * @param      array       $_POST        Job id
    * @return     html                      HTML for the checklist forms if there are any
    */
   public function verify_checklists( ) {

      $jobId = $this->input->post('jobId');

      // Get checklists not already used by this job as a query object
      $checklists = $this->checklist_model->get_unused_checklist( $jobId );

      // Output the checklist html if this job has them attached
      if ( !empty( $checklists ) ) {
         echo count( $checklists->result() );
      }
   }


    /** ----------------------------------------------------------------------------
    * Update job checklist item data to the database        
    *
    * return update when success and error if fail to save
    *
    * @param      array     $_POST         Job id
    * @return     json                     Redraw checklist
    * @return     bool      error          Display if update fail
    */   
   public function update_job_checklist_item_order(){

      // Update job checklist item once $_POST is set
      if ( isset( $_POST ) ) {
         
         $jobId = $_POST['jobId'] ;
         $jobChecklistId =  $this->job_checklist_model->get_display_id( $_POST, $jobId );
         // Update displayOrder base on start order number and end order number
         $this->job_checklist_model->change_display_order( $_POST, $jobId );
         // arrange new display order
         // arrange new display order
         $field   = 'displayOrder';
         $id      = $jobChecklistId->jobChecklistId;
         $where   = "jobId = '" . $jobId . "' ";
         $start   = $_POST['start'];
         $end     = $_POST['end'];
         $this->job_checklist_model->reorder( $field, $where, $start, $end, $id );
         // return html cheklist item view
         echo $this->print_checklists( );
      } else {
         echo "error";
      }
   }

   /** ----------------------------------------------------------------------------
    * Adds/Update a Job to the database
    *
    * First it adds the entry to the database, then return true if it success
    * Error if there is an error
    *
    * @param      array       $_POST      The job details
    * @return     json                    Type of form
    * @return     bool        error       Display once its not saves on database
    */  
   public function save_job() {

      // Save job  once $_POST is set
      if ( isset( $_POST ) ) {
         $isParent = '';
         $customPost = array();

         if( isset( $_POST['isParent'] ) ) {
            $isParent = $_POST['isParent'];
            unset( $_POST['isParent'] );
         }

        
         unset( $_POST['customerType'] );
         



         // Update once jobId is not null
         $id               = null;
         $customFieldsId   = null;

         // Set job form status
         $status     = 'new'; 
         $link       =  ROOT_RELATIVE_PATH . 'job/'; 

         // Set job update form status
         if ( $_POST['jobId'] != '' ) {
            $id      = $_POST['jobId'];
            $status  = 'update';
            $customQuery = $this->job_model->get( $id, TRUE );
            $customFieldsId = $customQuery->jobCustomFieldsId;
            if ( $customFieldsId == 0 ){
               $customFieldsId   = null;
            }
         } 

         // Set null if empty value
         $_POST['parentJobId']  = replace_empty_with_null( $_POST['parentJobId'] );

         if ( isset( $_POST['streetNo'] ) ) { $_POST['streetNo'] = replace_empty_with_null( $_POST['streetNo']  ); }
         if ( isset( $_POST['streetName'] ) ) { $_POST['streetName'] = replace_empty_with_null( $_POST['streetName']  ); }
         if ( isset( $_POST['suburb'] ) ) { $_POST['suburb'] = replace_empty_with_null( $_POST['suburb']  ); }
         if ( isset( $_POST['postCode'] ) ) { $_POST['postCode'] = replace_empty_with_null( $_POST['postCode']  ); }
         if ( isset( $_POST['northing'] ) ) { $_POST['northing'] = replace_empty_with_null( $_POST['northing']  ); }
         if ( isset( $_POST['easting'] ) ) { $_POST['easting'] = replace_empty_with_null( $_POST['easting']  ); }
         if ( isset( $_POST['zone'] ) ) { $_POST['zone'] = replace_empty_with_null( $_POST['zone']  ); }
         if ( isset( $_POST['tender'] ) ) { $_POST['tender'] = replace_empty_with_null( $_POST['tender']  ); }

         // Check if null and create date format for db saving
         if ( isset( $_POST['received'] ) ) { 
            $_POST['received'] = create_int_date_format( replace_empty_with_null( $_POST['received']  ) ); 
         }
         if ( isset( $_POST['start'] ) ) { 
            $_POST['start'] = create_int_date_format( replace_empty_with_null( $_POST['start']  ) ); 
         }
         if ( isset( $_POST['finish'] ) ) { 
            $_POST['finish'] = create_int_date_format( replace_empty_with_null( $_POST['finish']  ) ); 
         }
         if ( isset( $_POST['purchaseOrderNo'] ) && isset( $_POST['budget'] ) ) {
            // Set Data for jobcustom table insert
            $customPost['budget'] = $_POST['budget']; 
            $customPost['purchaseOrderNo'] = $_POST['purchaseOrderNo']; 
            // Execute Query for job custom table
            $_POST['jobCustomFieldsId'] = $this->job_customfields_model->save( $customPost, $customFieldsId );
            // Remove fields in array
            unset( $_POST['purchaseOrderNo'] );
            unset( $_POST['budget'] );
      
         }

            // if ( isset( $_POST['crownAllotment'] ) ) { 
            //    $_POST['crownAllotment'] =  replace_empty_with_null( $_POST['crownAllotment']  ); 
            // }
            // if ( isset( $_POST['section'] ) ) { 
            //    $_POST['section'] =  replace_empty_with_null( $_POST['section']  ); 
            // }
            // if ( isset( $_POST['parish'] ) ) { 
            //    $_POST['parish'] =  replace_empty_with_null( $_POST['parish']  ); 
            // }
            // if ( isset( $_POST['area'] ) ) { 
            //    $_POST['area'] =  replace_empty_with_null( $_POST['area']  ); 
            // }
      

         $jobId = $this->job_model->save( $_POST, $id );

         // Child job save return to parent job page
         if ( $_POST['parentJobId'] != null AND $isParent == null ) {
            $id   = $_POST['parentJobId'];
            $status  = 'child';
         } 
         // Redirect to job view page for new job
         if ( $status == 'new' ) { $link       = ROOT_RELATIVE_PATH . 'job/view/' . $jobId;  }

         $result  = array( 'id' => $id, 'link' => $link, 'type' => 'job', 'status' => $status );
         echo json_encode( $result );

      } else {
         echo "error";
      }
   }

   /** ----------------------------------------------------------------------------
    * Adds/ Update a new Job cadestral to the database
    *
    * First add job cadestral and then return id to save in job table
    * Error if there is an error
    *
    * @param      array       $_POST      The cadastral details
    * @return     json                    Job id, link, type of form
    * @return     bool        error       Display once its not saves on database
    */  
   public function save_job_cadastral() {

      // Save job  once $_POST is set
      if ( isset( $_POST ) ) {
         $jobId = $_POST['jobId'];

         // Set null if empty value
         array_replace_empty_with_null( $_POST );

         if ( empty( $_POST['cadastralId'] ) ){
            unset( $_POST['jobId'] );
            $id = $this->job_cadastral_model->save( $_POST ); 
            $value['cadastralId'] = $id;
            $this->job_model->save( $value, $jobId  ); 
         } else { 
            unset( $_POST['jobId'] );
            $id = $_POST['cadastralId'];
            $this->job_cadastral_model->save( $_POST, $id  );
         }
         
         $result  = array( 'cadastralId' => $id, 'jobId' => $jobId, 'link' => ROOT_RELATIVE_PATH . 'job/', 'type' => 'cadastral' );
         echo json_encode( $result );
      } else {
         echo "error";
      }
   }

   

   /** ----------------------------------------------------------------------------
    * Adds a new Job notes entry to the database
    *
    * First it adds the entry to the database, then return true if it success
    * Error if there is an error
    *
    * @param         array       $_POST         The job note details
    * @return        bool        true, error    Display output if success or fail
    */  
   public function create_job_notes() {

      // Save job notes once $_POST is set
      if ( isset( $_POST ) ) {
            // Change noteDate format to yyyy-mm-dd
            $_POST['noteDate'] = create_int_date_format( $_POST['noteDate'] );
         if ( isset( $_POST['jobNoteId'] ) ) {
            $this->jobnote_model->save( $_POST, $_POST['jobNoteId'] );
         } else {
            $this->jobnote_model->save( $_POST ); 
         }
         echo "true";
      } else {
         echo "error";
      }
   }


   /** ----------------------------------------------------------------------------
    * Adds a new Job checklist to the database
    *
    * First add job checklist and then get all checklist item to save in job checklist data
    * Error if there is an error
    *
    * @param      array       $_POST      The job checklist details
    * @return     json                    Job id, link, type of form
    * @return     bool        error       Display once its not saves on database
    */ 
   public function create_job_checklist() {

      // Saves job checklist once $_POST is set
      if ( isset( $_POST ) ) {
         // Return insert Id after adds new data
         $jobChecklistId = $this->job_checklist_model->save( $_POST );
          // Return latest count of display order
         $result_row = $this->job_checklist_model->get_last_display_order( 'displayOrder', 'jobId', $_POST['jobId'] );
         // Latest count + 1
         $displayOrder = $result_row->displayOrder + 1;     
         $this->job_checklist_model->update_single_field( 'displayOrder', $displayOrder, $jobChecklistId );

         // Array of objects with job checklist data detail
         $checkdataitemQuery  = $this->checklistitem_model->get_checklistitem_details( $_POST['checklistId'] );
            // loop and save checklist data item
            foreach ( $checkdataitemQuery ->result() as $row ) { 
               $dataItem['jobChecklistId'] = $jobChecklistId;
               $dataItem['checklistitemId'] = $row->checklistItemId;
               $this->job_checklist_data_model->save( $dataItem );
            }
         $result  = array( 'id' => $_POST['jobId'], 'link' => ROOT_RELATIVE_PATH . 'job/', 'type' => 'job-checklist' );
         echo json_encode( $result );
      } else {
         echo "error";
      }
   }


   /** ----------------------------------------------------------------------------
    * Save/ Update a  Job checklist data  entry to the database
    *
    * First it adds the entry to the database, 
    * Error if there is an error
    *
    * @param      array       $_POST      The Checklist data details
    * @return     bool        error       Display once its not saves on database
    */   
   public function create_job_checklistdata() {
      // Save job checklist data once $_POST is set
      if ( isset( $_POST ) ) {

         if ( isset( $_POST['dateData'] ) ) {
            // Set value to null if empty
            $_POST['dateData'] = replace_empty_with_null(  $_POST['dateData'] );

            // Change dateDate format to yyyy-mm-dd if not null
            if( !is_null( $_POST['dateData'] ) ) {
               $_POST['dateData'] = create_int_date_format( $_POST['dateData'] );
            }
         }
        
         // Update job checklist data
         $this->job_checklist_data_model->save( $_POST, $_POST['jobChecklistDataId'] );
      } else {
         echo "error";
      }
   }

   /** ----------------------------------------------------------------------------
    * Delete job notes to the database
    *
    * @param      array       $_POST['jobNoteId']  Job note id    
    */
   public function deletenotes() {

      if ( isset( $_POST['jobNoteId'] ) ) {
         // Delete data row on job checklist
         $this->jobnote_model->delete( $_POST['jobNoteId'] );
      } else {
         echo "error";
      }
   }

   /** ----------------------------------------------------------------------------
    * Delete checklist data to the database
    *
    * @param      array       $_POST      Job checklist details         
    */ 
   public function delete_checklist() {

      if ( isset( $_POST['jobChecklistId'] ) ) {

         // Reorder checklist before delete
         $this->job_checklist_model->update_order( $_POST );
         // Delete data row on job
         $this->job_checklist_model->delete( $_POST['jobChecklistId'] );
         // Delete data row on job checklist data
         $this->job_checklist_data_model->delete_job_checklist_data( $_POST['jobChecklistId'] );

         $result  = array( 'id' => $_POST['jobId'], 'link' => ROOT_RELATIVE_PATH . 'job/', 'type' => 'checklist' );
         echo json_encode( $result );
      } else {
       echo "error";
      }
   }


    /** ----------------------------------------------------------------------------
    * Retrieves all row for job notes   
    *
    * @return     json                Return json data from database
    *          
    */
   public function get_notes_rows_json( $id ) {
      // Get query object with job note items
      $jobNoteQuery = $this->jobnote_model->get_job_note_list( $id );
      // Check if data from query is no null
      if ( $jobNoteQuery->num_rows() > 0){  
         $comment_text = '';
         foreach ($jobNoteQuery->result() as $row) {
            // Creates a br for each ; found
            $check = strpos( $row->comment, ';' ); 
               if ( is_int( $check ) ) { 
                  $comment = explode( ';', $row->comment );
                  foreach ( $comment as $row1 ) {
                     $comment_text .= $row1 . '<br>';
                  }
               } else { 
                  // if no ; echo same comment
                  $comment_text = $row->comment;
               }
     
              $arrayName[] = array(
                 "jobNoteId"     => $row->jobNoteId,
                 "noteDate"      => format_db_date( $row->noteDate ),
                 "userId"        => ( $row->firstName . ' ' . $row->surname ),
                 "comment"       => $comment_text,
                  "actions"      =>'<a href="#" class="on-default edit-row-notes"><i class="fa fa-pencil"></i></a> <a href="#" class=" on-default remove-icon-notes confirmation-callback-notes" data-container="body" data-id="' . $row->jobNoteId . '" ><i class="fa fa-trash-o"></i></a><a href="#" class="on-default remove-row-notes row-notes' . $row->jobNoteId . ' hidden" ><i class="fa fa-trash-o"></i></a>',
              );
         }
      } else { // return $arrayName empty
        $arrayName = empty( $arrayName );
      }
      header( 'Content-Type: application/json' );
      echo '{ "data": ' . json_encode( $arrayName ) . '}';

   }

   /** ----------------------------------------------------------------------------
    * Adds a new Job invoice entry to the database
    *
    * First it adds the entry to the database, then return true if it success
    * Error if there is an error
    *
    * @param         array       $_POST         The job invoice details
    * @return        bool        true, error    Display output if success or fail
    */   
   public function create_job_invoice(){

      // Saves job invoice once $_POST is set
      if ( isset( $_POST ) ) {
         // Set value to null if empty
         $_POST['mockInvoiceId'] = replace_empty_with_null(  $_POST['mockInvoiceId'] );

         // Change invoiceDate format to yyyy-mm-dd 
         $_POST['invoiceDate'] = create_int_date_format( $_POST['invoiceDate'] );
        
         if ( isset($_POST['invoiceId'] ) ) {
            $this->jobinvoice_model->save( $_POST, $_POST['invoiceId'] );
         } else {
            $this->jobinvoice_model->save( $_POST ); 
         }
         echo "true";
      } else {
         echo "error";
      }
   }


   /** ----------------------------------------------------------------------------
    * Delete job invoice data to the database
    *
    * @param      array       $_POST      Invoice id          
    */
   public function deleteinvoice() {
      // Delete invoice row once id is set
      if ( isset( $_POST['invoiceId'] ) ) {
         $this->jobinvoice_model->delete( $_POST['invoiceId'] );
      } else {
         echo "error";
      }
   }


   /** ----------------------------------------------------------------------------
    * Retrieves all row for job invoice   
    *
    * @return json        Return json data from database
    *          
    */
   public function get_invoice_rows_json( $id ) {
      // Get query object with job invoice items
      $jobInvoiceQuery = $this->jobinvoice_model->get_job_invoice_list( $id );
      // check if data from query is no null
      if ( $jobInvoiceQuery->num_rows() > 0 ) {  
         foreach ( $jobInvoiceQuery->result() as $row ) {
            $arrayName[] = array(
              "invoiceId"        => $row->invoiceId,
              "invoiceDate"      => format_db_date( $row->invoiceDate ),
              "actualInvoiceNo"  => $row->actualInvoiceNo,
              "amount"           => '$' . $row->amount, 
              "mockInvoiceId"    => '<a href="/reports/mock-invoice?id=' . $row->mockInvoiceId . '" target="_blank">' . $row->mockInvoiceId . '</a>',
              "actions"          =>'<a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a> <a href="#" class=" on-default remove-icon confirmation-callback" data-container="body" data-placement="left" data-id="' . $row->invoiceId . '"  ><i class="fa fa-trash-o"></i></a><a href="#" class="on-default remove-row row' . $row->invoiceId . ' hidden" ><i class="fa fa-trash-o"></i></a>',
            );
         }
      } else { // return $arrayName empty
        $arrayName = empty( $arrayName );
      }

      header( 'Content-Type: application/json' );
      echo '{ "data": ' . json_encode( $arrayName ) . '}';

   } 



   /** ----------------------------------------------------------------------------
    * Retrieves all row for Job   
    *
    * @return     json        Return json data from database
    *          
    */
   public function get_rows_json( $archive = 0 ) {

 
      // Get query object with job items
      // true for call from job section
      $jobQuery = $this->job_model->get_job_list_or_search( TRUE, $archive, '' , null);
      // check if data from query is no null
      if ( count( $jobQuery ) ) {  
         foreach ( $jobQuery as $row ) {
           // Set Ref and job name if parent not exist
            $jobName = format_job_ref_name( $row->jobReferenceNo, $row->jobName );
            $id      = $row->jobId;
            $type    = $row->jobType;
            // Call itime helper function if job parent exist
            if ( $row->childJobId != null ) {
               $id      = $row->cJobId;
               $type    = $row->cjobType;
               $jobName = format_job_ref_name( $row->jobReferenceNo, $row->jobName, $row->childRef, $row->childName );
            }

            $arrayName[] = array(
            "jobId"    => $id,
            "job"      => $jobName,
            "customer" => $row->name,
            "jobType"  => $type,
            "suburb"   => $row->suburb
            );
         } 
      } else { // return $arrayName null
         $arrayName = empty( $arrayName );
      }

      header( 'Content-Type: application/json' );
      echo '{ "data": ' . json_encode( $arrayName ) . '}';
   }

   /** ----------------------------------------------------------------------------
    * Gets details of a single job from the database based on the job id
    *
    * Display the job details in json if not empty(var)
    *
    * @param      array       $_POST         Job id
    * @return     void
    */
   public function get_single_row() {
      $jobId = $this->input->post('jobId');

      // Job details go into an array ready to convert to json
      $data[] = $this->job_model->get_job_details( $jobId );
      $data[0]->received = format_db_date( $data[0]->received );
      $data[0]->start = format_db_date( $data[0]->start );
      $data[0]->finish = format_db_date( $data[0]->finish );
      // Send back the job details as JSON. Error if empty (Should always return a job)
      $this->json_library->print_array_json_unless_empty( $data );
   }


   /** ----------------------------------------------------------------------------
    * Returns the correct cadastral row for job
    *
    * Display the selected row in json if not empty(var)
    *
    * @param      array        $_POST          Cadastral id
    * @return     void                         
    *          
    */
   public function get_cadastral_single_row() {
      $cadastralId = $_POST['cadastralId'];
      // Returns a single row object with checklist detail
      $cadastralDetails = $this->job_cadastral_model->get_cadastral_row( $cadastralId );
      if ( !empty( $cadastralDetails ) ) {
         $data[] = $cadastralDetails;
      }
      // Send back the entry details as JSON. Error if empty (Should always return an entry)
      $this->json_library->print_array_json_unless_empty( $data );
   }

}