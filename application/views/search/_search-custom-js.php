<?php
/**
 * Advance Custom JavaScript
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

// Has the function used to create the settings object
require_once ( $_SERVER['DOCUMENT_ROOT'] . '/assets-system/php/timesheet-functions.php' );
?>
      <!-- Custom -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>


      <script type="text/javascript">

            // Set default data and query if adv search is trigger
            var data = $('#query-value').val();
            var ajax = '/search/get-search-result/'+data;

            /** ----------------------------------------------------------------------------
             * Get query string from url base on basic search function
             */
            $.urlParam = function( name ){
               var results = new RegExp( '[\?&]' + name + '=([^&#]*)' ).exec( window.location.href );
               if ( results != null ){
                  return  results[1] || 0;   
               }
            }

            /** ----------------------------------------------------------------------------
             * Check if search query is null
             * Remove hidden class in advance search panel and show data table result
             */
            if ( $.urlParam( 'c.name' ) != null ) {
               query = $.urlParam( 'c.name' );
               $( '#search-panel' ).removeClass( 'hidden' );
               $( '#name' ).val( query );
               $( '#zone' ).val( '<?php echo $settings->defaultZone; ?>' );
               $( '#distance' ).val( 1000 );
               ajax = '/search/get-search-result/'+query;
            } 

            /** ----------------------------------------------------------------------------
             * Result Panel data table result
             * Ajax depend on adv or basic search form
             */
            table = $( '#search-table' ).DataTable({
               "autoWidth": false,
               "iDisplayLength": 100,
               "aLengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
               "language": {
                     "infoEmpty": "Showing 0 to 0 of 0 entries",
                     "emptyTable": "<b><?php echo lang( "system_msg_nothing_display" ); ?></b>",
               },
              "columns": [
                  { "name": "Id", "data": "jobId", "visible": false, "defaultContent": "" },
                  { "name": "Customer", "data": "customer", "orderable": true },
                  { "name": "Job", "data": "job", "orderable": true },
                  { "name": "Job Type", "data": "jobType", "orderable": true },
                  { "name": "Suburb", "data": "suburb", "orderable": true }
               ],
               "order": [],
               "ajax": ajax
            });


            /** ----------------------------------------------------------------------------
             * Set datatable row as clickable link to job
             */
            $( '#search-table tbody' ).on( 'click', 'tr', function () {
               var data = table.row( this ).data();
               $.each( data, function( key, element ) {
                  if ( key == 'jobId' ) {
                     var id = element;
                     window.location.href = '<?php echo ROOT_RELATIVE_PATH;?>job/view/'+id;
                  }
               });
             });


            /** ----------------------------------------------------------------------------
             * Job Panel list
             * Redirect to job view page for specific link clicked
             */
            $( document ).on( 'click', '.filtered-list-link', function(){
               var id = $( this ).data( 'id' );
               window.location.href = '<?php echo ROOT_RELATIVE_PATH;?>job/view/'+id;
            });


            // Search filters for draggable lists (lists.js)
            // Jobs
            var jobOptions = {
               valueNames: [ 'entry-title', 'entry-job-ref', 'entry-job-name' ]
            };
            var jobList = new List( 'draggable-jobs', jobOptions );
            

            $( '#archived' ).change( function() {
               if ( $( this ).prop( 'checked' ) ) {
                  $( '#jarchived').val(1);
               } else {
                  $( '#jarchived').val(0);
               }
            });
          

       
      </script>