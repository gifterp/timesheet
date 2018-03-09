<?php
/**
 * User form for adding/editing user details
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?> 

      <div id="modalForm" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
         <section class="panel">
            <header class="panel-heading">
               <h2 class="panel-title" id="form-title"><?php echo lang("user_add_fm_title");?></h2>
            </header> 

            <div class="panel-body">
               <form id="form-user" class="form-horizontal form-modal form-user">
                  <?php echo form_input(['name' => 'userId','id' => 'userId','class'=> 'form-control','type' => 'hidden']);?>
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang("user_fm_fname_lbl");?><span class="required">*</span></label>
                     <div class="col-sm-9">
                        <input type="text" name="firstName" id="firstName" class="form-control" placeholder="<?php echo lang("user_fm_fname_ph");?>" data-msg-required="<?php echo lang("user_fm_fname_msg");?>" maxlength="50" required />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang("user_fm_sname_lbl");?><span class="required">*</span></label>
                     <div class="col-sm-9">
                        <input type="text" name="surname" id="surname" class="form-control" placeholder="<?php echo lang("user_fm_sname_ph");?>" data-msg-required="<?php echo lang("user_fm_sname_msg");?>" maxlength="50" required />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang("user_fm_initial_lbl");?><span class="required">*</span> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang("user_fm_initial_ttip");?>"></i></label>
                     <div class="col-sm-9">
                        <input type="text" name="initials" id="initials" class="form-control" maxlength="4" placeholder="<?php echo lang("user_fm_initial_ph");?>" data-msg-required="<?php echo lang("user_fm_initial_msg");?>" required />
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang("user_fm_email_lbl");?><span class="required">*</span></label>
                     <div class="col-sm-9">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="fa fa-fw fa-envelope"></i>
                           </span>
                           <input type="email" name="email" id="email" class="form-control"  maxlength="100" placeholder="<?php echo lang("user_fm_email_ph");?>" data-msg-required="<?php echo lang("user_fm_email_msg");?>" required />
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang("user_fm_username_lbl");?><span class="required">*</span></label>
                     <div class="col-sm-9">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="fa fa-fw fa-user"></i>
                           </span>
                           <input type="text" name="username" id="username" maxlength="30" class="form-control" placeholder="<?php echo lang("user_fm_username_ph");?>" data-msg-required="<?php echo lang("user_fm_username_msg");?>" required />
                        </div>
                     </div>
                  </div>
                  <div class="form-group disable-on-edit">
                     <label class="col-sm-3 control-label"><?php echo lang("user_fm_password_lbl");?><span class="required">*</span></label>
                     <div class="col-sm-9">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="fa fa-fw fa-key"></i>
                           </span>
                           <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo lang("user_fm_password_ph");?>" required />
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang("user_fm_charge_rate_lbl");?><span class="required">*</span> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang("user_fm_charge_rate_ttip");?>"></i></label>
                     <div class="col-sm-9">
                       <div class="input-group">
                           <span class="input-group-addon">
                              <i class="fa fa-fw fa-dollar"></i>
                           </span>
                           <input class="form-control numeric" type="text" name="chargeRate" id="chargeRate" placeholder="<?php echo lang("user_fm_charge_rate_ph");?>" data-msg-required="<?php echo lang("user_fm_charge_rate_msg");?>" required />
                       </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang("user_fm_access_level_lbl");?></label>
                     <div class="col-sm-9">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="fa fa-fw fa-lock"></i>
                           </span><select class="form-control" name="accessLevel" id="accessLevel">
                           <option value="0">User</option>
                           <option value="50">Admin</option>
                           <?php if($_SESSION['accessLevel'] == 99) {?>
                           <option value="99">Super Admin</option>
                           <?php }?>
                        </select>
                     </div></div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang("user_fm_active_lbl");?><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang("user_fm_active_ttip");?>"></i></label>
                     <div class="col-sm-9">
                        <div class="switch switch-sm switch-primary">
                           <!-- Hidden field value is used unless checkbox is checked -->
                           <input type="hidden" name="active" id="activeHidden" value="0" />   <?php // See this Gifter? The hidden input with the same name is passed when the checkbox is unchecked. Delete this commetn once you understand how this works ?>
                           <input type="checkbox" name="active" id="active" value="1" data-plugin-ios-switch checked="checked" />
                        </div>
                     </div>
                  </div>
               </form>

            </div>
            <footer class="panel-footer">
               <div class="row">
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary modal-confirm-submit" id="modal-confirm-button" data-form-id="#form-user" data-ajax-url="/user/save" data-ajax-method="updateFromFormData" data-ajax-success="<?php echo lang("user_notif_add_success");?>" data-ajax-error="<?php echo lang("user_notif_add_error");?>"><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
         </section>
      </div>


