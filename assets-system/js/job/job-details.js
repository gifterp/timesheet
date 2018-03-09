/**
 * Job Details Object
 *
 * This object handles the manipulation of data in job section view of job details and cadastral details.
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

var JD = {

   // Load html cadastral details in the panel
   loadCadastralDetails: function( cadastralId, jobId, ajaxUrl, errorMsg, errorNotif ) { 
      if ( cadastralId != 0 ) {
         if ( cadastralId == '' ) {

            $('.cadastral-panel').addClass('hidden');
            $('.cadastral-buton').removeClass('hidden');

         } else {

            $('.cadastral-panel').removeClass('hidden');
            $('.cadastral-button').addClass('hidden');

            $.ajax({
               type:'POST',
               url: ajaxUrl + "print_cadastral_details",
               data: 'jobId=' + jobId,
               cache: false,
               success: function( cadastralHtml ) {
                  $( '.job-cadastral' ).html( cadastralHtml );
               },
               error: function( e ) {
                  UN.displayNotice( errorNotif, errorMsg, 'error') ;
               }
            });

         }  
      } 
      
   },


   // Get data from database and display on the edit cadastral modal form
   loadJobCadastralForm: function( cadastralId, ajaxUrl, errorMsg, errorNotif ) {

         $.ajax({
            type:'POST',
            url: ajaxUrl + "get_cadastral_single_row",
            data: 'cadastralId=' + cadastralId,
            cache: false,
            dataType: 'json',
            success: function( data ) {
               $.each( data, function( key, element ) {
                  if ( element != null ) {
                     $( "#cadastralId" ).val( element.cadastralId );
                     $( "#councilId" ).val( element.councilId );
                     $( "#psNo" ).val( element.psNo );
                     $( "#planningCategory" ).val( element.planningCategory );
                     $( "#parishName" ).val( element.parishName ); 
                     $( "#crownAllotment" ).val( element.crownAllotment ); 
                     $( "#section" ).val( element.section );
                     $( "#township" ).val( element.township ); 
                     $( "#lot" ).val( element.lot ); 
                     $( "#planNo" ).val( element.planNo );
                     $( "#vol1" ).val( element.vol1 ); 
                     $( "#vol2" ).val( element.vol2 ); 
                     $( "#vol3" ).val( element.vol3 );
                     $( "#fol1" ).val( element.fol1 ); 
                     $( "#fol2" ).val( element.fol2 ); 
                     $( "#fol3" ).val( element.fol3 ); 
                  }
                  
               });
            },
            error: function( e ) {
               UN.displayNotice( errorNotif, errorMsg, 'error' );
            }
         }); 
      
   },

   // Load html job details in the panel
   loadJobDetails: function( jobId, ajaxUrl, errorMsg, errorNotif ) { 

      $.ajax({
         type:'POST',
         url: ajaxUrl + "print_job_details",
         data: 'jobId=' + jobId,
         cache: false,
         success: function( data ) {
            jobHtml = JSON.parse(data);
            // Html for job panel details
            $( '.job-panel-details' ).html( jobHtml.panel );
            // Html for job top header details
            $( '.job-header' ).html( jobHtml.header );
         },
         error: function( e ) {
            UN.displayNotice( errorNotif, errorMsg, 'error');
         }
      });  

   },

   

   // Load html customer details in the panel
   loadCustomerDetails: function( id, ajaxUrl, form = 0, errorMsg, errorNotif ) { 

      $.ajax({
         type:'POST',
         url: ajaxUrl + "print_customer_details",
         data: 'id=' + id + '&form=' + form,
         cache: false,
         success: function( customerHtml ) {
            if ( form != 0 ) {
                $( '.customer-form-details' ).html( customerHtml );
            } else {
                $( '.customer-panel-details' ).html( customerHtml );
            }
           
         },
         error: function( e ) {
            UN.displayNotice( errorNotif, errorMsg, 'error');
         }
      });  

   },


   // Load html job sub files in the panel
   loadSubFilesDetails: function( parentJobId, ajaxUrl, haveParent, errorMsg, errorNotif ) { 

      // Set parent job id if job have parent
      if ( haveParent != '' && haveParent != null ){
         parentJobId = haveParent;
      } 

      $.ajax({
         type:'POST',
         url: ajaxUrl + "print_child_job_details",
         data: 'parentJobId=' + parentJobId + '&haveParent=' + haveParent,
         cache: false,
         success: function( subFilesHtml ) {

            if ( subFilesHtml == '' ) { subFilesHtml = "<p class='text-center'><strong>There is currently nothing to display</strong></p>"; }

            $( '.sub-files-details' ).html( subFilesHtml );
            $( '#parentJobId' ).val( null );
            $( '#jobId' ).val( null );
         },
         error: function( e ) {
            UN.displayNotice( errorNotif, errorMsg, 'error');
         }
      });  

   },

   // Get data from database and display on the edit job modal form
   loadJobDetailsFormData: function( jobId, ajaxUrl, errorMsg, errorNotif ) {

      $.ajax({
         type:'POST',
         url: ajaxUrl + "get_single_row",
         data: 'jobId=' + jobId,
         cache: false,
         dataType: 'json',
         success: function( data ) {
            $.each( data, function( key, element ) {
               $( "#jobId" ).val( element.jobId );
               $( "#customerId" ).val( element.customerId );
               $( "#jobName" ).val( element.jobName );
               $( ".chosen-select-edit" ).val( element.customerId ).trigger( "chosen:updated" );
               $( "#jobReferenceNo" ).val( element.jobReferenceNo );
               $( "#job-type" ).val( element.jobType ); 
               $( "#jobType" ).val( element.jobType ); 
               $( "#streetNo" ).val( element.street );
               $( "#userId" ).val( element.userId ); 
               $( "#departmentId" ).val( element.departmentId ); 
               $( "#streetName" ).val( element.streetName ); 
               $( "#suburb" ).val( element.suburb ); 
               $( "#postCode" ).val( element.jobPostCode );
               $( "#easting" ).val( element.easting );
               $( "#northing" ).val( element.northing );
               $( "#zone" ).val( element.zone );
               $( "#budget" ).val( element.budget );
               $( "#purchaseOrderNo" ).val( element.purchaseOrderNo ); 
               $( "#tender" ).val( element.tender ); 
               $( "#received" ).val( element.received );
               $( "#start" ).val( element.start ); 
               $( "#finish" ).val( element.finish );
            });
         },
         error: function( e ) {
            UN.displayNotice( errorNotif, errorMsg, 'error' );
         }
      });
   }

};