<?php
/**
 * Header  views that holds global css files and header of the system
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>
<!doctype html>
<html class="fixed sidebar-left-collapsed">
   <head>

      <!-- Basic -->
      <meta charset="UTF-8">

      <title><?php echo $meta_title;?></title>

      <!-- Mobile Metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

      <!-- Web Fonts  -->
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

      <!-- Vendor CSS -->
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/bootstrap/css/bootstrap.css" />
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/font-awesome/css/font-awesome.css" />
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/magnific-popup/magnific-popup.css" />
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/pnotify/pnotify.custom.css" />

<?php echo $pageVendorCss;?>

      <!-- Theme CSS -->
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets/stylesheets/theme.css" />
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets-system/css/enhancements.css" />

      <!-- Skin CSS -->
      <link rel="stylesheet" href="<?php echo ROOT_RELATIVE_PATH; ?>assets-custom/css/skin.css" />

<?php echo $pageCustomCss;?>

      <!-- Head Libs -->
      <script src="<?php echo ROOT_RELATIVE_PATH; ?>assets/vendor/modernizr/modernizr.js"></script>

   </head>
   <body>
      <section class="body">

         <!-- start: header -->
         <header class="header">
            <div class="logo-container">
               <a href="../" class="logo">
                  <img src="<?php echo ROOT_RELATIVE_PATH; ?>assets-custom/img/logo.png" height="35" alt="Porto Admin" />
               </a>
               <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                  <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
               </div>
            </div>
         
            <!-- start: search & user box -->
            <div class="header-right">

               <form action="<?php echo ROOT_RELATIVE_PATH; ?>search/result" class="search nav-form">
                  <div class="input-group input-search">
                     <input type="text" class="form-control" name="c.name" id="q" placeholder="Search..." required="required">
                     <span class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                     </span>
                  </div>
               </form>
         
               <span class="separator"></span>
         
               <div id="userbox" class="userbox">
                  <a href="#" data-toggle="dropdown">
                     <figure class="profile-picture">
                        <i class="fa fa-lg fa-user"></i>
                     </figure>
                     <div class="profile-info">
                        <span class="name"><?php echo $_SESSION['fullName']?></span>
                     </div>
         
                     <i class="fa custom-caret"></i>
                  </a>
         
                  <div class="dropdown-menu">
                     <ul class="list-unstyled">
                        <li class="divider"></li>
                        <li>
                        <?php echo anchor('user/logout', '<i class="fa fa-power-off"></i> Logout'); ?>
                          <!--  <a role="menuitem" tabindex="-1" href=""><i class="fa fa-power-off"></i> Logout</a> -->
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
            <!-- end: search & user box -->
         </header>
         <!-- end: header -->

         <!-- start: inner wrapper -->
         <div class="inner-wrapper">
            <!-- start: sidebar -->
            <aside id="sidebar-left" class="sidebar-left">
            
               <div class="sidebar-header">
                  <div class="sidebar-title">
                     Navigation
                  </div>
                  <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                     <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                  </div>
               </div>
            
               <div class="nano">
                  <div class="nano-content">
                     <nav id="menu" class="nav-main" role="navigation">
                        <ul class="nav nav-main">
                           <li class="nav-active">
                              <a href="<?php echo ROOT_RELATIVE_PATH; ?>search">
                                 <i class="fa fa-search" aria-hidden="true"></i>
                                 <span><?php echo lang('system_menu_adv_search') ?></span>
                              </a>
                           </li>
                           <li>
                              <a href="<?php echo ROOT_RELATIVE_PATH; ?>customer">
                                 <i class="fa fa-users" aria-hidden="true"></i>
                                 <span><?php echo lang('system_menu_customer') ?></span>
                              </a>
                           </li>
                           <li>
                              <a href="<?php echo ROOT_RELATIVE_PATH; ?>job">
                                 <i class="fa  fa-folder-open" aria-hidden="true"></i>
                                 <span><?php echo lang('system_menu_jobs') ?></span>
                              </a>
                           </li>
                           <li>
                              <a href="<?php echo ROOT_RELATIVE_PATH; ?>contact">
                                 <i class="fa fa-book" aria-hidden="true"></i>
                                 <span><?php echo lang('system_menu_contacts') ?></span>
                              </a>
                           </li>
                           <li>
                              <a href="<?php echo ROOT_RELATIVE_PATH; ?>mail">
                                 <i class="fa fa-envelope " aria-hidden="true"></i>
                                 <span><?php echo lang('system_menu_mail') ?></span>
                              </a>
                           </li>
                           <li>
                              <a href="<?php echo ROOT_RELATIVE_PATH; ?>timesheet">
                                 <i class="fa fa-clock-o" aria-hidden="true"></i>
                                 <span><?php echo lang('system_menu_timesheets') ?></span>
                              </a>
                           </li>
                           <li class="nav-parent">
                              <a>
                                 <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                 <span><?php echo lang('system_menu_reports') ?></span>
                              </a>
                              <ul class="nav nav-children">
                                 <li>
                                    <a href="<?php echo ROOT_RELATIVE_PATH; ?>reports/form/wip"><?php echo lang('system_menu_reports_wip') ?></a>
                                 </li>
                                 <li>
                                    <a href=""><?php echo lang('system_menu_reports_staff') ?></a>
                                 </li>
                                 <li>
                                    <a href=""><?php echo lang('system_menu_reports_compliance') ?></a>
                                 </li>
                              </ul>
                           </li>
                           <?php if ( $_SESSION['accessLevel'] > 49 ) { ?>
                           <li class="nav-parent">
                              <a>
                                 <i class="fa fa-shield" aria-hidden="true"></i>
                                 <span><?php echo lang('system_menu_adm') ?></span>
                              </a>
                              <ul class="nav nav-children">
                                 <li>
                                    <a href="<?php echo ROOT_RELATIVE_PATH; ?>user"><?php echo lang('system_menu_adm_users') ?></a>
                                 </li>
                                 <li>
                                    <a href="<?php echo ROOT_RELATIVE_PATH; ?>checklist"><?php echo lang('system_menu_adm_checklists') ?></a>
                                 </li>
                                 <li class="nav-parent">
                                    <a><?php echo lang('system_menu_adm_lists') ?></a>
                                    <ul class="nav nav-children">
                                       <li>
                                          <a href="<?php echo ROOT_RELATIVE_PATH; ?>department"><?php echo lang('system_menu_adm_departments') ?></a>
                                       </li>
                                       <li>
                                          <a href="<?php echo ROOT_RELATIVE_PATH; ?>job-type"><?php echo lang('system_menu_adm_job_types') ?></a>
                                       </li>
                                       <li>
                                          <a href="<?php echo ROOT_RELATIVE_PATH; ?>council"><?php echo lang('system_menu_adm_councils') ?></a>
                                       </li>
                                    </ul>
                                 </li>
                                 <li class="nav-parent">
                                    <a><?php echo lang('system_menu_adm_timesheets') ?></a>
                                    <ul class="nav nav-children">
                                       <li>
                                          <a href="<?php echo ROOT_RELATIVE_PATH; ?>admin/timesheet-settings"><?php echo lang('system_menu_adm_timesheet_settings') ?></a>
                                       </li>
                                       <li>
                                          <a href="<?php echo ROOT_RELATIVE_PATH; ?>admin/timesheet-entry-addition"><?php echo lang('system_menu_adm_timesheet_entry_add') ?></a>
                                       </li>
                                       <li>
                                          <a href="<?php echo ROOT_RELATIVE_PATH; ?>admin/timesheet-tasks"><?php echo lang('system_menu_adm_timesheet_tasks') ?></a>
                                       </li>
                                    </ul>
                                 </li>
                                 <li class="nav-parent">
                                    <a><?php echo lang('system_menu_adm_reports') ?></a>
                                    <ul class="nav nav-children">
                                       <li>
                                          <a href="<?php echo ROOT_RELATIVE_PATH; ?>admin/mockinvoice-categories"><?php echo lang('system_menu_adm_mockinvoice_categories') ?></a>
                                       </li>
                                       <li>
                                          <a href="<?php echo ROOT_RELATIVE_PATH; ?>admin/mockinvoice-description"><?php echo lang('system_menu_adm_mockinvoice_desc') ?></a>
                                       </li>
                                    </ul>
                                 </li>
                                 <li>
                                    <a href="<?php echo ROOT_RELATIVE_PATH; ?>admin/system-settings"><?php echo lang('system_menu_adm_system_settings') ?></a>
                                 </li>
                              </ul>
                           </li>
                           <?php } ?>
                        </ul>
                     </nav>
                  </div>
            
                  <script>
                     // Preserve Scroll Position
                     if (typeof localStorage !== 'undefined') {
                        if (localStorage.getItem('sidebar-left-position') !== null) {
                           var initialPosition = localStorage.getItem('sidebar-left-position'),
                              sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                           
                           sidebarLeft.scrollTop = initialPosition;
                        }
                     }
                  </script>
            
               </div>
            
            </aside>
            <!-- end: sidebar -->