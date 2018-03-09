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

                     <section class="panel panel-featured">
                        <header class="panel-heading" data-panel-toggle>
                           <h2 class="panel-title"><?php echo lang( 'report_minv_mock_invoice_pnl_title' ); ?></h2>
                        </header>
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-12" id="container-minv-details">
                                 <!-- Table displaying invoice details loaded here -->
                              </div>

                           </div>
                        </div>
                     </section>

                     <section class="panel panel-featured hidden-ready">
                        <header class="panel-heading">
                           <h2 class="panel-title"><?php echo lang( 'report_minv_add_invoice_pnl_title' ); ?></h2>
                        </header>
                        <div class="panel-body">
                           <div class="row">
                              <form class="form-horizontal form-bordered" id="form-minv-details">
                                 <div class="col-md-12" id="container-form-minv-details">
                                    <!-- Form to add invoice details loaded here -->
                                 </div>
                              </form>
                           </div>
                        </div>
                        <div class="panel-footer text-right">
                           <button class="btn btn-primary" id="add-invoice-row">Add row</button>
                        </div>
                     </section>

                     <div id="container-minv-entries-panel">
<?php print_minv_report( $reportEntries, $invoiceDetails, @$_GET['id'] ); ?>
                     </div>

                  </div>
                  <!-- End: Report contents -->
                  <!-- Start: right hand form and job list -->
                  <div class="col-md-12 col-lg-4">

                     <section class="panel panel-featured">
                        <header class="panel-heading" data-panel-toggle>
                           <h2 class="panel-title"><?php echo lang( 'report_minv_ctrl_pnl_title' ); ?></h2>
                        </header>
                        <div class="panel-body">
                           <div class="row">

                              <form class="form-horizontal form-bordered" id="form-minv">

                                 <div class="form-group">
                                    <div class="col-md-12">
                                       <label class="col-lg-4 control-label" for="ready"><?php echo lang( 'report_minv_fm_ready_lbl' ); ?><i class="fa fa-question-circle ml-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'report_minv_fm_ready_ttip' ); ?>"></i></label>
                                       <div class="col-lg-8">

                                          <div class="switch switch-sm switch-primary">
                                             <div class="switch switch-sm switch-primary">
                                                <!-- Hidden field value is used unless checkbox is checked -->
                                                <input type="hidden" name="ready" id="readyHidden" value="0" />
                                                <input type="checkbox" name="ready" id="ready" value="1" data-plugin-ios-switch />
                                             </div>
                                             <span class="label label-warning switch-label" id="ready-label"><?php echo lang( 'system_no' ); ?></span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group hidden" id="complete-section">
                                    <div class="col-md-12">
                                       <label class="col-lg-4 control-label" for="complete"><?php echo lang( 'report_minv_fm_complete_lbl' ); ?><i class="fa fa-question-circle ml-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'report_minv_fm_complete_ttip' ); ?>"></i></label>
                                       <div class="col-lg-8">
                                          <div class="switch switch-sm switch-primary">
                                             <div class="switch switch-sm switch-primary">
                                                <!-- Hidden field value is used unless checkbox is checked -->
                                                <input type="hidden" name="complete" id="completeHidden" value="0" />
                                                <input type="checkbox" name="complete" id="complete" value="1" data-plugin-ios-switch />
                                             </div>
                                             <span class="label label-warning switch-label" id="complete-label"><?php echo lang( 'system_no' ); ?></span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group hidden-ready">
                                    <div class="col-md-12">
                                       <label class="col-lg-4 control-label" for="sortOrder"><?php echo lang( 'report_wip_fm_lbl_sort' ); ?></label>
                                       <div class="col-lg-8">
                                          <div class="input-group">
                                             <span class="input-group-addon">
                                                <i class="fa fa-fw fa-sort-amount-asc"></i>
                                             </span>
<?php echo form_dropdown( 'sortOrder', lang( 'report_wip_fm_sort_options' ), $invoiceDetails->sortOrder, 'id="sort-order-control" class="form-control"' ); ?>
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="col-md-12">
                                       <label class="col-lg-4 control-label mt-xs" for=""><?php echo lang( 'report_minv_fm_action_lbl' ); ?></label>
                                       <div class="col-lg-8">
                                          <button class="btn btn-danger mt-xs hidden-complete" data-toggle="delete-invoice"><?php echo lang( 'report_minv_fm_delete_btn' ); ?></button>
                                          <a href="javascript:window.close();" class="btn btn-primary mt-xs"><?php echo lang( 'report_minv_fm_close_btn' ); ?></a>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="col-md-12">
                                       <label class="col-lg-4 control-label mt-xs" for=""><?php echo lang( 'report_minv_fm_action_lbl' ); ?></label>
                                       <div class="col-lg-8">
                                          <?php echo create_wip_print_button( lang( 'report_btn_print_report' ), @$_POST['startDate'], @$_POST['endDate'], @$_POST['sortOrder'], @$_POST['filter'], @$_POST['jobSelect'], @$_POST['userSelect'] ) ?>
                                          <a href="#" class="btn btn-primary mt-xs">Print mock invoice</a>
                                       </div>
                                    </div>
                                 </div>

                              </form>

                           </div>
                        </div>
                     </section>
                  </div>
                  <!-- End: right hand form and job list -->

               </div>
               <!-- end: page -->

            </section><?php // tag opened in _templates/page-header.php ?>
            
            <!-- end: section role="main" class="content-body" -->
