<?php
/**
 * Footer  views that holds global js files and footer of the system
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?> 
      <!-- Vendor -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/jquery/jquery.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/jquery-validation/jquery.validate.min.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/pnotify/pnotify.custom.js"></script>

<?php echo $pageVendorJS;?>

      <!-- Theme Base, Components and Settings -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/javascripts/theme.js"></script>

      <!-- Theme Initialization Files -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/javascripts/theme.init.js"></script>

<?php echo $pageCustomJS;?>

   </body>
</html>