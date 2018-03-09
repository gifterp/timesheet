/**
 * Timesheet Manage Task Object
 *
 * This object handles the loading of timesheet 
 * 
 * 
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

var TMT = {

   // Will display html left pane in group list
   loadGroupList: function(  ajaxUrl, errorMsg, errorNotif ) {
      var _self = this;
 
      $.ajax({
         type:'POST',
         url: ajaxUrl + "get_group_list",
         data: '',
         cache: false,
         dataType: 'json',
         success: function( html ) {
            $( '.group-panel' ).html( '' );
            $( '.group-panel' ).html( html );
            makeSortable();
            deleteConfirmation();
         },
         error: function(e) {
            UN.displayNotice( 'Error!', errorMsg, 'error' );
         }
      });
   },

   // Will display selected group html left pane in group list
   loadSelectedGroup: function( timesheetTaskGroupId, ajaxUrl, errorMsg, errorNotif ) {
      var _self = this;

      $.ajax({
         type:'POST',
         url: ajaxUrl + "get_selected_group",
         data: 'timesheetTaskGroupId='+timesheetTaskGroupId,
         cache: false,
         dataType: 'json',
         success: function( html ) {
            $( '.task-list' ).html( '' );
            $( '.task-list' ).html( html );
            makeSortable();
            $('.modal-with-form').magnificPopup();
            deleteConfirmation();
         },
         error: function(e) {
            UN.displayNotice( 'Error!', errorMsg, 'error' );
         }
      });
   },


   // Arrange order in the database timesheet task/group item
   updateOrder: function( id, startOrder, endOrder, ajaxUrl, errorMsg, errorNotif, successNotif ) {
      var _self = this;

      $.ajax({
         type:'POST',
         url: ajaxUrl,
         data: 'id=' + id + '&start=' + startOrder + '&end=' + endOrder,
         cache: false,
         success: function( data ) {
            value = JSON.parse(data);
            if ( value.type == 'Group' ) {
               TMT.loadGroupList( value.link, errorMsg );
            } else {
               TMT.loadSelectedGroup( value.id, value.link, errorMsg );
            }
            UN.displayNotice(successNotif, value.msg, 'success');
         },
         error: function(e) {
            UN.displayNotice( errorNotif, errorMsg, 'error' );
         }
      });
   },
}