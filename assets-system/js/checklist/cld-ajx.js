var CLD = {


   // Will display html left pane in checklist
   loadListData: function( checklistId, ajaxUrl, errorMsg, errorNotif ) {
      var _CLD = this;

      $.ajax({
         type:'POST', 
         url: ajaxUrl + "get_multiple_row",
         data: 'checklistId=' + checklistId, 
         cache: false,
         dataType: 'json',
         success: function( html ) {
            $('#list-container').html( '' );
            $('#list-container').html( html );
            makesortable();
            deleteConfirm(); 
         },
         error: function(e) {
            UN.displayNotice( errorNotif, errorMsg, 'error' );
         }
      });
   },
   // Will display html right pane in checklist
   loadViewData: function( checklistId, ajaxUrl, errorMsg, errorNotif ) {
      var _CLD = this;

      $.ajax({
         type:'POST',
         url: ajaxUrl + "print_checklist",
         data: 'checklistId=' + checklistId,
         cache: false,
         success: function( html ) {
            $( '#checklist-container' ).html( '' );
            $(' #checklist-container' ).html( html );
            makesortable();
         },
         error: function(e) {
            UN.displayNotice( errorNotif, errorMsg, 'error' );
         }
      });
   },


   // Arrange order in the database checklist item
   updateOrder: function( checklistId, startOrder, endOrder, ajaxUrl, errorMsg, errorNotif ) {
      var _CLD = this;

      $.ajax({
         type:'POST',
         url: ajaxUrl + "update_checklist_item_order",
         data: 'checklistId=' + checklistId + '&start=' + startOrder + '&end=' + endOrder,
         cache: false,
         dataType: 'JSON',
         success: function( html ) {
            $( '#list-container' ).html( '' );
            $( '#list-container' ).html( html );
            CLD.loadViewData( checklistId, ajaxUrl );
            makesortable();
            deleteConfirm();
         },
         error: function(e) {
            UN.displayNotice( errorNotif, errorMsg, 'error' );
         }
      });
   },


};