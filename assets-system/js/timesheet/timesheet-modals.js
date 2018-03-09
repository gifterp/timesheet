/*
 * Copyright 2016 MalTek Solutions
 * Name: 			UI Elements / Modals - Diary page
 * Written by: 	MalTek Solutions - (http://www.maltek.com.au)
*/

(function($) {

	/*
	Modal dismiss
	*/
	$( document ).on( 'click', '.modal-dismiss', function( e ) {
		e.preventDefault();
		$.magnificPopup.close();
      $( '#calendar' ).fullCalendar( 'removeEvents', 'droppable' ); // Cleanup any dropped events that weren't finished
	});

	/*
	Modal confirm job or task chosen for a new entry (Hidden button)
	*/
	$( document ).on( 'click', '.modal-confirm-entry', function( e ) {
      e.preventDefault();
		$.magnificPopup.close();
	});

	/*
	Modal confirm comment for a new entry (Hidden button)
	*/
	$( document ).on( 'click', '.modal-confirm-entry-comment', function( e ) {
		e.preventDefault();
      $.magnificPopup.close();
	});

   /*
   Listen for TAB or ENTER keydown event when entering initial comment and trigger submit
   */
   $('#initial-comment').keydown(function(e){
      if ( e.which == 9 || e.which == 13 ) { // Look for TAB or ENTER key to submit comment
         $( 'button.comment-action' ).click();
      }
   });

	/*
	Modal confirm update entry
	*/
	$( document ).on( 'click', '.modal-confirm-edit', function( e ) {
      e.preventDefault();
      $.validator.setDefaults({ ignore: ":hidden:not(.chosen-select-edit)" })
      $( "#entry-form" ).validate().form();
      if ( $( "#job-chooser-edit" ).valid() && $( "#task-chooser-edit" ).valid()) {
   		$.magnificPopup.close();
         TE.editEntry( TS.multiDaySelectDisabled );
      }
	});


   /*
   Modal confirm delete entry
   [Bootstrap Confirmation plugin]
   */
   $('[data-toggle="confirm-delete"]').confirmation({
      placement: 'top',
      onConfirm: function( e ) {
         e.preventDefault();
   		$.magnificPopup.close();
         // Call the function to delete entries
         TE.deleteEntry( $( "#entryId" ).val());
      }
   });


	/*
	Job chooser modal popup
	*/
   $( '.modal-with-form-job-chooser' ).magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '.job-chooser',
      alignTop: true,
		modal: true
	});

	/*
	Task chooser modal popup
	*/
   $( '.modal-with-form-task-chooser' ).magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '.task-chooser',
      alignTop: true,
		modal: true
	});

	/*
	Comment modal popup
	*/
   $( '.modal-with-form-comment' ).magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#initial-comment',
      alignTop: true,
		modal: true
	});

	/*
	Edit entry modal popup
	*/
   $( '.modal-with-form-edit-entry' ).magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#task-comment',
      alignTop: true,
		modal: true,
      callbacks: {
      open: function() {
            $( '#entry-form .error' ).hide();
         }
      }
	});

	/*
	Ajax
	*/
	$( '.simple-ajax-modal' ).magnificPopup({
		type: 'ajax',
		modal: true
	});

}).apply( this, [jQuery] );