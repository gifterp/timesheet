<?php
/**
* Custom JavaScript for the job section 
*
* Copyright (c) 2016 Improved Software. All rights reserved
* Author:       John Gifter Poja <gifter@improvedsoftware.com.au>
* Requires:     [Vendor] dataTables JS + CSS
*               [Vendor] Magnific Popup JS + CSS
*               [Vendor] PNotify CSS
*/
?>      <!-- Custom -->
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/job/job-checklist-ajx.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/job/dnd-job-checklist.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/db/ajax-db.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/form-modal.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/date-eu.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/job/job-notes-custom.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/job/job-invoice-custom.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/job/job-details.js"></script>

   
 
   <script type="text/javascript">
      $( document ).ready(function(){
         var ajax = '/job/get-rows-json/';
         
         var jobsId        = $( 'input[name="jobId"]' ).val();
         var link          = "<?php echo ROOT_RELATIVE_PATH; ?>job/";
         var error         = $( '#error-form-job-checklist' ).val();
         var cadastralId   = $( '#cadastralId' ).val();
         var hasCadastral  = $( '#hasCadastral' ).val();
         var haveParent    = $( '#checkFiles' ).val();
         var isArchived    = $( '#isArchived' ).val();

         // Set Archive switch
         if ( isArchived == 0   ) {
            $( '.archived-off' ).removeClass( 'hidden' );
            $( '.archived-on' ).addClass( 'hidden' );
         } else {
            if( $( "#archived" ).prop( 'checked' ) == true ) {
               $( "#archived" ).prev( '.ios-switch' ).trigger( 'click' );
            }
            $( '.archived-off' ).addClass( 'hidden' );
            $( '.archived-on' ).removeClass( 'hidden' );
         }

         $( '#arc' ).change( function() {
            if ( $( this ).prop( 'checked' ) ) {
               ajax = '/job/get-rows-json/1'; 
               table.ajax.url( ajax ).load();
            } else {
               ajax = '/job/get-rows-json/';
               table.ajax.url( ajax ).load();
            }
         });

         table = $( '#job-editable' ).DataTable({
            "autoWidth": false,
            "language": {
                  "infoEmpty": "Showing 0 to 0 of 0 entries",
                  "emptyTable": "<b><?php echo lang( "system_msg_nothing_display" ); ?></b>",
            },
            "columns": [
                  { "name": "Id", "data": "jobId", "visible": false, "defaultContent": "" },
                  { "name": "Customer", "data": "customer", "orderable": true },
                  { "name": "Job", "data": "job", "orderable": true },
                  { "name": "Job Type", "data": "jobType", "orderable": true },
                  { "name": "Suburb", "data": "suburb", "orderable": true }
               ],
            "order": [],
            "ajax": ajax
         });

         // Initialise our Chosen select inputs
         $( ".chosen-select" ).chosen({
            width: "80%"
         });

         // Initialise our Chosen select inputs
         $( ".chosen-select-edit" ).chosen({
            width: "100%"
         });

         jobCallback = { 

            // Job

            // Success callback: Add Job Section
            addJobSuccess: function( data ) {
                value = JSON.parse( data );
               // Redirect to job view page
               window.location.href = value.link
            },

            // Error callback: Add Job Section
            addJobError: function( data ) {
               // Return to job data table
               table.ajax.reload();
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'job_add_error' ); ?>", "error" );
            },

            // Success callback: Update Job Section
            updateJobSuccess: function( data ) {
               value = JSON.parse( data );
               // Load job details on panel
               JD.loadJobDetails( value.id, value.link );  
               // Load customer details on panel
               JD.loadCustomerDetails( value.id, value.link, 0 );
               // Load subfiles details on panel
               JD.loadSubFilesDetails( value.id, value.link, haveParent ); 
               // Show Notification Success
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'job_edit_success' ); ?>", "success" );
            },

            // Error callback: Update Job Section
            updateJobError: function( data ) {
               // Show Notification Error
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'job_edit_error' ); ?>", "error" );
            },

            // Child Job

            // Success callback: Add Child Job Panel
            addChildJobSuccess: function( data ) {
                value = JSON.parse( data );
               // Load subfiles details on panel
               JD.loadSubFilesDetails( value.id, value.link ); 
               // Show Notification Success
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'job_notif_add_child_success' ); ?>", "success" );
            },

            // Error callback: Add Child Job Panel
            addChildJobError: function( data ) {
               // Show Notification Error
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'job_notif_add_child_error' ); ?>", "error" );
            },

            // Cadastral

            // Success callback: Add Job Cadastral Panel
            addJobCadastralSuccess: function( data ) {
               value = JSON.parse( data );
               // Load cadastral details on the panel
               JD.loadCadastralDetails( value.cadastralId, value.jobId, value.link );  
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'job_notif_cadastral_add_success' ); ?>", "success" );
            },

            // Error callback: Add Job Cadastral  Panel
            addJobCadastralError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'job_notif_cadastral_add_error' ); ?>", "error" );
            },

            // Success callback: Update Job Cadastral Panel
            updateJobCadastralSuccess: function( data ) {
               value = JSON.parse( data );
               // Load cadastral details on the panel
               JD.loadCadastralDetails( value.cadastralId, value.jobId, value.link );  
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'job_notif_cadastral_edit_success' ); ?>", "success" );
            },

            // Error callback: Update Job Cadastral  Panel
            updateJobCadastralError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'job_notif_cadastral_edit_error' ); ?>", "error" );
            },

            // Checklist

            // Success callback: Add Job Checklist Panel
            addJobChecklistSuccess: function( data ) {
               value = JSON.parse( data );
               // Load job checklist details on the panel
               JCL.loadJobChecklistFormData( value.id, value.link );  
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'job_notif_checklist_add_success' ); ?>", "success" );
            },

            // Error callback: Add Job Checklist  Panel
            addJobChecklistError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'job_notif_checklist_add_error' ); ?>", "error" );
            },

            // Success callback: Add Job Checklist Panel
            updateJobChecklistSuccess: function( data ) {
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'job_notif_checklist_up_success' ); ?>", "success" );
            },

            // Error callback: Add Job Checklist  Panel
            updateJobChecklistError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'job_notif_checklist_up_error' ); ?>", "error" );
            },

            // Success callback: Delete Job Checklist Panel
            deleteJobChecklistSuccess: function( data ) {
               value = JSON.parse( data );
               // Load job checklist details on the panel
               JCL.loadJobChecklistFormData( value.id, value.link );
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'job_notif_checklist_dlt_success' ); ?>", "warning" );
            },

            // Error callback: Delete Job Checklist  Panel
            deleteJobChecklistError: function( data ) {
               // Return checklist data table
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'job_notif_checklist_dlt_error' ); ?>", "error" );
            },

            // Success callback: Archive Job 
            archiveJobSuccess: function( data ) {
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'job_archived_success' ); ?>", "warning" );
            },

            // Error callback: Archive Job 
            archiveJobError: function( data ) {
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'job_archived_error' ); ?>", "error" );
            },

            // Success callback: Current Job 
            currentJobSuccess: function( data ) {
               UN.displayNotice( "<?php echo lang( 'system_notif_success' ); ?>", "<?php echo lang( 'job_current_success' ); ?>", "success" );
            },

            // Error callback: Current Job 
            currentJobError: function( data ) {
               UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'job_current_error' ); ?>", "error" );
            },


         }

        

         

         // Set jobId val '' if index job page is load
         if ( typeof( jobsId )  === undefined ) { jobsId = ''; }
         // Call job details if job id is not null
         if ( jobsId != '' ) { 
            JD.loadJobDetails( jobsId, link, '<?php echo lang( "job_details_notif_load_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>'  );
           
            // Have parent will determine if child or parent job file will be loaded
            JD.loadSubFilesDetails( jobsId, link, haveParent, '<?php echo lang( "job_child_notif_load_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>'  );

            if ( haveParent == '' || haveParent === undefined ) {
               $( '.child-job' ).removeClass( 'hidden' );
               $( '.sub-header' ).html( '<?php echo lang( "job_title_children" ); ?>' );
               $( '.parentVerify' ).removeClass( 'hidden' );
            } else {
               $( '.child-job' ).addClass( 'hidden' );
               $( '.sub-header' ).html( '<?php echo lang( "job_title_parent" ); ?>' );
               $( '.parentVerify' ).addClass( 'hidden' );
            }
           
            JD.loadJobDetailsFormData( jobsId, link, '<?php echo lang( "job_details_notif_load_form_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>'  )
            // Set 0 for load customer
            JD.loadCustomerDetails( jobsId, link, 0, '<?php echo lang( "job_customer_notif_load_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>'   );

         }
         // Check if there is a checklist on this job
         var countList = $( '#count-list' ).val();
         if ( countList != 0 ) { JCL.loadJobChecklistFormData( jobsId, link, '<?php echo lang( "job_checklist_load_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>'  ); }
         // If cadastral id hide cadastral details panel
         if ( hasCadastral == 0) {
             JD.loadCadastralDetails( 0, jobsId, link, '<?php echo lang( "job_notif_cadastral_load_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>' );
         } else {
             JD.loadCadastralDetails( cadastralId, jobsId, link, '<?php echo lang( "job_notif_cadastral_load_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>' );
         }

         
        

    


         /**
          * Manipulates Add form function of checklist and job and cadastral
          *
          * Prepares the form for adding a new checklist / job / cadastral
          */

         $(document).on( 'click','.add-form', function(e) {
    

            var dataForm      = $( this ).data( 'form' );
            var dataTitle     = $( this ).data( 'title' );

            // If child job trigger set parentJobId
            if( $( this ).hasClass( 'child-job' ) ) {
               $( '#parentJobId' ).val( jobsId );
               $( '#jobId' ).val( '' );
               // Set child form title
               dataTitle     = $( this ).data( 'title' );
               $( '#submit-job' ).addClass( 'child' );
               $( '#submit-job' ).attr( 'data-form', 'add' );
            }
            
            switch( dataForm ){
               case '#add-check-list':
                  JCL.loadChecklistSelection( jobsId, link, '<?php echo lang( "job_checklist_load_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>' );
               break;
               case '#add-cadastral':
                  if( cadastralId != ''){  
                     JD.loadJobCadastralForm( cadastralId, link, '<?php echo lang( "job_notif_cadastral_load_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>' ); 
                     $( '#submit-cadastral' ).attr( 'data-form', 'edit' );
                  }
               break;
               case '#form-job':
                  // Open the 'chosen' select element so it is ready for quick search/selection
                  setTimeout( function() { $( '.customer-chooser .chosen-select' ).trigger( 'chosen:open' ); }, 0 );
               break;
            }


            // Set the correct title for the modal
            $( '#form-title' ).html( dataTitle );
            // Clear the form so it is empty, ready for the new user details
            AJXDB.clearFormData( dataForm );
            $( '#jobReferenceNo' ).focus();
            
         });

         // Display selected job type on text field
         $( document ).on( 'change' , '#job-type', function () {
            if ( $( this ).val() == '' ){
               $( '#jobType' ).focus();
            }
            $( '#jobType' ).val( $( this ).val() );
         });

         // Set edit customer
         $( document ).on( 'click' , '.customer-confirm', function (e) {
            $( '#submit-job' ).trigger( 'click' );
         });

         // Reset customer details
         $( document ).on( 'click' , '.customer-reset', function () {
            $( '.customer-form-details' ).html( '' );
            $( '#customer-chooser-edit' ).val('');
         });

         /**
          * Called when a user clicks add job in job list page
          *
          * Open dropdown customer selection
          * listen for esc key
          */
         $( '.add-job' ).on( 'click', function (e) {
            e.stopPropagation();
            // Open the 'chosen' select element so it is ready for quick search/selection
            setTimeout( function() { $( '.customer-chooser.chosen-select' ).trigger( 'chosen:open' ); }, 0 );
            // Listen for escape key and cancel if pressed
            $( "#customer-chooser" ).chosen().data( 'chosen' ).container.bind( 'keydown', function( e ) {
               if ( e.which === 27 ) {
                  $( '.modal-dismiss' ).click();
               }
            });
         });



      

         /**
          * Called when a user clicks edit job
          *
          * Prepares the form for editing a job, loads the job details ready to edit
          */
         $( document ).on( 'click', '.edit', function(){

            $( '.customer-form-details' ).html( '' );
            // The job ID needed to modify the database record
            var jobId      = $( this ).data('id');
            var customerId = $( this ).data('customer-id');


            // Set the correct title for the modal
            $( '#form-title' ).html( '<?php echo lang("job_edit_fm_title"); ?>' );
            // Set for to edit type
            $( '#submit-job' ).attr( 'data-form', 'edit' );
            $( '.reset' ).trigger('click');
            // Set if have parent
            if ( haveParent == null ) {
               $( '#parentJobId' ).val( null );
               $( '#isParent' ).val( null );
            } else {
               $( '#parentJobId' ).val( haveParent );
               $( '#isParent' ).val( haveParent );
            }
            

            // Clear the form so it is empty
            AJXDB.clearFormData('#add-job');
            // Load the selected users details into the form
            JD.loadJobDetailsFormData( jobId, link );

            // Load customer details on change customer form
            if ( $( this ).hasClass( 'edit-customer' ) ) {
               JD.loadCustomerDetails( customerId, link, 1 );  
            } 


         });


         $( '#archived' ).change( function() {
               var ajaxURL    = '<?php echo ROOT_RELATIVE_PATH; ?>job/save_job';
               var ajaxData   = '';
               var archived   = 0;
               var successCB  = jobCallback.currentJobSuccess;
               var errorCB    = jobCallback.currentJobError;

               var hasParent  = $( '#checkFiles' ).val();
               var parentId   = '';
               if ( hasParent != null ) {
                  parentId = '&parentJobId=' + hasParent;
               } else {
                  parentId = '&isParent=' + hasParent;
               }
               
               if ( $( this ).prop( 'checked' ) ) {
                  $( '.archived-off' ).removeClass( 'hidden' );
                  $( '.archived-on' ).addClass( 'hidden' );
               } else { 
                  archived = 1;
                  successCB  = jobCallback.archiveJobSuccess;
                  errorCB    = jobCallback.archiveJobError;
                  $( '.archived-off' ).addClass( 'hidden' );
                  $( '.archived-on' ).removeClass( 'hidden' );
               }
               
               ajaxData = 'jobId='+ jobsId + '&archived=' + archived+parentId;
               AJXDB.ajaxForm( ajaxURL, ajaxData, successCB, errorCB );
            });

          
         
         /** ----------------------------------------------------------------------------
          * Set datatable row as clickable link to job
          */
         $( '#job-editable tbody' ).on( 'click', 'tr', function () {
            var data = table.row( this ).data();
            $.each( data, function( key, element ) {
               if ( key == 'jobId' ) {
                  var id = element;
                  window.location.href = '<?php echo ROOT_RELATIVE_PATH;?>job/view/'+id;
               }
            });
         
         });


         /** ----------------------------------------------------------------------------
          * Job sub files  list
          * Redirect to there own job view page for specific link clicked
          */
         $( document ).on( 'click', '.filtered-list-link', function(){
            var id = $( this ).data( 'id' );
            window.location.href = '<?php echo ROOT_RELATIVE_PATH;?>job/view/'+id;
         });


         /*
         Customer chooser modal popup
         */
         $( '.modal-with-form-customer-chooser' ).magnificPopup({
            type: 'inline',
            preloader: false,
            focus: '.customer-chooser',
            alignTop: true,
            modal: true
         });

         // On select option change we want to get the values before moving to the next step
         // We also unbind the change or we will get multiple events firing with subsequent use
         $( '.customer-chooser' ).unbind( "change" ).change( function() {
            // Set the value of to customer input
            $( '#customerId' ).val( $( this ).val() );
            
            // For add child job
            if ( $( this ).hasClass( 'chosen-select' ) ){
               // Reset the 'chosen' select to the empty default option ready for next use
               $( ".chosen-select" ).val( '' ).trigger( "chosen:updated" );
               // Show the add job form
               $( '.job-add' ).trigger( 'click' );
            } else {
               $( '#parentJobId' ).val( null );
               // Set 1 for load change customer
               JD.loadCustomerDetails( $( this ).val(), link, 1 );
            }
            

         });


         /**
          * Called when a user submit the job form
          *
          * Prepares the form for adding/updating a job entry
          */
         $( document ).on( 'click' ,'#submit-job', function(e) {
          
            validateForm('#form-job');
            // Check all required form fields are valid
            if ( $( '#form-job' ).valid() ) {
               $.magnificPopup.close();
               var ajaxURL    = '<?php echo ROOT_RELATIVE_PATH; ?>job/save_job';
               var ajaxData   = $( '#form-job' ).serialize();
               var successCB  = jobCallback.addJobSuccess;
               var errorCB    = jobCallback.addJobError;

               if ( $( this ).hasClass( 'child' ) ) { // Set callback for child
                  successCB   = jobCallback.addChildJobSuccess;
                  errorCB     = jobCallback.addChildJobError;
                  $( '#submit-job' ).removeClass( 'child' );
                  $( '#submit-job' ).attr( 'data-form', 'add' );
                  // Set the correct title for the modal
                  $( '#form-title' ).html( '<?php echo lang("job_add_fm_title"); ?>' );
               } else {
                  $( '#jobId' ).val( null );
                  if( $('#parentJobId').val() != null ) {
                        $( '#jobId' ).val();
                  } 
                     // Set callback for update
                     if ( $( this ).data( 'form' ) == 'edit' ) {
                        successCB   = jobCallback.updateJobSuccess;
                        errorCB     = jobCallback.updateJobError;
                        $( '#submit-job' ).attr( 'data-form', 'add' );
                        // Set the correct title for the modal
                        $( '#form-title' ).html( '<?php echo lang("job_add_fm_title"); ?>' );
                     } 
                  
                  
               }

               // Call the AJAX object method responsible for handing the db update
               AJXDB.ajaxForm( ajaxURL, ajaxData, successCB, errorCB );
               AJXDB.clearFormData( '#form-job' );
            }
      

         });

         /**
          * Called when a user submit the job cadastral form
          *
          * Prepares the form for adding/updating a cadastral entry
          */
         $( '#submit-cadastral' ).on( 'click' , function(e) {
         
            validateForm('#form-cadastral');
            // Check all required form fields are valid
            if ( $( '#form-cadastral' ).valid() ) {
               $.magnificPopup.close();
               var ajaxURL    = '<?php echo ROOT_RELATIVE_PATH; ?>job/save_job_cadastral';
               var ajaxData   = $( '#form-cadastral' ).serialize();
               var successCB  = jobCallback.addJobCadastralSuccess;
               var errorCB    = jobCallback.addJobCadastralError;

               // Set callback for update
               if ( $( this ).data( 'form' ) == 'edit' ) {
                  successCB   = jobCallback.updateJobCadastralSuccess;
                  errorCB     = jobCallback.updateJobCadastralError;
                  $( '#submit-cadastral' ).attr( 'data-form', 'add' );
                  // Set the correct title for the modal
                  $( '#form-title' ).html( '<?php echo lang("job_add_cadastral_fm_title"); ?>' );
               }

               // Call the AJAX object method responsible for handing the db update
               AJXDB.ajaxForm( ajaxURL, ajaxData, successCB, errorCB );
               AJXDB.clearFormData( '#form-cadastral' );
            }
      

         });


          /**
          * Called when a user submit the job checklist form
          *
          * Prepares the form for adding a checklist entry
          */
         $( '#submit-checklist' ).on( 'click' , function(e) {
         
            validateForm('#form-checklist');
            // Check all required form fields are valid
            if ( $( '#form-checklist' ).valid() ) {
               $.magnificPopup.close();
               var ajaxURL    = '<?php echo ROOT_RELATIVE_PATH; ?>job/create_job_checklist';
               var ajaxData   = $( '#form-checklist' ).serialize();
               var successCB  = jobCallback.addJobChecklistSuccess;
               var errorCB    = jobCallback.addJobChecklistError;


               // Call the AJAX object method responsible for handing the db update
               AJXDB.ajaxForm( ajaxURL, ajaxData, successCB, errorCB );
               AJXDB.clearFormData( '#form-checklist' );
            }
      

         });



        

      });

   </script>
     