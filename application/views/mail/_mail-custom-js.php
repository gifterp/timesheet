<?php

/**
 * Custom JavaScript for the mail section
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:       Gifter Post <gifter@improvedsoftware.com.au>
 * Requires:     [Vendor] dataTables JS + CSS
 *               [Vendor] Magnific Popup JS + CSS
 *               [Vendor] PNotify CSS
 */ 

?>      <!-- Custom -->
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/user-notification.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/db/ajax-db.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/form-modal.js"></script>
   <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/js/date-eu.js"></script>
   <script type="text/javascript">
    

   (function($) { 

      'use strict';
      var link          =  $( '#addToTable' ).data('link'); // this will hold the ajax url
      var remove_row    =  $( '#addToTable' ).data('delete'); // this will hold the ajax url
      var addSuccess    =  $( '#addToTable' ).data('ajax-success'); // this will hold the add success msg
      var addError      =  $( '#addToTable' ).data('ajax-error'); // this will hold the add error msg
      var updateSuccess =  $( '#addToTable' ).data('update-success'); // this will hold the update success msg
      var updateError   =  $( '#addToTable' ).data('update-error'); // this will hold the update error msg
      var deleteSuccess =  $( '#addToTable' ).data('delete-success'); // this will hold the delete success msg
      var deleteError   =  $( '#addToTable' ).data('delete-error'); // this will hold the delete error msg
      EditableTable = {

         options: {
            addButton: '#addToTable',
            table: '#datatable-editable',
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
                   "order" : [],
                   "aaSorting": [],
                  "language": {
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "emptyTable": "<b><?php echo lang( "system_msg_nothing_display" ); ?></b>",
                  },
                  "columns": [
                     {"name": "Id","data": "id","visible": false,"defaultContent": ""},
                     {"name": "Send", "data": "sendDate", "orderable": true,"defaultContent": "","type": "date-eu", targets: 1  },
                     {"name": "Recipient", "data": "recipient", "orderable": true,"defaultContent": ""},
                     {"name": "Description", "data": "description", "orderable": true,"defaultContent": "","render": function( data ) { if ( data ) { return data.replace( /\n/g, "<br />" ); } }, "className": "textarea-output", "defaultContent": "" },
                     {"name": "Actions", "data": "actions", "sClass": "actions", "orderable": false,"defaultContent": ""},
                  ],
                  "ajax": '/mail/get-rows-json'
            });
            $( '#datatable-editable' ).on( 'draw.dt', function () {
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
                  validateForm( '#table-mail' );
                  if( $( '#table-mail' ).valid() ) {
                     _self.rowSave( $( this ).closest( 'tr' ) );
                  }
               })
               .on('click', 'a.cancel-row', function( e ) {
                  e.preventDefault();

                  _self.rowCancel( $( this ).closest( 'tr' ) );
               })
               .on('click', 'a.edit-row', function( e ) {
                  e.preventDefault();

                   _self.rowEdit( $( this ).closest( 'tr' ) );
                  autosize( $( 'textarea.textarea-output' ) );
               })
               .on( 'click', 'a.remove-row', function( e ) {
                  e.preventDefault();

                  var $row = $( this ).closest( 'tr' );
                   _self.rowRemove( $row );
               });

            this.$addButton.on( 'click', function(e) {
               e.preventDefault();
               e.stopPropagation();
       
                _self.rowAdd();
               autosize( $('textarea.textarea-output') );
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

            //this.datatable.order([1,'asc']).draw().dt; // always show fields
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
               $('a.edit-row').addClass('hidden');
               $('a.remove-icon').addClass('hidden');
               this.$addButton.attr({ 'disabled': 'disabled' });
            data = this.datatable.row( $row.get(0) ).data();

            $row.children( 'td' ).each(function( i ) {
               var $this = $( this );
                if ( $this.hasClass('actions') ) {
                  _self.rowSetActionsEditing( $row );
                  $this.html( '<a href="#" class="on-editing save-row"><i class="fa fa-save"></i></a> <a href="#" class=" on-editing cancel-row"><i class="fa fa-times"></i></a>');
               } else {
                  switch( i ) {
                     case 0:
                        if ( data['sendDate'] == undefined ) {
                           $this.html( '<div class="form-group"><input type="text" name="sendDate" value="' + EditableTable.setDate() + '" required="required" data-msg-required="Send date is required"  class="form-control sendDate"></div>' );
                        }else{
                           $this.html( '<div class="form-group"><input type="text"  name="sendDate"  class="form-control input-block sendDate" required="required" data-msg-required="Send date is required" value="' + data['sendDate'] + '"/></div>' );
                        }
                     break;
                     case 1:
                        if ( data['recipient'] == undefined ) {
                           $this.html( '<div class="form-group"><input type="text" name="recipient"  class="form-control input-block" required="required" data-msg-required="Recipient is required" value=""/></div>' );
                        } else {
                           $this.html( '<div class="form-group"><input type="text" name="recipient"    class="form-control input-block" required="required" data-msg-required="Recipient is required" value="' + data['recipient'] + '"/></div>' );
                        }
                        
                     break;
                     case 2:
                        if ( data['description'] == undefined ) {
                           $this.html( '<div class="form-group"><textarea class="form-control input-block textarea-output" rows="1" name="description" style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 34px;" wrap="physical"></textarea></div>' );
                        } else {
                           $this.html( '<div class="form-group"><textarea class="form-control input-block textarea-output" rows="1" name="description" style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 34px;" wrap="physical">' + data['description'] + '</textarea></div>' );
                        }
                     break;
                     case 3:
                        if ( data['id'] == undefined ) {
                           $this.html( '<input type="text" class="form-control">' );
                        } else {
                           $this.html( '<input type="text"   class="form-control input-block" value="' + data['id'] + '"/>' );
                        }
                     break;
                  }

               }

            });
         },
         rowSave: function( $row ) {

            var _self      = this,
            $actions,
               values      = [];

            var holder     = '';
            var success    = addSuccess;
            var error      = addError;

               values = $row.find('td').map(function() {
               var $this = $(this);


                  if ( $this.hasClass('actions') ) {
                     _self.rowSetActionsEditing( $row );
                     return _self.datatable.cell( this ).data();
                  }else {
                     if($this.find('input').val() != undefined){                
                        return $.trim( $this.find('input').val() );
                     }
                     if($this.find('textarea').val() != undefined){                
                        return $.trim( $this.find('textarea').val() );
                     }
                     
                  }
               });   
               if ( $row.hasClass( 'adding' ) ) {  // determine add data
                  this.$addButton.removeAttr( 'disabled' );
                  $row.removeClass( 'adding' );
                  holder = 'sendDate=' + values[0] + '&recipient=' + values[1] + '&description=' + values[2];
               } else { //determine update data
                  this.$addButton.removeAttr( 'disabled' );
                  var data_rows = this.datatable.row( $row.get( 0 ) ).data(); // hold the data for the whole row
                     // switch condition to pass if its save or updates
                     $.each( data_rows, function( key, value ) {
                        switch( key ) {
                              case 'id':
                                 holder += 'mailId=' + value;
                              break;
                           }
                     });
                     values = $row.find( 'td' ).map( function() {
                        if ( $( this ).find( 'input' ).val() != undefined ) {
                           holder += ( '&' + $( this ).find( 'input' ).attr( 'name' ) + '=' + $( this ).find( 'input' ).val() );  
                        }
                        if( $( this ).find( 'textarea' ).val() != undefined ) {                
                           holder += ( '&' + $( this ).find( 'textarea' ).attr( 'name' ) + '=' + $( this ).find( 'textarea' ).val() );  
                        }
                     });
                     success  = updateSuccess;
                     error    = updateError;
                }

               // create array for the form
               var arrayData = [holder].join('');
               // jquery ajax to submit data and save it to database
               AJXDB.updateFromTableData( arrayData, success, error, link );

               this.datatable.row( $row.get( 0 ) ).data( values );
               $actions = $row.find('td.actions');
               if ( $actions.get( 0 ) ) {
                  this.rowSetActionsDefault( $row );
               }

               this.reload(); 
               
              
         },
         rowRemove: function( $row ) {
            if ( $row.hasClass( 'adding' ) ) {
               this.$addButton.removeAttr( 'disabled' );
            }
            var delete_rows = JSON.parse( JSON.stringify( this.datatable.row( $row.get( 0 ) ).data() ) );
            if( delete_rows.id != undefined ) {
               var talbe_id = ( 'mailId=' + delete_rows.id ); 
               // // jquery ajax to submit data and delete it to database
               AJXDB.deleteFromTableData( talbe_id, deleteSuccess, deleteError, remove_row );
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
            this.datatable.ajax.reload(null,false);
            // location.reload(); 
         }

      };

      
      $(function() {
         EditableTable.initialize(); 
         // Trigger datepicker on focus
         $(document).on('focus','.sendDate',function(){
             $(this).datepicker({
               format: 'dd/mm/yyyy',
               autoclose: true,
               orientation : 'bottom'
            });
         });

         // Avoid user to type on send date
          $(document).on('keydown','.sendDate',function(e){
            e.preventDefault();
            return false;
         });
         
      }); 
      

   }).apply(this, [jQuery]);
   </script>