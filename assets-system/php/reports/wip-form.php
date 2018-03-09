<?php
/**
 * Work in progress report form
 *
 * The form used to generate a work in progress report
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>
                              <form class="form-horizontal form-bordered" id="form-wip" action="<?php echo ROOT_RELATIVE_PATH; ?>reports/wip" method="POST">

                                 <div class="form-group">
                                    <div class="col-md-12">
                                       <label class="col-lg-2 control-label" for="startDate"><?php echo lang( 'report_wip_fm_lbl_date' ); ?></label>
                                       <div class="col-lg-10">
                                          <div class="input-group input-daterange">
                                             <!--<span class="input-group-addon">
                                                <?php echo lang( 'report_wip_fm_lbl_start' ); ?>
                                             </span>-->
                                             <input type="text" name="startDate" id="startDate" value="<?php echo @$_POST['startDate']; ?>" class="form-control datepicker white-bg" readonly>
                                             <span class="input-group-addon">
                                                <?php echo lang( 'report_wip_fm_lbl_date_range' ); ?>
                                             </span>
                                             <input type="text" name="endDate" id="endDate" value="<?php echo @$_POST['endDate']; ?>" class="form-control datepicker white-bg" readonly>
                                             <span class="input-group-btn text-primary">
                                                <button class="btn btn-default clear-dates" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo 'Clear dates and show everything'; ?>" type="button"><i class="fa fa-calendar-times-o text-primary"></i></button>

                                             </span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="col-md-12">
                                       <label class="col-lg-2 control-label" for="jobSelect"><?php echo lang( 'report_wip_fm_lbl_job' ); ?></label>
                                       <div class="col-lg-10">
                                          <div class="input-group btn-group">
                                             <span class="input-group-addon">
                                                <i class="fa fa-fw fa-folder-open-o"></i>
                                             </span>
<?php
echo form_dropdown( 'jobSelect[]', $jobArray, @$_POST['jobSelect'], 'class="form-control" multiple="multiple" data-plugin-multiselect data-plugin-options=\'{ "maxHeight": 200, "enableCaseInsensitiveFiltering": true, "nonSelectedText": "Show all" }\'' );
?>
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="col-md-12">
                                       <label class="col-lg-2 control-label" for="userSelect"><?php echo lang( 'report_wip_fm_lbl_user' ); ?></label>
                                       <div class="col-lg-10">
                                          <div class="input-group btn-group">
                                             <span class="input-group-addon">
                                                <i class="fa fa-fw fa-user"></i>
                                             </span>
<?php
echo form_dropdown( 'userSelect[]', $userArray, @$_POST['userSelect'], 'class="form-control" multiple="multiple" data-plugin-multiselect data-plugin-options=\'{ "maxHeight": 200, "enableCaseInsensitiveFiltering": true, "nonSelectedText": "Show all" }\'' );
?>
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="col-md-12">
                                       <label class="col-lg-2 control-label" for="sortOrder"><?php echo lang( 'report_wip_fm_lbl_sort' ); ?></label>
                                       <div class="col-lg-10">
                                          <div class="input-group">
                                             <span class="input-group-addon">
                                                <i class="fa fa-fw fa-sort-amount-asc"></i>
                                             </span>
<?php echo form_dropdown( 'sortOrder', lang( 'report_wip_fm_sort_options' ), @$_POST['sortOrder'], 'class="form-control"' ); ?>
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="col-md-12">
                                       <label class="col-lg-2 control-label" for="filter"><?php echo lang( 'report_wip_fm_lbl_filter' ); ?></label>
                                       <div class="col-lg-10">
                                          <div class="input-group btn-group">
                                             <span class="input-group-addon">
                                                <i class="fa fa-fw fa-filter"></i>
                                             </span>
<?php // Sets filter to current entries by default (Checks for POST)
echo form_dropdown( 'filter[]', lang( 'report_wip_fm_filter_options' ), ( $_SERVER['REQUEST_METHOD'] == 'POST' ? @$_POST['filter'] : 'current' ), 'class="form-control" multiple="multiple" data-plugin-multiselect data-plugin-options=\'{ "maxHeight": 200, "nonSelectedText": "Show all" }\'' ); ?>
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group">
                                    <div class="col-md-12">
                                       <div class="col-lg-10 col-lg-offset-2">
                                          <input type="submit" class="btn btn-primary" value="Run report again" />
                                       </div>
                                    </div>
                                 </div>

                              </form>
