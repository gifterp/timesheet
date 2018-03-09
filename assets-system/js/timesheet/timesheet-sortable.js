

(function($) {
	// Get the start order and end order from group/task list sort
	makeSortable = function(){
		$( ".sortable" ).sortable({ 
			// sort by this class - icon
			handle: '.handle-sort' ,
			start: function ( event, ui ) {
				// get the start order
				$( ui.item ).data( "startindex", ui.item.index() );
			},
			stop: function ( event, ui ) {
				// get the last order and update
				sendUpdatedIndex (ui.item ); 
			}
		}); 
	};

	
	/** ----------------------------------------------------------------------------
    * Remove group on group list, using bootsrap confirmation
    * Set new url, ajax notif message, group id and array display order
    */
	deleteConfirmation = function() {
		// $( document ).on( 'click', '.handle-delete', function() {
		// 	 
            // Delete function modal
            $( '.handle-delete' ).confirmation({
             	onConfirm: function() {
             		var data = $( this );
	             	// Id details
		            var id        = data[0].id;
		            // Determine if task or group id
		            var columnId  = data[0].columnId;
		            var groupId   = data[0].groupId;
		            // Display order value
		            var order     = data[0].order;
		            // Determins what form type or group
		            var type      = data[0].type;
		            // Url for the form
		            var ajaxUrl  = data[0].url;
		            // Set ajax data
		            var ajaxData = 'timesheetTaskGroupId=' + id + '&displayOrder=' + order;
		            var successCB = timesheetAdminCallback.deleteTimesheetGroupSuccess;
		            var errorCB = timesheetAdminCallback.deleteTimesheetGroupError;

		            // Set group id for task form
		            if ( type == 'task' ) {
		                	ajaxData 	= 'timesheetTaskGroupId=' + groupId + '&timesheetTaskId=' + id + '&displayOrder=' + order;
		                	successCB	= timesheetAdminCallback.deleteTimesheetTaskSuccess;
								errorCB		= timesheetAdminCallback.deleteTimesheetTaskError;
		            }
	              	AJXDB.ajaxForm( ajaxUrl, ajaxData, successCB, errorCB );
		            // Reset input
		            $( columnId ).val( '' );
		            $( '#' + type + 'DisplayOrder' ).val( '' );

             	}
           });
           
			 
        
		// });
		
	};

	// Save changes sort done in  group/task
	function sendUpdatedIndex ( $item ) {
		// increment by 1 as we're using 1 based index on server
		var startIndex 	= $item.data( "startindex" ) + 1;
		var newIndex 		= $item.index() + 1;
		// url
		var ajaxUrl 		= $item.attr( "data-url" );
		//  type
		var type 			= $item.attr( "data-type" );

		var errorMsg 		= $item.attr( "data-error" );
		var errorNotif 	= $item.attr( "data-error-notif" );
		var successNotif 	= $item.attr( "data-success-notif" );
		//  id
		var id 	= $item.attr( "id" );
			// if not same display order trigger update
			if ( newIndex != startIndex ) {
				// Check Type for order function
				if ( type == 'Group') { 
					ajaxUrl = ajaxUrl + 'update_timesheet_group_list_order';
				} else {
					ajaxUrl = ajaxUrl + 'update_timesheet_task_list_order';
				}
				TMT.updateOrder( id, startIndex, newIndex, ajaxUrl, errorMsg, errorNotif, successNotif );	
			}
	}

}).apply( this, [jQuery] );