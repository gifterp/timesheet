/**
 * Displays user notifications to the screen using the PNotify jQuery plugin
 *
 * Copyright (c) 2016 Improved Software. All rights reserved
 * Author: 	   Matt Batten <matt@improvedsoftware.com.au>
 * Requires:   PNotify vendor and css files to be included
*/


// User notification object for displaying notices within the system
var UN = {


   /** ----------------------------------------------------------------------------
    * pNotify stack parameters for displaying a notice at the bottom right
    */
    stackBottomRight: {
      "dir1": "up",
      "dir2": "left",
      "firstpos1": 15,
      "firstpos2": 15
   },


   /** ----------------------------------------------------------------------------
    * Displays a notice using the pNotify jQuery plugin
    *
    * @param  string title          Title for the notification
    * @param  string text           Text for the notification
    * @param  string noticeType     Type of notice to be displayed (success, error, notice, info)
    */
   displayNotice: function(title, text, noticeType) {
      var _UN = this;

      notice = new PNotify({
         title: title,
         text: text,
         type: noticeType,
         addclass: "stack-bottomright",
         stack: _UN.stackBottomRight
      });
   }
};