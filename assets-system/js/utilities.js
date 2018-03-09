/**
 * Allows new windows to be opened with various sizes
 *
 * Uses sortable and drag and drop list for job checklist section
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author:       Matt Batten <matt@improvedsoftware.com.au>
 */

( function( $ ) {

   /** ----------------------------------------------------------------------------
    * Find some text and then toggle between text to show/hide
    *
    * Useful for updating button text or labels
    *
    * @param  object    selector       The object in the DOM to perform changes on
    * @param  string    show           Text to show
    * @param  string    hide           Text to hide
    * @return void                     Updates the text
    */
   toggle_text = function( selector, show, hide ) {
      // Hide/show text for totals and invoice buttons
      if ( $( selector ).is( ':contains("' + hide + '")' ) ) {
         $( selector ).text( function () {
            return $( selector ).text().replace( hide, show );
         });
      } else {
         $( selector ).text( function () {
            return $ ( selector ).text().replace( show, hide );
         });
      }
   }

}).apply( this, [jQuery] );