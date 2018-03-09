/**
 * Form modals
 *
 * Uses Magnific Popup jQuery plugin to create modals that can be used with forms
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:       Matt Batten <matt@improvedsoftware.com.au>
 * Requires:     Magnific Popup js and css (included by default)
 *               jQuery Validation js
 */

(function($) {

   'use strict';


   /** ----------------------------------------------------------------------------
    * Display modal with form
    *
    * Popup a modal with a form, with a nice fade-zoom animation
    *
    * @requires    classes <a> tag    modal-with-form modal-with-zoom-anim
    */
   $('.modal-with-form').magnificPopup({
      type: 'inline',
      preloader: false,
      focus: $(this).attr('data-focus-element'),

      fixedContentPos: false,
      fixedBgPos: true,

      overflowY: 'auto',
 
      closeBtnInside: true,
      preloader: false,

      midClick: true,
      removalDelay: 300,
      mainClass: 'my-mfp-zoom-in',
      modal: true,

      // When element is focused, some mobile browsers can zoom in
      // Bad UX design, so we disable it:
      callbacks: {
         beforeOpen: function() {
            if($(window).width() < 700) {
               this.st.focus = false;
            } else {
               this.st.focus = $(this).attr('data-focus-element');
            }
         }
      }
   });


   /** ----------------------------------------------------------------------------
    * Display modal with form - FOR LINKS WITHIN jQuery dataTable ajax
    *
    * Popup a modal with a form, with a nice fade-zoom animation
    * We need to bind the listener to the AJAX data links within the dataTable since
    * they are not part of the original DOM
    *
    * @requires    classes <a> tag    modal-with-form modal-with-zoom-anim
    */
   $('#datatable-editable').on('draw.dt', function () {
      $('.modal-with-form').magnificPopup({
         type: 'inline',
         preloader: false,
         //focus: '#name',

         fixedContentPos: false,
         fixedBgPos: true,

         overflowY: 'auto',

         closeBtnInside: true,
         preloader: false,

         midClick: true,
         removalDelay: 300,
         mainClass: 'my-mfp-zoom-in',
         modal: true,

         // When element is focused, some mobile browsers can zoom in
         // Bad UX design, so we disable it:
         callbacks: {
            beforeOpen: function() {
               if($(window).width() < 700) {
                  this.st.focus = false;
               } else {
                  this.st.focus = '#name';
               }
            }
         }
      });
   });


   /** ----------------------------------------------------------------------------
    * Dismiss a modal without any action being taken. Clears any validation errors
    *
    * @requires    class (modal cancel button)    modal-dismiss
    */
   $(document).on('click', '.modal-dismiss', function (e) {
      e.preventDefault();
      $.magnificPopup.close();
      $(".form-modal").validate().resetForm();
      $('.form-group').removeClass('has-error');
   });


   /** ----------------------------------------------------------------------------
    * Submit modal form for AJAX processing
    *
    * Gets the form ID from the attribute 'data-form-id', then validates the form.
    * If the form is valid the modal is closed and the AJAX processing method is called
    * The JavaScript object and method name are passed via the attributes 'data-js-object' and 'data-js-method'
    * The PNotify success and error message are passed via the attributes 'data-js-success' and 'data-js-error'
    *
    * @requires    class (modal confirm button)        modal-confirm-submit
    * @requires    attribute (modal confirm button)    data-form-id
    * @requires    attribute (modal confirm button)    data-js-object
    * @requires    attribute (modal confirm button)    data-js-method
    * @requires    attribute (modal confirm button)    data-js-success
    * @requires    attribute (modal confirm button)    data-js-error
    */
   $(document).on('click', '.modal-confirm-submit', function (e) {
      var formId      = $(this).attr('data-form-id')          // Form ID
      var ajaxMethod  = $(this).attr('data-ajax-method');     // JavaScript method to handle AJAX processing
      var ajaxUrl     = $(this).attr('data-ajax-url');        // AJAX URL called to process db request
      var ajaxSuccess = $(this).attr('data-ajax-success');    // Notification success message for AJAX function
      var ajaxError   = $(this).attr('data-ajax-error');      // Notification error message for AJAX function

      e.preventDefault();
      validateForm(formId);

      // Check all required form fields are valid
      if ($(formId).valid()) {
   		$.magnificPopup.close();
         // Call the AJAX object method responsible for handing the db update
         eval('AJXDB'+'.'+ajaxMethod)(formId, ajaxSuccess, ajaxError, ajaxUrl);   // Call the method to process the form via AJAX
      }
   });

   $(document).on('focus','.datepicker',function(){
      $(this).datepicker({
         format: 'dd/mm/yyyy',
         autoclose: true,
         orientation : 'bottom'
      });
   });

   $("input.numeric").numeric();
   
}).apply(this, [jQuery]);

  /** ----------------------------------------------------------------------------
  * Validates a form using jQuery Validation plugin. The form ID is passed as a string
  *
  * @param    string  formId      Form ID to validate, format = "#formId"
  * @return   void
  */ 

   function validateForm(formId) {
      $(formId).validate({

         highlight: function( label ) {
            $(label).closest('.form-group').removeClass('has-success').addClass('has-error');
         },
         success: function( label ) {
            $(label).closest('.form-group').removeClass('has-error');
            label.remove();
         },
         errorPlacement: function( error, element ) {
            if (element.attr('type') == 'radio' || element.attr('type') == 'checkbox') {
               error.appendTo(element.parent().parent());
            } else {
               var placement = element.closest('.input-group');
               if (!placement.get(0)) {
                  placement = element;
               }
               if (error.text() !== '') {
                  placement.after(error);
               }
            }
         }
         
      }).form();
   }

   
