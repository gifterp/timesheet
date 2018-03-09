<?php
/**
 * Report Forms Custom JavaScript
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

?>
      <!-- Custom -->
      <script type="text/javascript">

         $( document ).ready( function() {

            // Limit numeric input fields
            $( ".numeric" ).numeric();

            // Set up datepicker fields
            $( '.datepicker' ).datepicker({
               format: 'dd/mm/yyyy',
               autoclose: true,
               orientation : 'bottom',
               clearBtn: true,
               todayBtn: 'linked',
               todayHighlight: true
            });

            // WIP Report form setup
            // Initially set to show previous month date range
            $( '#startDate' ).datepicker( 'update', moment().subtract( 1, 'months' ).format( 'DD/MM/YYYY' ) );
            $( '#endDate' ).datepicker( 'update', moment().format( 'DD/MM/YYYY' ) );
            // Button to clear dates
            $( '.clear-dates' ).click( function() {
               $( '#startDate' ).datepicker( 'update', '' );
               $( '#endDate' ).datepicker( 'update', '' );
            });

            // Ready invoices as a filtered list
            var invoiceOptions = {
               valueNames: [ 'job-title', 'parent-job-name', 'invoice-no' ]
            };
            var invoiceList = new List( 'ready-invoice-list', invoiceOptions );
            // Handle links
            $( document ).on( 'click', '.filtered-list-link', function() {
               var mockInvoiceId = $( this ).data( 'id' );
               window.location.href = '<?php echo ROOT_RELATIVE_PATH; ?>reports/mock-invoice?id=' + mockInvoiceId;
            });

         });
      </script>