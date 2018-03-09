/**
 * Drag and drop job checklist 
 *
 * Uses sortable and drag and drop list for job checklist section
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:       Gifter Poja <gifter@improvedsoftware.com.au>
 */

( function( $ ) {

	// Make the item sortable
	makesortable = function(){
		$( ".sortable" ).sortable({ 
			// sort by this class - icon
			handle: '.handle' ,
			start: function ( event, ui ) {
				// get the start order
				$( ui.item ).data("startindex", ui.item.index() );
			},
			stop: function ( event, ui ) {
				// get the last order and update
				sendUpdatedIndex( ui.item ); 
			}
		});
	};

	/** ----------------------------------------------------------------------------
    * Remove checklist on job checklist list, using bootsrap confirmation
    * Set new url, ajax notif message, checklist id and array display order
    */
   deleteConfirm = function() {
    
      // Delete rows once it clicked
          
      $( '.delete-job-checklist' ).confirmation({
         onConfirm: function() {
            var data = $( this );
            var ajaxUrl       = '../delete_checklist';
            var order         = data[0].order;
            var id            = data[0].jobId;
            var ajaxData      = "jobChecklistId=" + data[0].rowId
                                 + '&jobId=' + id
                                 + '&order=' + order;

            AJXDB.ajaxForm( ajaxUrl, ajaxData, jobCallback.deleteJobChecklistSuccess, jobCallback.deleteJobChecklistError, id ); 
            }
     });
           
      
   };

	// Update new order
	function sendUpdatedIndex ( $item ) {
		// increment by 1 as we're using 1 based index on server
		var error 			= $item.attr( "data-error" );
		var errorNotif 	= $item.attr( "data-error-notif" );
		var startIndex 	= $item.data( "startindex" ) + 1;
		var newIndex 		= $item.index() + 1;
		// job checklist id
		var jobId 			= $item.attr( "data-job-id" );
		// job url 
		var ajaxUrl 		= $item.attr( "data-url" );
		// if not same display order trigger update
		if ( newIndex != startIndex ) {
			JCL.updateOrder( jobId, startIndex, newIndex, ajaxUrl, error, errorNotif );
		}
	} 

}).apply( this, [jQuery] );