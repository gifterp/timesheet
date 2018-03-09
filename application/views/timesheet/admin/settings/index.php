<?php
/**
 * Administration - Timesheet Settings Index Page
 *
 * Gives administrators an easy way to change the settings for the timesheet diary
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
                  <div class="col-md-8">
                     <div class="row">
                        <div class="col-md-12">
                              <section class="panel panel-featured">
                                 <header class="panel-heading" data-panel-toggle>
                                    <h2 class="panel-title"><?php echo lang( 'timesheet_adm_set_display_title' ); ?></h2>
                                    <p class="panel-subtitle"><?php echo lang( 'timesheet_adm_set_display_subtitle' ); ?></p>
                                 </header>
                                 <div class="panel-body">
                                    <div class="row">

                                       <form class="form-horizontal form-bordered" id="form-appearance">

                                          <div class="form-group">
                                             <div class="col-md-6">
                                                <label class="col-lg-4 control-label" for="timeFormat"><?php echo lang( 'timesheet_adm_set_time_format' ); ?></label>
                                                <div class="col-lg-8">
                                                   <div class="input-group mb-sm">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-clock-o"></i>
                                                      </span>
                                                      <select name="timeFormat" id="timeFormat" class="form-control">
                                                         <option value="h(:mm)a">12 hour (1:30pm)</option>
                                                         <option value="H:mm">24 hour (13:30)</option>
                                                      </select>
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_time_format_help' ); ?>"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6">
                                                <label class="col-lg-4 control-label" for="businessDaysOfWeek"><?php echo lang( 'timesheet_adm_set_days_week' ); ?></label>
                                                <div class="col-lg-8">
                                                   <div class="input-group btn-group mb-sm multi-select-group">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-calendar"></i>
                                                      </span>
                                                      <select name="businessDaysOfWeek" id="businessDaysOfWeek" class="form-control" multiple="multiple" data-plugin-options='{ "maxHeight": 200 }' data-plugin-multiselect>
                                                         <option value="0">Sunday</option>
                                                         <option value="1">Monday</option>
                                                         <option value="2">Tuesday</option>
                                                         <option value="3">Wednesday</option>
                                                         <option value="4">Thursday</option>
                                                         <option value="5">Friday</option>
                                                         <option value="6">Saturday</option>
                                                      </select>
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_days_week_help' ); ?>"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>

                                          <div class="form-group">
                                             <div class="col-md-6">
                                                <label class="col-lg-4 control-label" for="businessHoursStart"><?php echo lang( 'timesheet_adm_set_start_time' ); ?></label>
                                                <div class="col-lg-8">
                                                   <div class="input-group mb-sm">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-clock-o"></i>
                                                      </span>
                                                      <input type="text" name="businessHoursStart" id="businessHoursStart" class="form-control" value="" data-plugin-timepicker onfocus="this.blur();" readonly />
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_start_time_help' ); ?>"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6">
                                                <label class="col-lg-4 control-label" for="businessHoursEnd"><?php echo lang( 'timesheet_adm_set_finish_time' ); ?></label>
                                                <div class="col-lg-8">
                                                   <div class="input-group mb-sm">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-clock-o"></i>
                                                      </span>
                                                      <input type="text" name="businessHoursEnd" id="businessHoursEnd" class="form-control" value=""  data-plugin-timepicker onfocus="this.blur();" readonly />
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_finish_time_help' ); ?>"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>

                                          <div class="form-group">
                                             <div class="col-md-6">
                                                <label class="col-lg-4 control-label" for="slotDuration"><?php echo lang( 'timesheet_adm_set_slot_duration' ); ?></label>
                                                <div class="col-lg-8">
                                                   <div class="input-group mb-sm">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-tasks"></i>
                                                      </span>
                                                      <select name="slotDuration" id="slotDuration" class="form-control">
                                                         <option value="00:15:00">15 minutes</option>
                                                         <option value="00:30:00">30 minutes</option>
                                                         <option value="00:45:00">45 minutes</option>
                                                         <option value="00:60:00">1 hour</option>
                                                      </select>
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_slot_duration_help' ); ?>"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6">
                                                <label class="col-lg-4 control-label" for="slotLabelInterval"><?php echo lang( 'timesheet_adm_set_label_interval' ); ?></label>
                                                <div class="col-lg-8">
                                                   <div class="input-group mb-sm">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-arrows-v"></i>
                                                      </span>
                                                      <select name="slotLabelInterval" id="slotLabelInterval" class="form-control">
                                                         <option value="00:15:00">15 minutes</option>
                                                         <option value="00:30:00">30 minutes</option>
                                                         <option value="00:45:00">45 minutes</option>
                                                         <option value="00:60:00">1 hour</option>
                                                      </select>
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_label_interval_help' ); ?>"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>

                                          <div class="form-group">
                                             <div class="col-md-6">
                                                <label class="col-lg-4 control-label" for="scrollTime"><?php echo lang( 'timesheet_adm_set_scroll_time' ); ?></label>
                                                <div class="col-lg-8">
                                                   <div class="input-group mb-sm">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-level-down"></i>
                                                      </span>
                                                      <input type="text" name="scrollTime" id="scrollTime" class="form-control" value="" data-plugin-timepicker onfocus="this.blur();" readonly />
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_scroll_time_help' ); ?>"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6">
                                                <label class="col-lg-4 control-label" for="defaultView"><?php echo lang( 'timesheet_adm_set_default_view' ); ?></label>
                                                <div class="col-lg-8">
                                                   <div class="input-group mb-sm">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-columns"></i>
                                                      </span>
                                                      <select name="defaultView" id="defaultView" class="form-control">
                                                         <option value="agendaDay">Day view</option>
                                                         <option value="agendaWeek">Week view</option>
                                                      </select>
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_default_view_help' ); ?>"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>

                                          <div class="form-group">
                                             <div class="col-md-6">
                                                <label class="col-lg-4 control-label" for="dayViewColumnFormat"><?php echo lang( 'timesheet_adm_set_day_date_format' ); ?></label>
                                                <div class="col-lg-8">
                                                   <div class="input-group mb-sm">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-calendar-o"></i>
                                                      </span>
                                                      <select name="dayViewColumnFormat" id="dayViewColumnFormat" class="form-control">
                                                         <option value="ddd D/MM"><?php echo date( "D j/n" ); ?></option>
                                                         <option value="dddd D/MM"><?php echo date( "l j/n" ); ?></option>
                                                         <option value="dddd D MMM"><?php echo date( "l j M" ); ?></option>
                                                         <option value="dddd D MMMM"><?php echo date( "l j F" ); ?></option>
                                                         <option value="dddd Do MMM"><?php echo date( "l jS M" ); ?></option>
                                                         <option value="dddd Do MMMM"><?php echo date( "l jS F" ); ?></option>
                                                      </select>
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_day_date_format_help' ); ?>"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6">
                                                <label class="col-lg-4 control-label" for="weekViewColumnFormat"><?php echo lang( 'timesheet_adm_set_week_date_format' ); ?></label>
                                                <div class="col-lg-8">
                                                   <div class="input-group mb-sm">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-calendar-o"></i>
                                                      </span>
                                                      <select name="weekViewColumnFormat" id="weekViewColumnFormat" class="form-control">
                                                         <option value="ddd D/MM"><?php echo date( "D j/n" ); ?></option>
                                                         <option value="ddd D MMM"><?php echo date( "D j M" ); ?></option>
                                                         <option value="ddd Do MMM"><?php echo date( "D jS M" ); ?></option>
                                                         <option value="dddd D/MM"><?php echo date( "l j/n" ); ?></option>
                                                         <option value="dddd D MMM"><?php echo date( "l j M" ); ?></option>
                                                         <option value="dddd Do MMM"><?php echo date( "l jS M" ); ?></option>
                                                      </select>
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_week_date_format_help' ); ?>"></i>
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>

                                       </form>

                                    </div>
                                 </div>
                              </section>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="row">
                        <div class="col-md-12">
                           <section class="panel panel-featured">
                              <header class="panel-heading" data-panel-toggle>
                                 <h2 class="panel-title"><?php echo lang( 'timesheet_adm_set_other_title' ); ?></h2>
                              </header>
                              <div class="panel-body">
                                 <div class="row">

                                    <form class="form-horizontal form-bordered" id="form-other">

                                       <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="col-lg-4 control-label" for="multiDaySelectDisabled"><?php echo lang( 'timesheet_adm_set_multi_day' ); ?><i class="fa fa-question-circle ml-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_set_multi_day_help' ); ?>"></i></label>
                                             <div class="col-lg-8">

                                                <div class="switch switch-sm switch-primary">
                                                   <input type="checkbox" name="multiDaySelectDisabled" id="multiDaySelectDisabled" data-plugin-ios-switch checked />
                                                </div>
                                             </div>
                                          </div>
                                       </div>

                                       <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="col-lg-4 control-label" for=""><?php echo lang( 'timesheet_adm_set_reset' ); ?></label>
                                             <div class="col-lg-8">
                                                <button class="btn btn-danger" data-toggle="confirm-reset"><?php echo lang( 'timesheet_adm_set_reset_button' ); ?></button>
                                             </div>
                                          </div>
                                       </div>

                                    </form>

                                 </div>
                              </div>
                           </section>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- end: page -->
            </section><?php // tag opened in _templates/page-header.php ?>
            
            <!-- end: section role="main" class="content-body" -->
