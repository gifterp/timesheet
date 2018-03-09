<?php
/**
 * Administration - Timesheet Settings Custom JavaScript
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>
      <!-- Custom -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/timesheet/timesheet-entry.js"></script>
      <script type="text/javascript">

      $( document ).ready( function() {

         // Initialise the form on page load
         get_settings();

         // Fix: When label is clicked for IOS7 Switch we need to change the switch state to match the checkbox
         $( '#form-other' ).on( 'click', "label[for='multiDaySelectDisabled']", function( e ) {
            e.preventDefault();
            $( "#multiDaySelectDisabled" ).prev( '.ios-switch' ).trigger( 'click' );
         });


         /** ----------------------------------------------------------------------------
          * Gets the settings from the database via AJAX and runs the function to set
          * the form to match
          */
         function get_settings() {
            // Get the timesheet settings from the database to set the form
            $.ajax({
               url: '<?php echo ROOT_RELATIVE_PATH; ?>admin/timesheet-settings/get-settings',
               type: 'POST',
               dataType: 'json',
               success: function ( data ) {
                  set_form( data );
               },
               error: function ( request, error ) {
                  UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "timesheet_adm_set_notif_get_error" ); ?>', 'error' );
               }
            });
         }


         /** ----------------------------------------------------------------------------
          * Updates the timesheet settings form to match values retrieved from the database
          *
          * @param  object jsonObject            Values from the database
          * @return void
          */
         function set_form( jsonObject ) {
            $.each( jsonObject, function( key, element ) {

               // Bootstrap multiselect. We need to split days into an array, update value and refresh
               var busDays = ( element.businessDaysOfWeek ).split( ',' );
               $( "#businessDaysOfWeek" ).val( busDays );
               $( "#businessDaysOfWeek" ).multiselect( "refresh" );

               // ios7-switch needs to simulate a click to change state
               if ( element.multiDaySelectDisabled == '1' ) { // on state
                  if ( $( "#multiDaySelectDisabled" ).prop( 'checked' ) == false ) {
                     $( "#multiDaySelectDisabled" ).prev( '.ios-switch' ).trigger( 'click' );
                  }
               } else {
                  if ( $( "#multiDaySelectDisabled" ).prop( 'checked' ) == true ) {
                     $( "#multiDaySelectDisabled" ).prev( '.ios-switch' ).trigger( 'click' );
                  }
               }

               // Assign other inputs by value
               $( "#timeFormat" ).val( element.timeFormat );
               $( "#businessHoursStart" ).timepicker( 'setTime', element.businessHoursStart );
               $( "#businessHoursEnd" ).timepicker( 'setTime', element.businessHoursEnd );
               $( "#slotDuration" ).val( element.slotDuration );
               $( "#slotLabelInterval" ).val( element.slotLabelInterval );
               $( "#defaultView" ).val( element.defaultView );
               $( "#scrollTime" ).timepicker( "setTime", element.scrollTime );
               $( "#weekViewColumnFormat" ).val( element.weekViewColumnFormat );
               $( "#dayViewColumnFormat" ).val( element.dayViewColumnFormat );
            });

            // We only start listening for changes after we have set the form to the correct settings
            start_listening();
         }


         /** ----------------------------------------------------------------------------
          * Event handlers to trigger update when form elements are altered
          */
         function start_listening() {

            // IOS 7 Lightswitch - true/false to integer value for database TINYINT
            $( '#multiDaySelectDisabled' ).change( function() {
               var settings = {
                  multiDaySelectDisabled: ( $( "#multiDaySelectDisabled" ).prop( 'checked' ) ? 1 : 0 )
               }
               update_settings( settings );
            });

            // Modal confirm reset to default
            // [Bootstrap Confirmation plugin]
            $('[data-toggle="confirm-reset"]').confirmation({
               placement: 'top',
               onConfirm: function( e ) {
                  e.preventDefault();
                  // Call the function to reset settings to default
                  reset_default();
               },
               btnOkLabel: 'Reset'
            });

            // Listen for change events in the appearance form
            $( '#form-appearance' ).on( 'change', 'select', function() {
               // This sets and updates all the select inputs
               // We use jQuery val() to also get the multiselect values
               var settings = {};
               settings[ this.name ] = $( this ).val();
               update_settings( settings );
            })
            .on( 'change', 'input[type=text]', function() {
               // This sets and updates the timepicker fields (start time, finish time and scroll time)
               var settings = {};
               settings[ this.name ] = TE.convertMeridianTo24HourTimeFormat( this.value ) + ":00";
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
            // No success notification as timepicker updates with each increment
            $.ajax({
               url: '<?php echo ROOT_RELATIVE_PATH; ?>admin/timesheet-settings/update-settings',
               data: queryString,
               type: 'POST',
               dataType: 'json',
               error: function ( request, error ) {
                  UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "timesheet_adm_set_notif_update_error" ); ?>', 'error' );
               }
            });
         }


         /** ----------------------------------------------------------------------------
          * Resets all settings in the database to default and resets the form to match
          */
         function reset_default() {

            // Update the settings using the querystring we built
            // No success notification as timepicker updates with each increment
            $.ajax({
               url: '<?php echo ROOT_RELATIVE_PATH; ?>admin/timesheet-settings/reset-default-settings',
               type: 'POST',
               dataType: 'json',
               success: function ( json ) {
                  // Fix: Unbind change events to prevent multiple updates being triggered
                  $( '#form-appearance' ).unbind( 'change' );
                  $( '#multiDaySelectDisabled' ).unbind( 'change' );
                  // Get the settings and reset the form
                  get_settings();
                  UN.displayNotice( '<?php echo lang( "system_notif_success" ); ?>', '<?php echo lang( "timesheet_adm_set_notif_reset_success" ); ?>', 'success' );
               },
               error: function ( request, error ) {
                  UN.displayNotice( '<?php echo lang( "system_notif_error" ); ?>', '<?php echo lang( "timesheet_adm_set_notif_update_error" ); ?>', 'error' );
               }
            });
         }

      });

      </script>