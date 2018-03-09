/* -------------------------------------------------------------------
* Copyright (c) 2016 Improved Software - All rights reserved
* Author: Matt Batten
*
* Form Validation
* --------------------------
* Controls placement of errors for forms validated with the jQuery Validation plugin
*
* --------------------------------------------------------------------
*/
(function() { 

	'use strict';

	// basic
   $(".form").validate({

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
               placement = element;if (error.text() !== '') {
                  placement.after(error);
               }
            }
         }
      }

   });

   $("input.numeric").numeric();


}).apply(this, [jQuery]);