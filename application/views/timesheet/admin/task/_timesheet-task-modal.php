<?php
/**
 * User form for adding/editing timesheet task details
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?> 
<div id="taskmodal" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
            <section class="panel">
               <header class="panel-heading">
                  <h2 class="panel-title" id="form-task-title"></h2>
               </header>  
                 
         <div class="panel-body">       
            <form id="form-task" class="form-horizontal form-modal form-task" >
            <?php echo form_input(['name' => 'timesheetTaskId', 'id' => 'timesheetTaskId', 'class'=> 'form-control', 'type' => 'hidden']); ?>
            <?php echo form_input(['name' => 'timesheetTaskGroupId', 'id' => 'task-group-id', 'class'=> 'form-control', 'type' => 'hidden']); ?>
               <?php echo form_input(['name' => 'displayOrder', 'id' => 'taskDisplayOrder', 'class'=> 'form-control', 'type' => 'hidden']); ?> 
            <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( 'timesheet_adm_task_fm_task_name_lbl' ); ?><span class="required">*</span></label>
               <div class="col-sm-9">
                  <input type="text" name="taskName" id="taskName" maxlength="50" class="form-control" placeholder="<?php echo lang( 'timesheet_adm_task_fm_task_name_ph' ); ?>" required/>
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( 'timesheet_adm_task_fm_task_desc_lbl' ); ?></label>
               <div class="col-sm-9">
                  <textarea type="text" name="taskDescription" id="taskDescription" row="2" class="form-control" placeholder="<?php echo lang( 'timesheet_adm_task_fm_task_desc_ph' ); ?>"></textarea>
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( 'timesheet_adm_task_fm_task_color_lbl' ); ?>
               <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'timesheet_adm_task_fm_task_color_ttip' ); ?>"></i></label>
               <div class="col-sm-9">
                  <div class="input-group color" data-plugin-colorpicker>
                    <span class="input-group-addon" ><i class="color-z"></i></span>
                    <input type="text" name="color" id="color" class="form-control color-z color-input" placeholder="Use group colour"  value="" />
                  </div>
               </div>
            </div>
           <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( 'timesheet_adm_task_fm_task_time_taken_lbl' ); ?></label>
               <div class="col-sm-9">
                  <select class="form-control" id="timeTaken" name="timeTaken">
                       <?php for ( $i = 15; $i <= 120; $i +=15) { ?>
                       <option value="<?php echo $i; ?>" <?php echo ($i == 60) ? 'selected' : '';?> ><?php echo $i; ?> minutes</option>
                       <?php } ?>
                  </select>
                
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( 'timesheet_adm_task_fm_task_chargeable_lbl' ); ?></label>
               <div class="col-sm-9">
                  <div class="switch switch-sm switch-primary">
                     <!-- Hidden field value is used unless checkbox is checked -->
                     <input type="hidden" name="chargeable" id="chargeableHidden" value="0" />
                     <input type="checkbox" name="chargeable" id="chargeable" value="1" checked="checked" data-plugin-ios-switch />
                  </div>
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( 'timesheet_adm_task_fm_task_create_btn_lbl' ); ?></label>
               <div class="col-sm-9">
                  <div class="switch switch-sm switch-primary">
                     <!-- Hidden field value is used unless checkbox is checked -->
                     <input type="hidden" name="createButton" id="createButtonHidden" value="0" />
                     <input type="checkbox" name="createButton" id="createButton"  checked="checked" value="1" data-plugin-ios-switch />
                  </div>
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( 'timesheet_adm_task_fm_task_hide_rep_lbl' ); ?></label>
               <div class="col-sm-9">
                  <div class="switch switch-sm switch-primary">
                     <!-- Hidden field value is used unless checkbox is checked -->
                     <input type="hidden" name="hiddenReports" id="hiddenReportsHidden" value="0" />
                     <input type="checkbox" name="hiddenReports" id="hiddenReports" checked="checked" value="1" data-plugin-ios-switch  />
                  </div>
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( 'timesheet_adm_task_fm_task_active_lbl' ); ?></label>
               <div class="col-sm-9">
                  <div class="switch switch-sm switch-primary">
                     <!-- Hidden field value is used unless checkbox is checked -->
                     <input type="hidden" name="active" id="activeHidden" value="0" />
                     <input type="checkbox" name="active" id="active" value="1" checked="checked" data-plugin-ios-switch  />
                  </div>
               </div>
            </div>
           
         </div>
        <footer class="panel-footer">
               <div class="row"> 
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary" id="submit-task" data-form="add"><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
      </section>
          </form>
   </div>

