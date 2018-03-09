<?php
/**
 * Work in progress report - index page
 *
 * Displays the WIP report and provides users with forms to
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>
 
               <!-- start: page -->
               <div class="row">

                  <!-- Start: Report contents -->
                  <div class="col-md-12 col-lg-8" id="wip-report">

<?php print_wip_report( $reportEntries, $_POST['sortOrder'], @$_POST['filter'] ); ?>

                  </div>
                  <!-- End: Report contents -->
                  <!-- Start: right hand form and job list -->
                  <div class="col-md-12 col-lg-4">

                     <section class="panel panel-featured panel-featured-primary">
                        <div class="panel-body button-panel-body">
                           <div class="row">
                              <div class="col-md-12">
                                 <?php echo create_wip_print_button( lang( 'report_btn_print_report' ), @$_POST['startDate'], @$_POST['endDate'], @$_POST['sortOrder'], @$_POST['filter'], @$_POST['jobSelect'], @$_POST['userSelect'] ) ?>
                                 <button class="btn btn-primary ib click-all-headers"><?php echo lang( 'report_btn_collapse_expand' ); ?></button>
                                 <button class="btn btn-primary ib show-hide-entries"><?php echo lang( 'report_btn_hide_entries' ); ?></button>
                              </div>
                           </div>
                        </div>
                     </section>

                     <section class="panel panel-featured">
                        <header class="panel-heading" data-panel-toggle>
                           <h2 class="panel-title"><?php echo lang( 'report_wip_fm_sdbr_title' ); ?></h2>
                           <p class="panel-subtitle"><?php echo lang( 'report_wip_fm_sdbr_subtitle' ); ?></p>
                        </header>
                        <div class="panel-body">
                           <div class="row">

<?php include $_SERVER['DOCUMENT_ROOT'] . ROOT_RELATIVE_PATH . 'assets-system/php/reports/wip-form.php'; ?>

                           </div>
                        </div>
                     </section>
                  </div>
                  <!-- End: right hand form and job list -->

               </div>
               <!-- end: page -->

            </section><?php // tag opened in _templates/page-header.php ?>
            
            <!-- end: section role="main" class="content-body" -->
