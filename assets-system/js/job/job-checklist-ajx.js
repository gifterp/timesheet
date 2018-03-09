/**
 * Job Checklist Object
 *
 * This object handles the manipulation of data in job section view of checklist.
 * It includes job checklist update on sort checklist data update
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

var JCL = {

   /** ----------------------------------------------------------------------------
    * Get the html data strucrture for job checklist
    * call makesort for output sort ready
    *
    * @param  string jobId            Job Id 
    * @param  string ajaxUrl          Root Url
    * @return void
    */ 
   loadJobChecklistFormData: function( jobId, ajaxUrl, errorMsg, errorNotif ) {
      var _self = this;

      $.ajax({
         type:'POST',
         url: ajaxUrl + "print_checklists",
         data: 'jobId=' + jobId,
         cache: false,
         success: function( html ) {
            if ( html ) {
               $( '.checklist-panel' ).removeClass( 'hidden' );
               $( '#form-container' ).html( '' );
               $( '#form-container' ).html( html );
               $( '.add-checklist-button' ).addClass( 'hidden' );
            } else {
               $( '.checklist-panel' ).addClass( 'hidden' );
               $( '.add-checklist-button' ).removeClass( 'hidden' );
            } 
           makesortable();
           deleteConfirm();
            _self.verifyChecklist( jobId, ajaxUrl );
           _self.startListeningChecklist( jobId );
         },
         error: function( e ) {
            
            $( '.checklist-panel' ).addClass( 'hidden' );
            $( '.add-checklist-button' ).removeClass( 'hidden' );
            UN.displayNotice( errorNotif, errorMsg, 'error' );
         }
      });
   },

   /** ----------------------------------------------------------------------------
    * Event handlers to trigger when checklist form elements are altered
    */
   verifyChecklist: function( jobId, ajaxUrl ) {
      var _self = this;

      $.ajax({
         type:'POST',
         url: ajaxUrl + "verify_checklists",
         data: 'jobId=' + jobId,
         cache: false,
         success: function( counter ) {
            if ( counter == 0 ) {
               $( '.checklistVerify' ).addClass( 'hidden' );
            } else {
               $( '.checklistVerify' ).removeClass( 'hidden' );
            } 
         },
         error: function( e ) {
            $( '.checklist-panel' ).addClass( 'hidden' );
            $( '.add-checklist-button' ).removeClass( 'hidden' );
            UN.displayNotice( errorNotif, errorMsg, 'error' );
         }
      });
   },


   /** ----------------------------------------------------------------------------
    * Event handlers to trigger update when checklist form elements are altered
    */
   startListeningChecklist: function( jobId ) {

      // Make date fields work with the Bootstrap Datepicker plugin
      // Change datepicker to datepickers solve css padding problem on input
      $( '.datepickers' ).datepicker({
         format: 'dd/mm/yyyy',
         autoclose: true,
         orientation : 'bottom',
         clearBtn: true,
         todayBtn: 'linked',
         todayHighlight: true
      });

      // Button to select todays date
      // We set using the datepicker plugin so further use of the plugin won't overwrite
      // Seting the value only will not store the updated date in the plugin
      $( '.select-today' ).click( function() {
         var dateField = $( document ).find("input[name='" + $( this ).data( 'field-id' ) + "']");
         $( dateField ).datepicker( 'update', moment().format( 'DD/MM/YYYY' ));
      });

      // Date input listen for change event
      $( '.checklist-date-input' ).unbind( "change" ).change( function() {
         ajaxData = 'jobChecklistDataId=' + $( this ).data( 'id' ) + '&dateData=' + $( this ).val();
         AJXDB.ajaxForm( $( this ).data( 'url' ), ajaxData, jobCallback.updateJobChecklistSuccess, jobCallback.updateJobChecklistError );
      });

      // Checkbox listen for change event
      $( '.checklist-checkbox-input' ).unbind( "change" ).change( function() {
         value = 0;
         if ( $( this ).prop( 'checked' ) ) { value = 1; }
         ajaxData = 'jobChecklistDataId=' + $( this ).data( 'id' ) + '&checkboxData=' + value;
         AJXDB.ajaxForm( $( this ).data( 'url' ), ajaxData, jobCallback.updateJobChecklistSuccess, jobCallback.updateJobChecklistError, jobId );
      });

      // Text input listen for change event
      $( '.checklist-text-input' ).unbind( "change" ).change( function() {
         ajaxData = 'jobChecklistDataId=' + $( this ).data( 'id' ) + '&textData=' + $( this ).val();
         AJXDB.ajaxForm( $( this ).data( 'url' ), ajaxData, jobCallback.updateJobChecklistSuccess, jobCallback.updateJobChecklistError, jobId );
      });
   },


   /** ----------------------------------------------------------------------------
    * Get the html data dropdown strucrture for job checklist
    * call makesort for output sort ready
    *
    * @param  string jobId            Job Id 
    * @param  string ajaxUrl          Root Url
    * @return void
    */
   loadChecklistSelection: function( jobId, ajaxUrl, errorMsg, errorNotif ) {

      $.ajax({
         type:'POST',
         url: ajaxUrl + "create_dropdown_checklist",
         data: 'jobId=' + jobId,
         cache: false,
         dataType: 'json',
         success: function( data ) {
            value = JSON.parse( JSON.stringify( data ) );
            if ( value.status == 'List' ) {
               $( '.checklist-selector').html( '' );
               $( '.checklist-selector').html( value.option );
               $( '.submit-checklist').removeClass('disabled'); 
               $( '.submit-checklist').addClass(' modal-confirm-submit');          
            } 
            
           makesortable();
         },
         error: function(e) {
            UN.displayNotice( errorMsg, errorNotif, 'error');
         }
      });  
   },

   /** ----------------------------------------------------------------------------
    * Update display order once checklist is reorder, then redraw the checklist data form
    * call makesort for output sort ready
    *
    * @param  string jobId            Job Id 
    * @param  string startOrder       Original display order value
    * @param  string endOrder         New display order value
    * @param  string ajaxUrl          Root Url
    * @param  string error            Notification Error
    * @return void
    */
   updateOrder: function( jobId, startOrder, endOrder, ajaxUrl, error , errorNotif) {

      $.ajax({
         type:'POST',
         url: ajaxUrl + "update_job_checklist_item_order",
         data: 'jobId=' + jobId + '&start=' + startOrder + '&end=' + endOrder,
         cache: false,
         success: function( html ) {
            $('.checklist-panel').removeClass('hidden');
            $('#form-container').html( '' );
            $('#form-container').html( html );
            $('.add-checklist-button').addClass('hidden');
            makesortable();
         },
         error: function(e) {
            UN.displayNotice( errorNotif, error, 'error');
         }
      });
   },

};