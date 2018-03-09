/**
 * AJAX handler functions to create and edit data from the database
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:     Matt Batten <matt@improvedsoftware.com.au>
 * Author:     John Gifter C Poja <gifter@improvedsoftware.com.au>
 * Requires:   [Vendor] PNotify JS + CSS
 *             [System] user-notification.js
*/

// Our jQuery dataTable used to display the data to the screen
var table;
var EditableTable; 
var EditableTable_notes;

// User AJAX object for working with data in the database
var AJXDB = {
   

   /** ----------------------------------------------------------------------------
    * Run an AJAX query on the database with the passed form data
    *
    * @param  string formId          The form ID with data to be used to update the db
    * @param  string msgSuccess      Success message used for PNotify notification
    * @param  string msgError        Error message used for PNotify notification
    * @param  string ajaxUrl         The URL to be used by the AJAX call
    */
   updateFromFormData: function(formId, msgSuccess, msgError, ajaxUrl) {
      $.ajax({
         type: "POST",
         url: ajaxUrl,
         data: $(formId).serialize(),
         success: function(data){
            table.ajax.reload();
             AJXDB.clearFormData( formId );
            UN.displayNotice('Success!', msgSuccess, 'success');
         }, error: function (XHR, status, response) {
            UN.displayNotice('Error!', msgError, 'error');
         }

      });
   },


   /** ----------------------------------------------------------------------------
    * Execute ajax with callback function for system sections
    *
    * @param  string ajaxURL           The holder of function url
    * @param  string ajaxData          Form details
    * @param  string successCallback   Callback function when success
    * @param  string errorCallback     Callback function when error
    * @param  string link              Result url
    * @param  string id                Details
    */
   ajaxForm: function( ajaxURL, ajaxData, successCallback, errorCallback, id, link ) {
      $.ajax({
         type: 'POST',
         url: ajaxURL,
         data: ajaxData,
         success: function( data ) {
            if ( typeof( successCallback ) == "function" ) {
               successCallback( data, id, link );
            }
         },
         error: function( e ) {
            if ( typeof( errorCallback ) == "function" ) {
               errorCallback( e, id, link );
            }
         }
      });
   },
 

 


   /** ----------------------------------------------------------------------------
    * Run an AJAX query on the database with the passed form data
    *
    * @param  string arrayData      The array that holds the data and the field name
    * @param  string msgSuccess      Success message used for PNotify notification
    * @param  string msgError        Error message used for PNotify notification
    * @param  string ajaxUrl         The URL to be used by the AJAX call
    */
   updateFromTableData: function(arrayData, msgSuccess, msgError, ajaxUrl) {
      $.ajax({
         type: "POST",
         url: ajaxUrl,
         data: arrayData,
         success: function(data){
            EditableTable.reload();
            UN.displayNotice('Success!', msgSuccess, 'success');
         }, error: function (XHR, status, response) {
            UN.displayNotice('Error!', msgError, 'error');
         }

      });
   },


   /** ----------------------------------------------------------------------------
    * Run an AJAX query on the database with the passed form data
    *
    * @param  string arrayData      The array that holds the data and the field name
    * @param  string msgSuccess      Success message used for PNotify notification
    * @param  string msgError        Error message used for PNotify notification
    * @param  string ajaxUrl         The URL to be used by the AJAX call
    */
   deleteFromTableData: function(arrayData, msgSuccess, msgError, ajaxUrl) {
      $.ajax({
         type: "POST",
         url: ajaxUrl,
         data: arrayData,
         success: function(data){
            EditableTable.reload();
            UN.displayNotice('Success!', msgSuccess, 'warning');
         }, error: function (XHR, status, response) {
            UN.displayNotice('Error!', msgError, 'error');
         }

      });
   },

   /** ----------------------------------------------------------------------------
    * Run an AJAX query on the database with the passed form data
    *
    * @param  string arrayData      The array that holds the data and the field name
    * @param  string msgSuccess      Success message used for PNotify notification
    * @param  string msgError        Error message used for PNotify notification
    * @param  string ajaxUrl         The URL to be used by the AJAX call
    */
   updateFromTableData_notes: function(arrayData, msgSuccess, msgError, ajaxUrl) {
      $.ajax({
         type: "POST",
         url: ajaxUrl,
         data: arrayData,
         success: function(data){
            EditableTable_notes.reload_notes();
            UN.displayNotice('Success!', msgSuccess, 'success');
         }, error: function (XHR, status, response) {
            UN.displayNotice('Error!', msgError, 'error');
         }

      });
   },

   /** ----------------------------------------------------------------------------
    * Run an AJAX query on the database with the passed form data
    *
    * @param  string arrayData      The array that holds the data and the field name
    * @param  string msgSuccess      Success message used for PNotify notification
    * @param  string msgError        Error message used for PNotify notification
    * @param  string ajaxUrl         The URL to be used by the AJAX call
    */
   deleteFromTableData_notes: function(arrayData, msgSuccess, msgError, ajaxUrl) {
      $.ajax({
         type: "POST",
         url: ajaxUrl,
         data: arrayData,
         success: function(response){
            EditableTable_notes.reload_notes();
            UN.displayNotice('Success!', msgSuccess, 'warning');
         }, error: function (XHR, status, response) {
            UN.displayNotice('Error!', msgError, 'error');
         }

      });
   },


   /** ----------------------------------------------------------------------------
    * Resets the form to the original default settings.
    * Also resets the "active" IOS7 switch if one is present
    *
    * @param  string formId          The form ID of the form to be cleared
    */
   clearFormData: function (formId) {
      // ios7-switch does not reset with the above command.
      // If it is turned off then reset to the default on state
      if ($("#active").prop('checked') == false) {
         $("#active").prev('.ios-switch').trigger('click');
      }
      if ($("#chargeable").prop('checked') == false) {
         $("#chargeable").prev('.ios-switch').trigger('click');
      }
      if ($("#hiddenReports").prop('checked') == false) {
         $("#hiddenReports").prev('.ios-switch').trigger('click');
      }
      if ($("#createButton").prop('checked') == false) {
         $("#createButton").prev('.ios-switch').trigger('click');
      }
      $(formId).trigger("reset");
   }

};

