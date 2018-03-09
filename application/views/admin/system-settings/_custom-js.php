<?php
/**
 * Administration - System Settings Custom JavaScript
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>
      <!-- Custom -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>
      <script type="text/javascript">

      $( document ).ready( function() {

         // Initialise the form on page load
         get_default();

         // Fix: When label is clicked for IOS7 Switch we need to change the switch state to match the checkbox
         $( '#form-setting' ).on( 'click', "label[for='hasCadastral']", function( e ) {
            e.preventDefault();
            $( "#hasCadastral" ).prev( '.ios-switch' ).trigger( 'click' );
         });


         /** ----------------------------------------------------------------------------
          * Gets the settings from the database via AJAX and runs the function to set
          * the form to match
          */
         function get_default() {
            // Get the system settings from the database to set the form
            $.ajax({
               url: '<?php echo ROOT_RELATIVE_PATH; ?>admin/system-settings/get-default',
               type: 'POST',
               dataType: 'json',
               success: function ( data ) {
                  set_form( data );
               },
               error: function ( request, error ) {
                  UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "system_settings_adm_load_error" ); ?>', 'error' );
               }
            });
         }


         /** ----------------------------------------------------------------------------
          * Updates the system settings form to match values retrieved from the database
          *
          * @param  object jsonObject            Values from the database
          * @return void
          */ 
         function set_form( jsonObject ) {
            $.each( jsonObject, function( key, element ) {


               // ios7-switch needs to simulate a click to change state
               if ( element.hasCadastral == '1' ) { // on state
                  if ( $( "#hasCadastral" ).prop( 'checked' ) == false ) {
                     $( "#hasCadastral" ).prev( '.ios-switch' ).trigger( 'click' );
                  }
               } else {
                  if ( $( "#hasCadastral" ).prop( 'checked' ) == true ) {
                     $( "#hasCadastral" ).prev( '.ios-switch' ).trigger( 'click' );
                  }
               }

               // Assign other inputs by value
               $( "#businessName" ).val( element.businessName );
               $( "#jobNameFormat" ).val( element.jobNameFormat );
               $( "#defaultZone" ).val( element.defaultZone );

            });

            // We only start listening for changes after we have set the form to the correct settings
            start_listening();
         }


         /** ----------------------------------------------------------------------------
          * Event handlers to trigger update when form elements are altered
          */
         function start_listening() {

            // IOS 7 Lightswitch - true/false to integer value for database TINYINT
            $( '#hasCadastral' ).change( function() {
               var settings = {
                  hasCadastral: ( $( "#hasCadastral" ).prop( 'checked' ) ? 1 : 0 )
               }
               update_settings( settings );
            });

            
            // Listen for change events in the setting form
            $( '#form-setting' ).on( 'change','input[type=text]', function() {
               // This sets and updates all the select inputs
               // We use jQuery val() to also get the input values
               var settings = {};
               settings[ this.name ] = $( this ).val();
               update_settings( settings );
            })
            
         }


         /** ----------------------------------------------------------------------------
          * Updates the settings in the database
          *
          * @param object  settings    Settings to be updated are passed in this object
          */
         function update_settings( settings ) {

            // Build the query string from the object data
            var queryStringSeparator = '',
                queryString          = '';
            $.each( settings, function( key, element )  {
               queryString = queryString + queryStringSeparator + key + '=' + element;
               queryStringSeparator = '&';
            });

            // Update the settings using the querystring we built
            $.ajax({
               url: '<?php echo ROOT_RELATIVE_PATH; ?>admin/system-settings/update-settings',
               data: queryString,
               type: 'POST',
               dataType: 'json',
               error: function ( request, error ) {
                  UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "system_settings_adm_update_error" ); ?>', 'error' );
               }
            });
         }


         

      });

      </script>