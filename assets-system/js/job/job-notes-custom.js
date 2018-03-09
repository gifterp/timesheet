
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
   var linkNotes     =  $( '#add-notes' ).data('link'); // this will hold the ajax url
   var removeRowNotes =  $( '#add-notes' ).data('delete'); // this will hold the ajax url
   var addSuccessNotes  =  $( '#add-notes' ).data('ajax-success'); // this will hold the add success msg
   var addErrorNotes    =  $( '#add-notes' ).data('ajax-error'); // this will hold the add error msg
   var updateSuccessNotes  =  $( '#add-notes' ).data('ajax-update-success'); // this will hold the update success msg
   var updateErrorNotes    =  $( '#add-notes' ).data('ajax-update-error'); // this will hold the update error msg
   var deleteSuccessNotes  =  $( '#add-notes' ).data('delete-success'); // this will hold the delete success msg
   var deleteErrorNotes    =  $( '#add-notes' ).data('delete-error'); // this will hold the delete error msg
   var userListNotes = $('input[name="user"]').val(); // user list for user dropdow
   var userIdNotes = $('input[name="userId"]').val(); // user id 
   var jobIdNotes = $('input[name="jobId"]').val(); // job id 
   EditableTable_notes = {

      options: {
         addButton: '#add-notes',
         table: '#datatable-editable-notes',
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
         this.$table_notes             = $( this.options.table );
         this.$addButton_notes        = $( this.options.addButton );

         // dialog
         this.dialog          = {};
         this.dialog.$wrapper = $( this.options.dialog.wrapper );
         this.dialog.$cancel     = $( this.options.dialog.cancelButton );
         this.dialog.$confirm = $( this.options.dialog.confirmButton );

         return this;
      },
      loadData: function() {
         this.datatable_notes = this.$table_notes.DataTable({
            "autoWidth": false,
            "order": [],
            "language": {
               "infoEmpty": "Showing 0 to 0 of 0 entries",
               "emptyTable": "<b>There is currently nothing to display</b>",
            },
            "columns": [
               { "name": "Id", "data": "jobNotesId", "visible": false, "defaultContent": "" },
               { "name": "Note Date", "data": "noteDate", "orderable": true, "defaultContent": "","type": "date-eu" },
               { "name": "user Member", "data": "userId", "orderable": true, "defaultContent": "" },
               { "name": "Comment", "data": "comment", "orderable": true, "render": function( data ) { if ( data ) { return data.replace( /\n/g, "<br />" ); } }, "className": "textarea-output", "defaultContent": "" },
               { "name": "Actions", "data": "actions", "sClass": "actions", "orderable": false, "defaultContent": "" },
            ],
            "ajax": '/job/get-notes-rows-json/'+jobIdNotes,
         });
         $( '#datatable-editable-notes' ).on( 'draw.dt', function () {
            $( '.confirmation-callback-notes' ).confirmation({
               onConfirm: function() {
                  var data = $( this );
                  $( '.row-notes' + data[0].id ).trigger('click');
               }
            }); 
         });
         window.dt = this.datatable_notes;

         return this;
      },

      events: function() {
         var _self = this;

         this.$table_notes
            .on('click', 'a.save-row', function( e ) {
               e.preventDefault();
               validateForm( '#tbl-job-notes' );
                  if( $( '#tbl-job-notes' ).valid() ) {
                     _self.rowSave( $(this).closest( 'tr' ) );
                  }
            })
            .on('click', 'a.cancel-row', function( e ) {
               e.preventDefault();

               _self.rowCancel( $(this).closest( 'tr' ) );
            })
            .on('click', 'a.edit-row-notes', function( e ) {
               e.preventDefault();

               _self.rowEdit( $(this).closest( 'tr' ) );
               autosize( $('textarea.textarea-output') );
            })
            .on( 'click', 'a.remove-row-notes', function( e ) {
               e.preventDefault();

               var $row = $(this).closest( 'tr' );
               _self.rowRemove( $row );
                 
            });

         this.$addButton_notes.on( 'click', function(e) {
            e.preventDefault();

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
         this.$addButton_notes.attr({ 'disabled': 'disabled' });

         var actions,
            data,
            $row;

         actions = [
            '<a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>',
            '<a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>',
            '<a href="#" class="on-default edit-row-notes"><i class="fa fa-pencil"></i></a>',
            '<a href="#" class="on-default remove-icon-notes"><i class="fa fa-trash-o"></i></a>'
         ].join(' ');

         data = this.datatable_notes.row.add( [null,null,null,null,actions] ).draw();

          // Move the added row to the top of the table
         var index_notes = 0, // Index for the top of the table
            rowCount_notes = this.datatable_notes.data().length-1,
            insertedRow_notes = this.datatable_notes.row( rowCount_notes ).data(),
            tempRow_notes;
         // Cycle through rows and shift added row to the top by changing the row indexes
         for ( var i = rowCount_notes; i > index_notes; i-- ) {
            tempRow_notes = this.datatable_notes.row( i-1 ).data();
            this.datatable_notes.row( i ).data( tempRow_notes );
            this.datatable_notes.row( i-1 ).data( insertedRow_notes );
         }


         $row = this.datatable_notes.row( 0 ).nodes().to$();

         $row
            .addClass( 'adding' )
            .find( 'td:last' )
            .addClass( 'actions' );

         this.rowEdit( $row );

         //this.datatable_notes.order([1,'asc']).draw(); // always show fields
      },

      rowCancel: function( $row ) {
         var _self = this,
            $actions,
            i,
            data;
         this.$addButton_notes.removeAttr( 'disabled' );
         if ( $row.hasClass('adding') ) {
            this.rowRemove( $row );
         } else {

            data = this.datatable_notes.row( $row.get(0) ).data();
            this.datatable_notes.row( $row.get(0) ).data( data );

            $actions = $row.find('td.actions');
            if ( $actions.get(0) ) {
               this.rowSetActionsDefault( $row );
            }
            _self.reload_notes();
         }
      },

      rowEdit: function( $row ) {
         var _self = this,
            data;
         var option = '';
         $('a.edit-row-notes').addClass('hidden');
         $('a.remove-icon-notes').addClass('hidden');
         this.$addButton_notes.attr({ 'disabled': 'disabled' });
          $('.comment br').remove();
         data = this.datatable_notes.row( $row.get(0) ).data();
         $row.children( 'td' ).each(function( i ) {

            var $this = $( this );
             if ( $this.hasClass('actions') ) {
               _self.rowSetActionsEditing( $row );
               $this.html( '<a href="#" class="on-editing save-row"><i class="fa fa-save"></i></a> <a href="#" class=" on-editing cancel-row"><i class="fa fa-times"></i></a>');
            } else {
               switch(i){
                  case 0:
                     if(data['noteDate'] == undefined){
                        $this.html( '<div class="form-group"><input type="text" name="noteDate" value="'+EditableTable_notes.setDate()+'" required="required" data-msg-required="Note date is required"  class="form-control noteDate white-bg" readonly></div>' );
                     }else{
                        $this.html( '<div class="form-group"><input type="text"  name="noteDate"  class="form-control input-block noteDate white-bg" required="required" data-msg-required="Note date is required" value="' + data['noteDate'] + '" readonly></div>' );
                     }
                     break; 
                  case 1:
                  var option_user;
                     if(data['userId'] == undefined){
                        $.each(eval(userListNotes) , function( key, value ) {
                           $.each(eval(value), function( id , name){
                              if(userIdNotes == id){
                                 option_user += '<option value="'+id+'" selected>'+name+'</option>';
                              }else{
                                 option_user += '<option value="'+id+'">'+name+'</option>';   
                              }
                              
                           });
                         });

                        $this.html( '<div class="form-group"><select class="form-control" name="userId">'+option_user+'</select></div>' );
                     }else{
                        $.each(eval(userListNotes) , function( key, value ) {
                           $.each(eval(value), function( id , name){
                              if(data['userId']== id){
                                 option_user += '<option value="'+id+'" selected>'+name+'</option>';
                              }else{
                                 option_user += '<option value="'+id+'">'+name+'</option>';   
                              }
                              
                           });
                         });

                        $this.html( '<div class="form-group"><select class="form-control" name="userId">'+option_user+'</select></div>' );
                     }
                     
                     break;
                  case 2:
                     if ( data['comment'] == undefined ) {
                        $this.html( '<div class="form-group"><textarea rows="1" wrap="physical" required="required" data-msg-required="Comment is required"  name="comment" class="form-control input-block textarea-output"></textarea></div>' );
                     } else {
                        $this.html( '<div class="form-group"><textarea rows="1"  wrap="physical" required="required" data-msg-required="Comment is required" name="comment" class="form-control comment input-block textarea-output">' + data['comment'] + '</textarea></div>' );
                     }
                     break;
                  case 3:
                     if(data['id'] == undefined){
                        $this.html( '<input type="text" class="form-control">' );
                     }else{
                        $this.html( '<input type="text"   class="form-control input-block" value="' + data['id'] + '"/>' );
                     }
                     break;
               }

            }

         });

         $( '.noteDate' ).datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            orientation: 'bottom',
            todayHighlight: true
         });
      },
      rowSave: function( $row ) {

         var _self         = this,
         $actions,
            values         = [];

         var holder        = '';
         var successNotes  = addSuccessNotes;
         var errorNotes    = addErrorNotes;

            values = $row.find( 'td' ).map( function() {
            var $this = $( this );


               if ( $this.hasClass( 'actions' ) ) {
                  _self.rowSetActionsEditing( $row );
                  return _self.datatable_notes.cell( this ).data();
               }else {
                  if($this.find('input').val() != undefined){                
                     return $.trim( $this.find('input').val() );
                  }
                  if($this.find('select').val() != undefined){                
                     return $.trim( $this.find('select').val() );
                  }
                  if($this.find('textarea').val() != undefined){                
                     return $.trim( $this.find('textarea').val() );
                  }
                  
               }
            });   
            if ( $row.hasClass( 'adding' ) ) {  // determine add data
               this.$addButton_notes.removeAttr( 'disabled' );
               $row.removeClass( 'adding' );
               holder = 'noteDate=' + values[0] + '&userId=' + values[1] + '&comment=' + values[2] + '&jobId=' + jobIdNotes;
            } else { //determine update data
               this.$addButton_notes.removeAttr( 'disabled' );
                var data_rows = this.datatable_notes.row( $row.get( 0 ) ).data(); // hold the data for the whole row
                  // switch condition to pass if its save or updates
                  
                  $.each( data_rows, function( key, value ) {
                     switch( key ) {
                           case 'jobNoteId':
                              holder += 'jobNoteId=' + value;
                           break;
                        }
                  });
                  values = $row.find( 'td' ).map(function() {
                     if ( $( this ).find( 'input' ).val() != undefined ) {
                        holder += ( '&' + $( this ).find( 'input' ).attr( 'name' ) + '=' + $( this ).find( 'input' ).val() );  
                     }
                     if ( $( this ).find( 'select' ).val() != undefined ) {                
                        holder += ( '&' +$( this ).find( 'select' ).attr( 'name' ) + '=' + $( this ).find( 'select' ).val() );  
                     }
                     if ( $( this ).find( 'textarea' ).val() != undefined ) {                
                        holder += ( '&' + $( this ).find( 'textarea' ).attr( 'name' ) + '=' + $( this ).find( 'textarea' ).val() );  
                     }
                  });
                  holder += ( '&jobId=' + jobIdNotes );
                  successNotes   = updateSuccessNotes;
                  errorNotes     = updateErrorNotes;
             }

            // create array for the form
            var arrayData_notes = [holder].join('');
            // jquery ajax to submit data and save it to database
            AJXDB.updateFromTableData_notes( arrayData_notes, successNotes, errorNotes, linkNotes );

            this.datatable_notes.row( $row.get( 0 ) ).data( values );
            $actions = $row.find( 'td.actions' );
            if ( $actions.get(0) ) {
               this.rowSetActionsDefault( $row );
            }

             this.reload_notes();
            
           
      },
      rowRemove: function( $row ) {
         if ( $row.hasClass('adding') ) {
            this.$addButton_notes.removeAttr( 'disabled' );
         }
         var delete_rows = JSON.parse( JSON.stringify( this.datatable_notes.row( $row.get( 0 ) ).data() ) );
         if ( delete_rows.jobNoteId != undefined ) {
            var table_id = ( 'jobNoteId=' + delete_rows.jobNoteId ); 
            // // jquery ajax to submit data and delete it to database
            AJXDB.deleteFromTableData_notes( table_id, deleteSuccessNotes, deleteErrorNotes, removeRowNotes );
         } else {
            this.reload_notes(); // this will remove and reload add input if cancel is click

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
      reload_notes: function() {
          this.datatable_notes.ajax.reload(null,false);
      }

   };

   
   $(function() {
      EditableTable_notes.initialize();  
   }); 
   

}).apply(this, [jQuery]);
