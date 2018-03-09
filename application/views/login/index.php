<?php
/* -------------------------------------------------------------------
* Copyright (c) 2016 Improved Software - All rights reserved
* Author: John Gifter C Poja
* 
*
* Login  view
* --------------------------
* Page for login of the system
*
* --------------------------------------------------------------------
*/
?>
<!doctype html>
<html class="fixed">
   <head>

      <!-- Basic -->
      <meta charset="UTF-8">

      <title><?php echo $meta_title; ?></title>


      <!-- Mobile Metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

      <!-- Web Fonts  -->
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

      <!-- Vendor CSS -->
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/bootstrap/css/bootstrap.css" />
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/font-awesome/css/font-awesome.css" />
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/magnific-popup/magnific-popup.css" />
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

<?php echo $pageVendorCss; ?>

      <!-- Theme CSS -->
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/stylesheets/theme.css" />
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/css/enhancements.css" />

      <!-- Skin CSS -->
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets-custom/css/skin.css" />

<?php echo $pageCustomCss; ?>

      <!-- Head Libs -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/modernizr/modernizr.js"></script>

   </head>
   <body>
      <!-- start: page -->
      <section class="body-sign">
         <div class="center-sign">
            <a href="/" class="logo pull-left">
               <img src="<?php echo ROOT_RELATIVE_PATH; ?>assets-custom/img/logo.png" height="54" alt="Improved Software" />
            </a>

            <div class="panel panel-sign">
               <div class="panel-title-sign mt-xl text-right">
                  <h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> Sign In</h2>
               </div>


               <div class="panel-body">
                  <?php echo form_open(); ?>
                        <?php echo $this->session->flashdata('error'); ?>
                        <?php echo validation_errors(); ?>
                     <div class="form-group mb-lg">
                        <label><?php echo lang( "login_fm_username_lbl" ); ?></label>
                        <div class="input-group input-group-icon">
                           <?php echo form_input(['name' => 'username', 'class'=> 'form-control input-lg', 'Placeholder'=> lang( "login_fm_username_ph" )]); ?>
                           <span class="input-group-addon">
                              <span class="icon icon-lg">
                                 <i class="fa fa-user"></i>
                              </span>
                           </span>
                        </div>
                     </div>

                     <div class="form-group mb-lg">
                        <div class="clearfix">
                           <label class="pull-left"><?php echo lang( "login_fm_password_lbl" ); ?></label>
                           <!-- <a href="pages-recover-password.html" class="pull-right">Lost Password?</a> -->
                        </div>
                        <div class="input-group input-group-icon">
                            <?php echo form_password(['name' => 'password', 'class' => 'form-control input-lg', 'Placeholder'=>lang( "login_fm_password_ph" )]); ?>
                           <span class="input-group-addon">
                              <span class="icon icon-lg">
                                 <i class="fa fa-lock"></i>
                              </span>
                           </span>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-8">
                           <div class="checkbox-custom checkbox-default">
                              <input id="RememberMe" name="rememberme" type="checkbox"/>
                              <label for="RememberMe"><?php echo lang( "login_fm_remember_lbl" ); ?></label>
                           </div>
                        </div>
                        <div class="col-sm-4 text-right">

                             <?php echo form_submit('submit', 'Sign In', 'class="btn btn-primary hidden-xs"'); ?>
                             <?php echo form_submit('submit', 'Sign In', 'class="btn btn-primary btn-block btn-lg visible-xs mt-lg"'); ?>
                        </div>
                     </div>

                   <?php echo form_close(); ?>
               </div>
            </div>

            <p class="text-center text-muted mt-md mb-md"><?php echo lang( "login_footer_lbl" ); ?></p>
         </div>
      </section>
      <!-- end: page -->

       <!-- Vendor -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/jquery/jquery.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/jquery-validation/jquery.validate.min.js"></script>
    
<?php echo $pageVendorJS; ?>

      <!-- Theme Base, Components and Settings -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/javascripts/theme.js"></script>
      
      <!-- Theme Initialization Files -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/javascripts/theme.init.js"></script>

<?php echo $pageCustomJS; ?>

   </body>
</html>