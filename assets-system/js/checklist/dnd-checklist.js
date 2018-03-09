

(function($) {
	// Get the start order and end order from checklist sort
	makesortable = function(){
		$( ".sortable" ).sortable({  
			// sort by this class - icon
			handle: '.handle',
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
    * Remove checklist on checlist list, using bootsrap confirmation
    * Set new url, ajax notif message, checklist id and array display order
    */
   deleteConfirm = function() {
    
      // Delete rows once it clicked
          
      $( '.delete-row' ).confirmation({
         onConfirm: function() {
            $( '#counter' ).val( 0 );
            var data = $( this );
            var checklistItemId  = data[0].id;
            var success          = data[0].success;
            var error            = data[0].error;
            var ajaxUrl          = data[0].link;
            var order            = data[0].order;
            var checklistId      = data[0].checklistId;
            var ajaxData        	= 'checklistItemId=' + checklistItemId + '&checklistId=' + checklistId + '&order=' + order;   
            AJXDB.ajaxForm( ajaxUrl, ajaxData, checklistItemCallback.deleteChecklistItemSuccess, checklistItemCallback.deleteChecklistItemError, checklistId ); 
            }
     });
           
      
   };


	// Save changes sort done in checklist
	function sendUpdatedIndex ( $item ) {
		// increment by 1 as we're using 1 based index on server
		var startIndex 	= $item.data( "startindex" ) + 1;
		var newIndex 		= $item.index() + 1;


		var error 			= $item.attr( "data-error" );
		var errorNotif 	= $item.attr( "data-error-notif" );
		// checklist id
		var checklist 	= $item.attr( "id" );
		// checklist url
		var ajaxUrl 	= $item.attr( "data-url" );
		// if not same display order trigger update
		if ( newIndex != startIndex ) {
			CLD.updateOrder( checklist, startIndex, newIndex, ajaxUrl, error, errorNotif );
		}
	}

}).apply( this, [jQuery] );