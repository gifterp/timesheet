<?php
/**
 * Custom JavaScript for the checklist item section
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:       Matt Batten <matt@improvedsoftware.com.au>
 * Author:       John Gifter C Poja <gifter@improvedsoftware.com.au>
 */ 
?>      
   <!-- Custom -->
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/db/ajax-db.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/checklist/cld-ajx.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/checklist/dnd-checklist.js"></script>
      
 
   <script type="text/javascript">
    
         $( document ).ready( function() { 

            // Set global variables
            var checklistId   = $( '#check-list-id' ).val(); 
            var container     = '.list-container'; 
            var ajaxUrl       = "<?php echo ROOT_RELATIVE_PATH; ?>checklist/";
            var ajaxData      =  '';


             /** ----------------------------------------------------------------------------
             * Object to handle the ajax success/error requests
             */
            checklistItemCallback = { 

               // Checklist Item 

               // Success callback: Add Checklist item Section
               addChecklistItemSuccess: function( data ) {
                  value =  JSON.parse( data );
                  CLD.loadListData( value.id, value.link);
                  CLD.loadViewData( value.id, value.link);
                  UN.displayNotice('<?php echo lang( 'system_notif_success' ); ?>', "<?php echo lang( 'checklist_notif_add_item_success' ); ?>" , 'success');
               },

               // Error callback: Add Checklist item Section
               addChecklistItemError: function( data ) {
                  // Show notification error
                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'checklist_notif_add_item_error' ); ?>", "error" );
               },

               // Success callback: Update Checklist item Section
               updateChecklistItemSuccess: function( data, id ) {
                  value =  JSON.parse( data );
                  CLD.loadViewData( id, value.link);
               },

               // Error callback: Update Checklist item Section
               updateChecklistItemError: function( data, id ) {
                  // Show notification error
                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'checklist_notif_add_item_error' ); ?>", "error" );
               },

               // Success callback: Delete Checklist item Section
               deleteChecklistItemSuccess: function( data, id ) {
                  value =  JSON.parse( data );
                  CLD.loadListData( id, value.link);
                  CLD.loadViewData( id, value.link);
                  UN.displayNotice('<?php echo lang( 'system_notif_success' ); ?>', "<?php echo lang( 'checklist_item_notif_delete_success' ); ?>" , 'warning');
               },

               // Error callback: Delete Checklist item Section
               deleteChecklistItemError: function( data ) {
                  // Show notification error
                  UN.displayNotice( "<?php echo lang( 'system_notif_error' ); ?>", "<?php echo lang( 'checklist_item_notif_delete_error' ); ?>", "error" );
               },
            }


            // Load left and right panel form if there is a checklist data
            if ( $( '#counter' ).val() != 0 ) { 
               CLD.loadListData( checklistId, ajaxUrl, '<?php echo lang( "checklist_item_fm_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>' );
               CLD.loadViewData( checklistId, ajaxUrl, '<?php echo lang( "checklist_item_fm_error" ); ?>', '<?php echo lang( "system_notif_error" ); ?>' ); 
            } else {
               ajaxUrl     = $( '.add-list-row' ).data( 'link' );
               ajaxData    = 'checklistId=' + checklistId + '&fieldType=Checkbox&fieldWidth=col-md-12';
               AJXDB.ajaxForm( ajaxUrl, ajaxData, checklistItemCallback.addChecklistItemSuccess, checklistItemCallback.addChecklistItemError );
            }

            
           

            // Create a new row in left form panel
            $(  '.add-list-row' ).on(' click ', function(e){
               e.stopPropagation();
               ajaxUrl     = $( this ).data( 'link' );
               ajaxData    = 'checklistId=' + checklistId + '&fieldType=Checkbox&fieldWidth=col-md-12';
               AJXDB.ajaxForm( ajaxUrl, ajaxData, checklistItemCallback.addChecklistItemSuccess, checklistItemCallback.addChecklistItemError );
            });
 
            // Trigger when new rows checkbox is checked
            // Creates new row
            $( document ).on(' click ', '.new-row', function(){
               var checklistItemId  = $( this ).data( 'id' );
               ajaxUrl              = $( this ).data( 'link' );

               if ( $( this ).prop( 'checked' ) ) {
                  ajaxData          = 'checklistItemId=' + checklistItemId + '&newRow=1';   
               } else {
                  ajaxData          = 'checklistItemId=' + checklistItemId + '&newRow=0'; 
               }
               AJXDB.ajaxForm( ajaxUrl, ajaxData, checklistItemCallback.updateChecklistItemSuccess, checklistItemCallback.updateChecklistItemError, checklistId );

            });


            // Trigger when new field Type dropdown is changed
            $( document ).on( 'change', '.field-type', function() {
               var dataId           = $( this ).data( 'id' );
               var checklistitemId  = $( '#check-list-item-id' + dataId ).val();
               ajaxUrl              = $( this ).data( 'link' ); 
               // Change width field data
               $( "#widthField" + dataId ).remove();
               // Dont trigger ajax when field type is blank
               if ( $( this ).val() != '--' ) { 
                  ajaxData = 'checklistItemId=' + checklistitemId + '&fieldType=' + $( this ).val(); 
                  AJXDB.ajaxForm( ajaxUrl, ajaxData, checklistItemCallback.updateChecklistItemSuccess, checklistItemCallback.updateChecklistItemError, checklistId );
               }
            });

            // Trigger when column  is changed
            $( document ).on( 'change', '.column', function() {
               var checklistItemId  = $( this ).data( 'id' );
               var column           = 'col-md-' + $( this ).find( 'option:selected' ).val();
               ajaxUrl              = $(this).data( 'link' );
               ajaxData             = 'checklistItemId=' + checklistItemId + '&fieldWidth=' + $( this ).val(); 

               $( '#widthField' + checklistItemId ).attr( 'class', '' )
               $( '#widthField' + checklistItemId ).attr( 'class', column );
               AJXDB.ajaxForm( ajaxUrl, ajaxData, checklistItemCallback.updateChecklistItemSuccess, checklistItemCallback.updateChecklistItemError, checklistId ); 
            });

            // Trigger when input name  is changed
            $( document ).on( 'change', '.field-input', function() {
                  var checklistItemId  = $( this ).data( 'id' );
                  ajaxUrl              = $( this ).data( 'link' );
                  ajaxData             = 'checklistItemId=' + checklistItemId + '&fieldTitle=' + $( this ).val(); 

                  $( '#fieldName' + checklistItemId ).text( $( this ).val() );
                   AJXDB.ajaxForm( ajaxUrl, ajaxData, checklistItemCallback.updateChecklistItemSuccess, checklistItemCallback.updateChecklistItemError, checklistId ); 
            });

            
         });


   </script>