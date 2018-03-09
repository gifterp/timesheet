<?php
/**
 *  Job View Helper 
 *  Print details of job, cadastral
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
 
 
/** ----------------------------------------------------------------------------
 * Creates the HTML to display job details
 *
 * @param   object    $jobDetails      Job details from database
 * @return  string                     The HTML to use when displaying the job details
 */
function print_job_details_html( $jobDetails, $jobName ) {

   // Format Date Helper
   $CI =& get_instance();
   $CI->load->helper( 'isoft' );


   $jobHTML    = '';
   $street     = '';
   $streetName = '';
   $suburb     = '';
   $postCode   = '';
   $easting    = '';
   $northing   = '';
   $zone       = '';
   $jobType    = '';
   $received   = '';
   $start      = '';
   $finish     = '';
   $tender     = '';
   $budget     = '';
   $po         = '';
   $classJobType     = 'data-empty';
   $classStreet      = 'data-empty';
   $classStreetName  = 'data-empty';
   $classSuburb      = 'data-empty';
   $classPostCode    = 'data-empty';
   $classEasting     = 'data-empty';
   $classNorthing    = 'data-empty';
   $classZone        = 'data-empty';
   $classReceived    = 'data-empty';
   $classStart       = 'data-empty';
   $classFinish      = 'data-empty';
   $classTender      = 'data-empty';
   $classBudget      = 'data-empty';
   $classPO          = 'data-empty';

   
   // If no StreetNo, dont display data with comma
   if( $jobDetails->street != '' ) { 
      $street = $jobDetails->street;
      $classStreet = '';
   }
   // If no StreetName, dont display daata
   if( $jobDetails->streetName != '' ) { 
      $streetName = $jobDetails->streetName; 
      $classStreetName = '';
   }
   // If no Suburb, dont display data with comma
   if( $jobDetails->suburb != '' ) { 
      $suburb = $jobDetails->suburb; 
      $classSuburb = '';
   }
   // If no Post code, dont display postcode 
   if( $jobDetails->jobPostCode != '' && $jobDetails->jobPostCode != 0 ) { 
      $postCode = $jobDetails->jobPostCode; 
      $classPostCode = '';
   }
   // If no Easting, dont display data easting
   if( $jobDetails->easting != '' && $jobDetails->easting != 0 ) { 
      $easting = $jobDetails->easting; 
      $classEasting = '';
   }
   // If no Northing, dont display data northing
   if( $jobDetails->northing != '' && $jobDetails->northing != 0 ) { 
      $northing = $jobDetails->northing;
      $classNorthing = ''; 
   }
   // If no Zone, dont display data zone
   if( $jobDetails->zone != '' && $jobDetails->zone != 0 ) { 
      $zone = $jobDetails->zone; 
      $classZone = '';
   }
   // If no jobType, dont display data job type
   if( $jobDetails->jobType != '' ) { 
      $jobType = $jobDetails->jobType; 
      $classJobType = '';
   }

   // If no received, dont display data received
   if( $jobDetails->received != '' && $jobDetails->received != '0000-00-00' ) { 
      $received = format_db_date( $jobDetails->received ); 
      $classReceived = '';
   }

   // If no start, dont display data start
   if( $jobDetails->start != '' && $jobDetails->start != '0000-00-00' ) { 
      $start = format_db_date( $jobDetails->start ); 
      $classStart = '';
   }

   // If no finish, dont display data finish
   if( $jobDetails->finish != '' && $jobDetails->finish != '0000-00-00' ) { 
      $finish = format_db_date( $jobDetails->finish ); 
      $classFinish = '';
   }

   // If no tender, dont display data tender
   if( $jobDetails->tender != '' && $jobDetails->tender != 0 ) { 
      $tender = $jobDetails->tender; 
      $classTender = '';
   }

   // If no budget, dont display data budget
   if( $jobDetails->jcbudget != '' && $jobDetails->jcbudget != 0 ) { 
      $budget = '$' . trim_trailing_zeros( $jobDetails->jcbudget ); 
      $classBudget = '';
   }

   // If no purchaceOrder, dont display data purchaceOrder
   if( $jobDetails->jcpurchaseOrderNo != '' ) { 
      $po = $jobDetails->jcpurchaseOrderNo; 
      $classPO = '';
   }

    

$jobHTML .=<<<HTML
      <div class="table-info table-responsive">
         <table class="table table-info">
            <tbody>
               <tr class="table-info-darker-row">
                  <td><span class="info-heading">Manager:</span> $jobDetails->manager</td>
                  <td class="$classJobType"><span class="info-heading">Job Type:</span> $jobType</td>
                  <td><span class="info-heading">Department:</span> $jobDetails->departmentName</td>
               </tr>
               <tr>
                  <td colspan="3" class="table-info-heading">Location Details</td>
               </tr>
               <tr>
                  <td class="$classStreetName"><span class="info-heading">Street:</span> $street $streetName</td>
                  <td class="$classSuburb"><span class="info-heading">Suburb:</span> $suburb</td>
                  <td class="$classPostCode"><span class="info-heading">Post Code:</span> $postCode</td>
               </tr>
               <tr>
                  <td class="$classEasting"><span class="info-heading">Easting:</span> $easting</td>
                  <td class="$classNorthing"><span class="info-heading">Northing:</span> $northing</td>
                  <td class="$classZone"><span class="info-heading">Zone:</span> $zone</td>
               </tr>
               <tr>
                  <td colspan="3" class="table-info-heading">Other Details</td>
               </tr>
               <tr>
                  <td class="$classReceived"><span class="info-heading">Received:</span> $received</td>
                  <td class="$classStart"><span class="info-heading">Start:</span>  $start</td>
                  <td class="$classFinish"><span class="info-heading">Finish:</span> $finish</td>
               </tr>
               <tr>
                  <td class="$classTender"><span class="info-heading">Tender:</span> $tender</td>
                  <td class="$classBudget"><span class="info-heading">Budget:</span> $budget</td>
                  <td class="$classPO"><span class="info-heading">PO No:</span> $po</td>
               </tr>
            </tbody>
         </table>
      </div>
HTML;

   return $jobHTML;
}



/** ----------------------------------------------------------------------------
 * Creates the HTML to display child job details
 *
 * @param   object    $childDetails   Child job details from database
 * @return  string                    The HTML to use when displaying the child job details
 */
function print_child_job_details( $childDetails ) {

   $newLine          = "\n";
   $subFilesDetails  = '';
   $CI =& get_instance();
   $CI->load->helper( 'itime' );
   foreach ( $childDetails as $childData ) {
      $subFilesDetails .= '<div class="filtered-list-link" data-id="' . $childData->jobId . '">' . $newLine . 
         format_job_ref_name( $childData->childRef, $childData->childName, $childData->jobReferenceNo, $childData->jobName ) . $newLine
                     . '</div>';
   }
   
   return $subFilesDetails;
}


/** ----------------------------------------------------------------------------
 * Creates the HTML to display parent job details
 *
 * @param   object    $parentDetails  Parent job details from database
 * @return  string                    The HTML to use when displaying the parent job details
 */
function print_parent_job_details( $parentDetails ) {

   $newLine          = "\n";
   $subFilesDetails  = '';
   $CI =& get_instance();
   $CI->load->helper( 'itime' );

      $subFilesDetails .= '<div class="filtered-list-link" data-id="' . $parentDetails->jobId . '">' . $newLine
                     .  format_job_ref_name( $parentDetails->jobReferenceNo, $parentDetails->jobName ) . $newLine
                     . '</div>';
   
   return $subFilesDetails;
}

/** ----------------------------------------------------------------------------
 * Creates the HTML to display customer details
 *
 * @param   object    $customerDetails    Customer details from database
 * @return  string                        The HTML to use when displaying the customer details
 */
function print_customer_job_details( $customerDetails ) {

   $customerCity     = '';
   $customerRegion   = '';
   $customerPostCode = '';
   $customerPhone    = '';
   $customerEmail    = '';
   $classAddress     = 'data-empty';
   $classContact     = 'data-empty';
   if ( $customerDetails->customerAddress != ''  || $customerDetails->customerAddress != null ) {
      $address = $customerDetails->customerAddress . '<br/>';
      $classAddress = '';
   } 

   if ( $customerDetails->customerCity != ''  || $customerDetails->customerCity != null ) {
      $customerCity =  $customerDetails->customerCity;
   } 

   if ( $customerDetails->customerRegion != ''  || $customerDetails->customerRegion != null ) {
      $customerRegion =  $customerDetails->customerRegion;
   }

   if ( $customerDetails->customerPostCode != ''  || $customerDetails->customerPostCode != null ) {
      $customerPostCode =  $customerDetails->customerPostCode;
   } 

   if ( $customerDetails->customerPhone != '' || $customerDetails->customerPhone != null ) {
      $customerPhone =  '<span class="info-heading">Phone:</span> ' . $customerDetails->customerPhone . '<br/>';
      $classContact = '';
   } 

   if ( $customerDetails->customerEmail != ''  || $customerDetails->customerEmail != null ) {
      $customerEmail =  '<span class="info-heading">Email:</span> <a href="mailto:someone@somewhere.com.au">' . $customerDetails->customerEmail . '</a>';
      $classContact = '';
   } 

   $customerHtml = '';
      $customerHtml .=<<<HTML
      <div class="table-info table-responsive">
            <table class="table table-info">
               <tbody>
                  <tr class="table-info-darker-row">
                     <td colspan="3">
                        <span class="info-heading"> $customerDetails->customerName </span>
                        <span class="label label-dark pull-right"> $customerDetails->customerType </span>
                     </td>

                  </tr>
                  <tr>
                     <td class="table-info-heading">Address</td>
                     <td class="table-info-heading">Contact Info</td>
                  </tr>
                  <tr>
                     <td class="$classAddress">
                        $address
                        $customerCity $customerRegion $customerPostCode
                     </td>
                     <td class="$classContact">
                        $customerPhone
                        $customerEmail
                     </td>
                  </tr>
               </tbody>
            </table>
         </div>
HTML;


   return $customerHtml;
}


/** ----------------------------------------------------------------------------
 * Creates the HTML to display for job details header
 *
 * @param   object    $jobDetails    Job details from database
 * @return  string                   The HTML to use when displaying the job details header
 */

function print_job_header_details( $jobHeaderTitle ) {

   $jobHeaderHtml = '';
   $jobHeaderHtml .=<<<HTML
      <div class="widget-summary widget-summary-xs">
         <div class="widget-summary-col widget-summary-col-icon">
            <div class="summary-icon bg-primary">
               <i class="fa fa-folder"></i>
            </div>
         </div>
         <div class="widget-summary-col">
            <div class="summary">
               <h4 class="title job-title ">
                  $jobHeaderTitle
               </h4>
            </div>
         </div>
      </div>
HTML;

   return $jobHeaderHtml;
}


/** ----------------------------------------------------------------------------
 * Creates the HTML to display job cadastral
 *
 * @param   object    $jobCadastralDetails   Job cadastral details from database
 * @return  string                           The HTML to use when displaying the job cadastral details
 */
function print_job_cadastral_details( $jobCadastralDetails ) {

   $cadastralHtml    = '';
   $psNo             = ''; 
   $planningCategory = '';             
   $parishName       = '';       
   $crownAllotment   = '';             
   $section          = '';    
   $township         = '';       
   $lot              = ''; 
   $planNo           = '';    
   $vol1             = ''; 
   $fol1             = ''; 
   $vol2             = ''; 
   $fol2             = ''; 
   $vol3             = ''; 
   $fol3             = '';
   $classPsNo             = 'data-empty'; 
   $classPlanningCategory = 'data-empty';             
   $classParishName       = 'data-empty';       
   $classCrownAllotment   = 'data-empty';             
   $classSection          = 'data-empty';    
   $classTownship         = 'data-empty';       
   $classLot              = 'data-empty'; 
   $classPlanNo           = 'data-empty';    
   $classVol1             = 'data-empty'; 
   $classFol1             = 'data-empty'; 
   $classVol2             = 'data-empty'; 
   $classFol2             = 'data-empty'; 
   $classVol3             = 'data-empty'; 
   $classFol3             = 'data-empty';

   // If no psNo, dont display data 
   if( $jobCadastralDetails->psNo != '' ) { 
      $psNo = $jobCadastralDetails->psNo; 
      $classPsNo = '';
   }
   // If no planningCategory, dont display data 
   if( $jobCadastralDetails->planningCategory != '' ) { 
      $planningCategory = $jobCadastralDetails->planningCategory; 
      $classPlanningCategory = '';
   }
   // If no parishName, dont display data 
   if( $jobCadastralDetails->parishName != '' ) { 
      $parishName = $jobCadastralDetails->parishName;
      $classParishName = '';
   }
   // If no crownAllotment, dont display data 
   if( $jobCadastralDetails->crownAllotment != '' ) { 
      $crownAllotment = $jobCadastralDetails->crownAllotment;
      $classCrownAllotment = '';
   }
   // If no section, dont display data 
   if( $jobCadastralDetails->section != '' ) { 
      $section = $jobCadastralDetails->section;
      $classSection = '';
   }
   // If no township, dont display data 
   if( $jobCadastralDetails->township != '' ) { 
      $township = $jobCadastralDetails->township;
      $classTownship = '';
   }
   // If no lot, dont display data 
   if( $jobCadastralDetails->lot != '' ) { 
      $lot = $jobCadastralDetails->lot;
      $classLot = '';
   }
   // If no planNo, dont display data 
   if( $jobCadastralDetails->planNo != '' ) { 
      $planNo = $jobCadastralDetails->planNo;
      $classPlanNo = '';
   }
   // If no vol1, dont display data 
   if( $jobCadastralDetails->vol1 != '' ) { 
      $vol1 = $jobCadastralDetails->vol1;
      $classVol1 = '';
   }
   // If no fol1, dont display data 
   if( $jobCadastralDetails->fol1 != '' ) { 
      $fol1 = $jobCadastralDetails->fol1;
      $classFol1 = '';
   }
   // If no jvol2 dont display data 
   if( $jobCadastralDetails->vol2 != '' ) { 
      $vol2 = $jobCadastralDetails->vol2;
      $classVol2 = '';
   }
   // If no fol2, dont display data 
   if( $jobCadastralDetails->fol2 != '' ) { 
      $fol2 = $jobCadastralDetails->fol2;
      $classFol2 = '';
   }
   // If no vol3, dont display data
   if( $jobCadastralDetails->vol3 != '' ) { 
      $vol3 = $jobCadastralDetails->vol3;
      $classVol3 = '';
   }
   // If no fol3, dont display data 
   if( $jobCadastralDetails->fol3 != '' ) { 
      $fol3 = $jobCadastralDetails->fol3;
      $classFol3 = '';
   }

   $cadastralHtml .=<<<HTML
      <div class="table-info table-responsive">
         <table class="table table-info">
            <tbody>
               <tr class="table-info-darker-row">
                  <td><span class="info-heading">Council:</span> $jobCadastralDetails->councilName</td>
                  <td class="$classPsNo"><span class="info-heading">P.S. No:</span> $psNo</td>
                  <td class="$classPlanningCategory"><span class="info-heading">Planning Category:</span> $planningCategory</td>
               </tr>
               <tr>
                  <td colspan="3" class="table-info-heading">Parish Particulars</td>
               </tr>
               <tr>
                  <td colspan="2" class="$classParishName"><span class="info-heading">Parish Name:</span> $parishName</td>
                  <td class="$classCrownAllotment"><span class="info-heading">Crown Allotment:</span> $crownAllotment</td>
               </tr>
               <tr>
                  <td colspan="2" class="$classTownship"><span class="info-heading">Township:</span> $township</td>
                  <td class="$classSection"><span class="info-heading">Section:</span> $section</td>
               </tr>
               <tr>
                  <td colspan="3" class="table-info-heading">Parent Title Information</td>
               </tr>
               <tr>
                  <td class="$classLot"><span class="info-heading">Lot:</span> $lot</td>
                  <td colspan="2" class="$classPlanNo"><span class="info-heading">Plan No:</span> $planNo</td>
               </tr>
               <tr>
                  <td class="$classVol1"><span class="info-heading">Vol 1:</span> $vol1</td>
                  <td class="$classFol1"><span class="info-heading">Vol 2:</span> $fol1</td>
                  <td class="$classVol2"><span class="info-heading">Vol 3:</span> $vol2</td>
               </tr>
               <tr>
                  <td class="$classFol2"><span class="info-heading">Fol 1:</span> $fol2</td>
                  <td class="$classVol3"><span class="info-heading">Fol 2:</span> $vol3</td>
                  <td class="$classFol3"><span class="info-heading">Fol 3:</span> $fol3</td>
               </tr>
            </tbody>
         </table>
      </div>
HTML;

   return $cadastralHtml;

}


?>