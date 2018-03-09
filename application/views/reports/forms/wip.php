<?php
/**
 * Report Form - Work in progress report
 *
 * Allows administrators to generate a work in progress report
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
                  <div class="col-md-12 col-lg-8">

                     <section class="panel panel-featured">
                        <header class="panel-heading" data-panel-toggle>
                           <h2 class="panel-title"><?php echo lang( 'report_wip_fm_pnl_title' ); ?></h2>
                           <p class="panel-subtitle"><?php echo lang( 'report_wip_fm_pnl_subtitle' ); ?></p>
                        </header>
                        <div class="panel-body">
                           <div class="row">

<?php include $_SERVER['DOCUMENT_ROOT'] . ROOT_RELATIVE_PATH . 'assets-system/php/reports/wip-form.php'; ?>

                           </div>
                        </div>
                     </section>

                  </div>
                  <div class="col-md-12 col-lg-4">

                     <section class="panel panel-featured">
                        <header class="panel-heading" data-panel-toggle>
                           <h2 class="panel-title"><?php echo lang( 'report_minv_fm_pnl_title' ); ?></h2>
                           <p class="panel-subtitle"><?php echo lang( 'report_minv_fm_pnl_subtitle' ); ?></p>
                        </header>
                        <div class="panel-body">
                           <div class="row">
                              <div class="col-md-12">
<?php list_ready_invoices(); ?> 
                              </div>
                           </div>
                        </div>
                     </section>
                  </div>

               </div>
               <!-- end: page -->
            </section><?php // tag opened in _templates/page-header.php ?>
            
            <!-- end: section role="main" class="content-body" -->
