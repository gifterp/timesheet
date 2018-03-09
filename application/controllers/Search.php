<?php
/**
 *  Search Controller
 *  
 *  Display the Search page and handles the AJAX calls to the database
 * 
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */ 
class Search extends Admin_controller {

   public function __construct(){
      parent::__construct(); 
      $this->lang->load( 'system', 'english' );
      $this->lang->load( 'search', 'english' );
      $this->load->model( 'job_model' );
      $this->load->model( 'task_model' );
      $this->load->model( 'timesheet/timesheet_entry_model' );
      $this->load->model( 'timesheet/timesheet_settings_model' );
      $this->load->model( 'timesheet/user_savedjob_model' );
      $this->load->library( 'json_library' );
      $this->load->helper( 'isoft' );
      $this->load->helper( 'itime' );
   }
 


   /** ----------------------------------------------------------------------------
    * View page of advance search with job list     
    */ 
   public function index() {

      $this->data['meta_title']        = lang( 'system_menu_adv_search' );
      $this->data['page_title']        = lang( 'system_menu_adv_search' );
      $this->data['breadcrumb_items']  = array( lang( 'system_menu_adv_search' ) );

        // List of jobs to be used in dropdowns and side list of draggables
      $this->data['jobObjArray']        = $this->job_model->get_list();

      // Page specific JavaScript and CSS
      $this->data['pageVendorJS']       = $this->load->view('search/_search-vendor-js', '', true);
      $this->data['pageCustomJS']       = $this->load->view('search/_search-custom-js', $this->data, true);
      $this->data['pageVendorCss']      = $this->load->view('search/_search-vendor-css', '', true);
      $this->data['pageCustomCss']      = $this->load->view('search/_search-custom-css', '', true);

      $this->data['cadastralPanelHidden']  = '';

      if( $this->data['settings']->hasCadastral == 0 ) {
         $this->data['cadastralPanelHidden']  = 'hidden';
      } 
      
      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'search/index' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );

 
   }


   /** ----------------------------------------------------------------------------
    * Result page of department list     
    */ 
   public function result() {

      $this->data['meta_title']        = lang( 'system_menu_adv_search' );
      $this->data['page_title']        = lang( 'system_menu_adv_search' );
      $this->data['breadcrumb_items']  = array('<a href="' . ROOT_RELATIVE_PATH . 'search">' . lang('system_menu_adv_search') . "</a>", lang('search_result_title'));


      // Page specific JavaScript and CSS
      $this->data['pageVendorJS']       = $this->load->view('search/_search-vendor-js', '', true);
      $this->data['pageCustomJS']       = $this->load->view('search/_search-custom-js', $this->data, true);
      $this->data['pageVendorCss']      = $this->load->view('search/_search-vendor-css', '', true);
      $this->data['pageCustomCss']      = $this->load->view('search/_search-custom-css', '', true);

      // Set query search to null if advance or basic search is not use
      $this->data['querySearch']        = null;
      $this->data['searchPanel']        = 'hidden'; 
      if ( isset( $_POST ) ) {
         $this->data['searchPanel']     = '';
         $this->data['querySearch']     = $_POST;
      }

      $this->data['cadastralPanelHidden']  = '';

      if( $this->data['settings']->hasCadastral == 0 ) {
         $this->data['cadastralPanelHidden']  = 'hidden';
      }
      

      
      $this->load->view( '_template/header', $this->data );
      $this->load->view( '_template/page-header' );
      $this->load->view( 'search/result' );
      $this->load->view( '_template/sidebar-right' );
      $this->load->view( '_template/footer' );


   }



  

   /** ----------------------------------------------------------------------------
    * Retrieves all row for Job , from search basic and adv search  
    *
    * @return     json        Return json data from database
    *          
    */
   public function get_search_result( $searchItem, $archive = 0 ) {
      
      $childarchive     = 0;
      $query            = null;
      $childQuery       = null;
      $finalQuery       = null;
      $childFinalQuery  = null;
      $queryKeyword     = null;
      $cqueryKeyword    = null;
      $keywordCounter   = 0;
      $ckeywordCounter  = 0;
      $distanceQuery    = null;
      $cdistanceQuery   = null;
      $dqCounter        = 0;
      $childDqCounter   = 0;
      $loadAll          = false;
      $valueEasting     = 0;   
      $valueNorthing    = 0;   
      $valueZone        = 0;
      $valueDistance    = 0;
      $cvalueEasting    = 0;   
      $cvalueNorthing   = 0;   
      $cvalueZone       = 0;
      $cvalueDistance   = 0;   

      // Query from Adv Form
      if ( preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $searchItem ) ) {
         // Decode url array from adv post
         $str = urldecode( $searchItem );
         // Convert string to array
         parse_str( $str, $intoArray );
         // Remove array key with null values
         $filterArray = array_filter( $intoArray );
         // Count array
         $len = count( $filterArray );
         // If have search item, create sql condition
         if ( $len != 0 ) {
            $i = 0;
            $x = 0;
            $andParent = 0;
            $andChild = 0;
            // Create Sql condition
            foreach ( $filterArray as $key => $value ) {
               $and = 'AND';
               // Set archive include on, and next loop
               if ( str_replace( "_", ".", $key ) == 'j.archived' && $value == 1 ) {
                  $archive = 1;
                  $i++;  
                  $andParent++;
                  continue;
               }

               // Set search by keyword query
               if ( str_replace( "_", ".", $key ) == 'c.name' ) {
                  $queryKeyword .= "( c.name like '%" . $value . "%' 
                        OR j.jobName like '%" . $value . "%' 
                        OR j.jobReferenceNo like '%" . $value . "%' OR  j.parentJobId IN  
                        (  SELECT jobId FROM time_job j 
                              INNER JOIN time_customer AS c ON j.customerId = c.customerId
                           WHERE
                              c.name like '%" . $value . "%' 
                              OR j.jobName like '%" . $value . "%' 
                              OR j.jobReferenceNo like '%" . $value . "%' ) ) ";   
                  $keywordCounter = 1;
                  $andParent ++;
                  continue;
               }

               // Check if distance query will trigger
               if ( str_replace( "_", ".", $key ) == 'j.easting' || str_replace( "_", ".", $key ) == 'j.zone' || str_replace( "_", ".", $key ) == 'j.northing' || str_replace( "_", ".", $key ) == 'distance') {

                  switch (  str_replace( "_", "", $key ) ) {
                     case 'jeasting':
                        $valueEasting = $value;
                        break;
                     case 'jnorthing':
                        $valueNorthing = $value;
                        break;
                     case 'jzone':
                        $valueZone = $value;
                        break;
                     case 'distance':
                        $valueDistance = $value;
                        break; 
                  }

                   $distanceQuery = ' ( 
                     (  j.easting >= ( ' . $valueEasting . '  - ' . $valueDistance .' ) )  AND (  j.easting <=  ( ' . $valueEasting . '  + ' . $valueDistance . '  )  )
                     AND 
                     (  j.northing >= ( ' . $valueNorthing . ' -  ' . $valueDistance . ' ) )  AND (  j.northing <= ( ' . $valueNorthing . '  +  ' . $valueDistance . '  ) )
                     AND 
                     ( j.zone = ' . $valueZone . ' )
                  ) ';
                  $dqCounter++;  
                  $i++;  
                  $andParent++;
                  continue;
                         
               } else {
                  $query .= str_replace( "_", ".", $key ) . " like '%" . $value . "%' " . $and . " "; 
                  $i++;  
               }

              
                
            }
               // Remove 'AND' if no next value added or keyword and dq is set
               if ( $andParent != 0 && ( $keywordCounter != 1 && $dqCounter != 4 ) ) {
                  $query =  rtrim( $query, "AND " );
               }

               // Join Keyword query
               if ( $keywordCounter == 1 ) {
                  if ( $dqCounter == 4 ) {
                     $query .=  $queryKeyword . 'AND';
                  } else {
                     $query .= $queryKeyword;
                  }
                  
               } 

               // Join Distance query
               if ( $dqCounter == 4 ) {
                  $query .= $distanceQuery;
               } 
 
               // if no search data or area input incomplete
               if ( $query == '' || $query == null ) {
                  $loadAll = true;
                  $finalQuery = null;
               } else {
                  $finalQuery = "(" . $query . ")";
               }
            

            foreach ( $filterArray as $childKey => $childValue ) {
               $and = 'AND';
               // Set archive include on, and next loop
               if ( str_replace( "_", ".", $childKey ) == 'j.archived' && $childValue == 1 ) {
                  $childarchive = 1;
                  $x++;  
                  $andChild++; 
                  continue;
               }

               // Set search by keyword query
               if ( str_replace( "_", ".", $childKey ) == 'c.name' ) {
                  $cqueryKeyword .= "( c.name like '%" . $childValue . "%' 
                        OR j.jobName like '%" . $childValue . "%' 
                        OR j.jobReferenceNo like '%" . $childValue . "%' OR  j.parentJobId IN  
                        (  SELECT jobId FROM time_job j 
                              INNER JOIN time_customer AS c ON j.customerId = c.customerId
                           WHERE
                              c.name like '%" . $childValue . "%' 
                              OR j.jobName like '%" . $childValue . "%' 
                              OR j.jobReferenceNo like '%" . $childValue . "%' ) ) ";   
                  $ckeywordCounter = 1;
                  $andChild++; 
                  continue;
               }

               // Check if distance query will trigger
               if ( str_replace( "_", ".", $childKey ) == 'j.easting' || str_replace( "_", ".", $childKey ) == 'j.zone' || str_replace( "_", ".", $childKey ) == 'j.northing' || str_replace( "_", ".", $childKey ) == 'distance') {

                  switch (  str_replace( "_", "", $childKey ) ) {
                     case 'jeasting':
                        $cvalueEasting = $childValue;
                        break;
                     case 'jnorthing':
                        $cvalueNorthing = $childValue;
                        break;
                     case 'jzone':
                        $cvalueZone = $childValue;
                        break;
                     case 'distance':
                        $cvalueDistance = $childValue;
                        break; 
                  }

                  $cdistanceQuery = ' ( 
                     (  j.easting >= ( ' . $valueEasting . '  - ' . $valueDistance .' ) )  AND (  j.easting <=  ( ' . $valueEasting . '  + ' . $valueDistance . '  )  )
                     AND 
                     (  j.northing >= ( ' . $valueNorthing . ' -  ' . $valueDistance . ' ) )  AND (  j.northing <= ( ' . $valueNorthing . '  +  ' . $valueDistance . '  ) )
                     AND 
                     ( j.zone = ' . $valueZone . ' )
                  ) ';
                  $childDqCounter++; 
                  $andChild++;  
                  $x++;  
                  continue;
                         
               } 
                  $newKey = str_replace( "_", ".", $childKey );

                  $childQuery .= preg_replace( "/j/", "child", $newKey, 1 ) . " like '%" . $childValue . "%' " . $and . " "; 
                  $x++;  
               
                
            }

               // Remove 'AND' if no next value added or keyword and dq is set
               if ( ( $andChild != 0 )  && ( $ckeywordCounter != 1 && $childDqCounter != 4 ) ) {
                  $childQuery =  rtrim( $childQuery, "AND " );
               }

               // Join Keyword query
               if ( $ckeywordCounter == 1 ) {
                  if ( $childDqCounter == 4 ) {
                     $childQuery .= $cqueryKeyword . 'AND';
                  } else {
                     $childQuery .= $cqueryKeyword;
                  }
                  
               } 



               // Join Distance query
               if ( $childDqCounter == 4 ) {
                  $childQuery .= $cdistanceQuery;
               } 

               // if no search data or area input incomplete
               if ( $childQuery == '' || $childQuery == null ) {
                  $childFinalQuery = null;
               } else {
                  $childFinalQuery = "(" . $childQuery . ")";
               }
         }  else { 
            $loadAll = true;
         }
        
      } 



      // Get query object with job items
      // $query is null if request is from basic search
      // $archive include archive job if 1
      $jobQuery = $this->job_model->get_job_list_or_search( $loadAll, $archive, $searchItem, $finalQuery, $childFinalQuery );
      // Check if data from query is no null
      // Check if basic search is null 
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

}
