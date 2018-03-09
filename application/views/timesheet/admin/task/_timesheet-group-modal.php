<?php
/**
 * User form for adding/editing timesheet Group details
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?> 
<div id="groupmodal" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
            <section class="panel">
               <header class="panel-heading">
                  <h2 class="panel-title" id="form-title"><?php echo lang( 'timesheet_adm_group_add_mdl_title' ); ?></h2>
               </header>  
                  
         <div class="panel-body">       
            <form id="form-taskgroup" class="form-horizontal form-modal form-taskgroup" >
          <?php echo form_input(['name' => 'timesheetTaskGroupId', 'id' => 'timesheetTaskGroupId', 'class'=> 'form-control', 'type' => 'hidden']); ?> 
          <?php echo form_input(['name' => 'displayOrder', 'id' => 'groupDisplayOrder', 'class'=> 'form-control', 'type' => 'hidden']); ?> 
           <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( 'timesheet_adm_group_fm_group_name_lbl' ); ?><span class="required">*</span></label>
               <div class="col-sm-9">
                  <input type="text" name="groupName" id="groupName" maxlength="50" class="form-control" placeholder="<?php echo lang( 'timesheet_adm_group_fm_group_name_ph' ); ?>" required/>
               </div>
            </div>
            <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( 'timesheet_adm_group_fm_group_color_lbl' ); ?><span class="required">*</span>
               <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"  title="" data-original-title="<?php echo lang( 'timesheet_adm_group_fm_group_color_ttip' ); ?>"></i></label>
               <div class="col-sm-9">
                  <div class="input-group color" data-plugin-colorpicker>
                    <span class="input-group-addon" ><i class="color-z"></i></span>
                    <input type="text" name="groupColor" id="groupColor" class="form-control color-z color-input" value="#ccc" required />

                  </div>
               </div>
            </div>
                        
         </div>
        <footer class="panel-footer">
               <div class="row">
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary" id="submit-taskgroup" data-form="add"><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
      </section>
          </form>
   </div>

