/**
 * Custom JavaScript for the job invoice section
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:       Gifter Post <gifter@improvedsoftware.com.au>
 * Requires:     [Vendor] dataTables JS + CSS 
 *               [Vendor] Magnific Popup JS + CSS
 *               [Vendor] PNotify CSS
 */ 

(function($) {
 
   'use strict'; 

   var link     =  $( '#add-invoice' ).data('link'); // this will hold the ajax url
   var removeRow =  $( '#add-invoice' ).data('delete'); // this will hold the ajax url
   var addSuccess  =  $( '#add-invoice' ).data('ajax-success'); // this will hold the add success msg
   var addError    =  $( '#add-invoice' ).data('ajax-error'); // this will hold the add error msg
   var updateSuccess  =  $( '#add-invoice' ).data('ajax-update-success'); // this will hold the update success msg
   var updateError    =  $( '#add-invoice' ).data('ajax-update-error'); // this will hold the update error msg
   var deleteSuccess  =  $( '#add-invoice' ).data('delete-success'); // this will hold the delete success msg
   var deleteError    =  $( '#add-invoice' ).data('delete-error'); // this will hold the delete error msg
   var userList = $('input[name="user"]').val(); // user list for user dropdow
   var userId = $('input[name="userId"]').val(); // user id 
   var jobId = $('input[name="jobId"]').val(); // job id

   EditableTable = {

      options: {
         addButton: '#add-invoice',
         table: '#datatable-editable-invoice',
         dialog: {
            wrapper: '#dialog', 
            cancelButton: '#dialogCancel',
            confirmButton: '#dialogConfirm',
         }
      },

      initialize: function() {
         this
            .setVars()
            .loadData()
            .events();
      },
      setVars: function() {
         this.$table          = $( this.options.table );
         this.$addButton         = $( this.options.addButton );

         // dialog
         this.dialog          = {};
         this.dialog.$wrapper = $( this.options.dialog.wrapper );
         this.dialog.$cancel     = $( this.options.dialog.cancelButton );
         this.dialog.$confirm = $( this.options.dialog.confirmButton );

         return this;
      },
      loadData: function() {
         this.datatable = this.$table.DataTable({
           "autoWidth": false,
               "order": [],
               "language": {
                     "infoEmpty": "Showing 0 to 0 of 0 entries",
                     "emptyTable": "<b>There is currently nothing to display</b>",
               },
               "aaSorting": [
                  []
               ],
               "columns": [
                  {"name": "Id","data": "invoiceId","visible": false,"defaultContent": ""},
                  {"name": "Date", "data": "invoiceDate", "orderable": true,"defaultContent": "","type": "date-eu"},
                  {"name": "Inv. No", "data": "actualInvoiceNo", "orderable": true,"defaultContent": ""},
                  {"name": "Amount", "data": "amount", "orderable": true,"defaultContent": ""},
                  {"name": "Mock Inv. No.", "data": "mockInvoiceId", "orderable": true,"defaultContent": ""},
                  {"name": "Actions", "data": "actions", "sClass": "actions", "orderable": false,"defaultContent": ""},
               ],
               "ajax": '/job/get-invoice-rows-json/' + jobId
         });
         $( '#datatable-editable-invoice' ).on( 'draw.dt', function () {
            $( '.confirmation-callback' ).confirmation({
               onConfirm: function() {
                  var data = $( this );
                  $( '.row' + data[0].id ).trigger('click');
               }
            }); 
         });
         window.dt = this.datatable;

         return this;
      },

      events: function() {
         var _self = this;

         this.$table
            .on('click', 'a.save-row', function( e ) {
               e.preventDefault();
               validateForm( '#tbl-job-invoice' );
                  if( $( '#tbl-job-invoice' ).valid() ) {
                     _self.rowSave( $(this).closest( 'tr' ) );
                  }
            })
            .on('click', 'a.cancel-row', function( e ) {
               e.preventDefault();

               _self.rowCancel( $(this).closest( 'tr' ) );
            })
            .on('click', 'a.edit-row', function( e ) {
               e.preventDefault();

               _self.rowEdit( $(this).closest( 'tr' ) );
            })
            .on( 'click', 'a.remove-row', function( e ) {
               e.preventDefault();

               var $row = $(this).closest( 'tr' );
               _self.rowRemove( $row );
               
            });

         this.$addButton.on( 'click', function(e) {
            e.preventDefault();

            _self.rowAdd();
         });

         this.dialog.$cancel.on( 'click', function( e ) {
            e.preventDefault();
            $.magnificPopup.close();
         });

         return this;
      },
      setDate:function(){
             var now = new Date();
             var day = ( "0" + now.getDate() ).slice( -2 );
             var month = ( "0" + ( now.getMonth() + 1) ).slice( -2 );
             var today = ( day ) + "/" + ( month ) + "/" + now.getFullYear();
            return today;
      },

         



      // ==========================================================================================
      // ROW FUNCTIONS
      // ==========================================================================================
      rowAdd: function() {
         this.$addButton.attr({ 'disabled': 'disabled' });

         var actions,
            data,
            $row;

         actions = [
            '<a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>',
            '<a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>',
            '<a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>',
            '<a href="#" class="on-default remove-icon"><i class="fa fa-trash-o"></i></a>'
         ].join(' ');

         data = this.datatable.row.add( [null,null,null,null,actions] ).draw();

         // Move the added row to the top of the table
         var index = 0, // Index for the top of the table
            rowCount = this.datatable.data().length-1,
            insertedRow = this.datatable.row( rowCount ).data(),
            tempRow;
       
         // Cycle through rows and shift added row to the top by changing the row indexes
         for ( var i = rowCount; i > index; i-- ) {
            tempRow = this.datatable.row( i-1 ).data();
            this.datatable.row( i ).data( tempRow );
            this.datatable.row( i-1 ).data( insertedRow );
         }

         $row = this.datatable.row( 0 ).nodes().to$();
         $row
            .addClass( 'adding' )
            .find( 'td:last' )
            .addClass( 'actions' );

         this.rowEdit( $row );
      },

      rowCancel: function( $row ) {
         var _self = this,
            $actions,
            i,
            data;
         this.$addButton.removeAttr( 'disabled' );
         if ( $row.hasClass('adding') ) {
            this.rowRemove( $row );
         } else {
            data = this.datatable.row( $row.get(0) ).data();
            this.datatable.row( $row.get(0) ).data( data );

            $actions = $row.find('td.actions');
            if ( $actions.get(0) ) {
               this.rowSetActionsDefault( $row );
            }
            _self.reload();
         }
      },

      rowEdit: function( $row ) {
         var _self = this,
            data;
         var option = '';
         $( 'a.edit-row' ).addClass( 'hidden' );
         $( 'a.remove-icon' ).addClass( 'hidden' );
         this.$addButton.attr( { 'disabled': 'disabled' } );
         data = this.datatable.row( $row.get( 0 ) ).data();
         $row.children( 'td' ).each( function( i ) {

            var $this = $( this );
            if ( $this.hasClass('actions') ) {
               _self.rowSetActionsEditing( $row );
               $this.html( '<a href="#" class="on-editing save-row"><i class="fa fa-save"></i></a> <a href="#" class=" on-editing cancel-row"><i class="fa fa-times"></i></a>');
            } else {
               switch( i ) {
                  case 0:
                     if ( data['invoiceDate'] == undefined ) {
                        $this.html( '<div class="form-group"><input type="text" name="invoiceDate" value="' + EditableTable.setDate() + '" class="form-control invoiceDate white-bg" required data-msg-required="Invoice date is required" readonly></div>' );
                     } else {
                        $this.html( '<div class="form-group"><input type="text" name="invoiceDate" value="' + data['invoiceDate'] + '" class="form-control input-block invoiceDate white-bg" required data-msg-required="Invoice date is required" readonly></div>' );
                     }
                  break;
                  case 1:
                     if ( data['actualInvoiceNo'] == undefined ) {
                        $this.html( '<div class="form-group"><input type="text" maxlength="15" name="actualInvoiceNo" class="form-control input-block numeric" required data-msg-required="Invoice No. is required" value=""/></div>' );
                     } else {
                        $this.html( '<div class="form-group"><input type="text" maxlength="15" class="form-control input-block numeric" name="actualInvoiceNo" required data-msg-required="Invoice No. is required" value="' + data['actualInvoiceNo'] + '"/></div>' );
                     }
                     
                  break;
                  case 2:
                     if ( data['amount'] == undefined ) {
                        $this.html( '<div class="form-group"><input type="text" maxlength="12" required data-msg-required="Amount is required" name="amount"  class="form-control input-block numeric" value=""/></div>' );
                     } else {
                        var amount = data['amount'].replace("$", ""); 
                        $this.html( '<div class="form-group"><input type="text" maxlength="12" required data-msg-required="Amount is required" class="form-control input-block numeric" name="amount" value="' + amount + '"/></div>' );
                     }
                  break;
                  case 3:
                     if ( data['mockInvoiceId'] == undefined ) {

                        $this.html( '<div class="form-group"><input type="text" maxlength="8" name="mockInvoiceId" class="form-control input-block numeric" value=""/></div>' );
                     } else {
                        var mockInvoiceId = data['mockInvoiceId']; 
                        $this.html( '<div class="form-group"><input type="text" maxlength="8" name="mockInvoiceId" class="form-control input-block numeric" value="' + $(mockInvoiceId).text() + '"/></div>' );
                     }
                  break;
               }

            }

         });

         $( '.invoiceDate' ).datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            orientation: 'bottom',
            todayHighlight: true
         });
         $('.numeric').numeric();
         // // Load validation for the datatables
         // load_form_validation();
         
      },
      rowSave: function( $row ) {

         var _self     = this,
         $actions,
            values      = [];

         var holder     = '';
         var success    = addSuccess;
         var error      = addError;


            values = $row.find( 'td' ).map( function() {
            var $this = $(this);


               if ( $this.hasClass( 'actions' ) ) {
                  _self.rowSetActionsEditing( $row );
                  return _self.datatable.cell( this ).data();
               } else {
                  if( $this.find( 'input' ).val() != undefined ) {
                     return $.trim( $this.find( 'input' ).val() );
                  }

                  
               }
            });
            
                if ( $row.hasClass( 'adding' ) ) {  // determine add data
                  this.$addButton.removeAttr( 'disabled' );
                  $row.removeClass( 'adding' );
                  holder = 'invoiceDate=' + values[0] 
                  + '&actualInvoiceNo=' + values[1] 
                  + '&amount=' + values[2] 
                  + '&mockInvoiceId=' + values[3]
                  + '&jobId=' + jobId;
                } else { //determine update data
                  this.$addButton.removeAttr( 'disabled' );
                   var data_rows = this.datatable.row( $row.get( 0 ) ).data(); // hold the data for the whole row
                     // switch condition to pass if its save or updates
                     
                     $.each( data_rows, function( key, value ) {
                        switch( key ) {
                              case 'invoiceId':
                                 holder += 'invoiceId=' + value;
                              break;
                           }
                     });
                     values = $row.find( 'td' ).map(function() {
                        if ( $( this ).find( 'input' ).val() != undefined ) {
                           // holder = field name = field value;
                           holder += ( '&' + $( this ).find( 'input' ).attr( 'name' ) + '=' + $( this ).find( 'input' ).val() );  
                        }
                        
                     });
                     holder   += ( '&jobId=' + jobId );
                     success  = updateSuccess;
                     error    = updateError;
                }
               // create array for the form
               var arrayData = [holder].join('');
               // jquery ajax to submit data and save it to database
               AJXDB.updateFromTableData( arrayData, success, error, link );

               this.datatable.row( $row.get( 0 ) ).data( values );
               $actions = $row.find( 'td.actions' );
               if ( $actions.get( 0 ) ) {
                  this.rowSetActionsDefault( $row );
               }
                this.reload(); 
         
           
      },
      rowRemove: function( $row ) {
         if ( $row.hasClass('adding') ) {
            this.$addButton.removeAttr( 'disabled' );
         }
         var delete_rows = JSON.parse( JSON.stringify( this.datatable.row( $row.get( 0 ) ).data() ) );
         if ( delete_rows.invoiceId != undefined ) {
            var table_id = ( 'invoiceId=' + delete_rows.invoiceId ); 
            // // jquery ajax to submit data and delete it to database
            AJXDB.deleteFromTableData( table_id, deleteSuccess, deleteError, removeRow );
         } else {
            this.reload(); // this will remove and reload add input if cancel is click

         }
      },

      rowSetActionsEditing: function( $row ) {
         $row.find( '.on-editing' ).removeClass( 'hidden' );
         $row.find( '.on-default' ).addClass( 'hidden' );
      },

      rowSetActionsDefault: function( $row ) {
         $row.find( '.on-editing' ).addClass( 'hidden' );
         $row.find( '.on-default' ).removeClass( 'hidden' );
      },

      // Called from the AJAX success method after datatbase has been modified
      reload: function() {
         this.datatable.ajax.reload( null, false );
      }

   };

   $(function() {
      EditableTable.initialize();
   }); 
   

}).apply(this, [jQuery]);
