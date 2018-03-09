<?php
/**
 * Update password form editing user password details
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>

      <div id="modalFormPassword" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
         <section class="panel">
            <header class="panel-heading">
               <h2 class="panel-title" id="form-title"><?php echo lang("user_password_fm_title");?></h2>
            </header>

            <div class="panel-body">
               <form id="form-password" class="form-horizontal form-modal form-user">
                  <?php echo form_input(['name' => 'userId','id' => 'passwordId','class'=> 'form-control','type' => 'hidden']);?>
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang("user_pm_password_lbl");?><span class="required">*</span></label>
                     <div class="col-sm-9">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="fa fa-fw fa-key"></i>
                           </span>
                           <input type="password" name="password" id="password" class="form-control" data-msg-required="<?php echo lang("user_pm_password_msg");?>" placeholder="<?php echo lang("user_pm_password_ph");?>" />
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang("user_pm_confirm_password_lbl");?><span class="required">*</span></label>
                     <div class="col-sm-9">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="fa fa-fw fa-key"></i>
                           </span>
                           <input type="password" name="confirm_password" id="confirm_password" class="form-control" data-msg-required="<?php echo lang("user_pm_confirm_password_msg");?>" data-msg-equalTo="Passwords do not match" placeholder="<?php echo lang("user_pm_confirm_password_ph");?>" />
                        </div>
                     </div>
                  </div>
                  
               </form>

            </div>
            <footer class="panel-footer">
               <div class="row">
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary modal-confirm-submit" id="modal-confirm-button-password" data-form-id="#form-password" data-ajax-url="<?php echo ROOT_RELATIVE_PATH; ?>user/save" data-ajax-method="updateFromFormData" data-ajax-success="<?php echo lang("user_notif_password_success"); ?>" data-ajax-error="<?php echo lang("user_notif_password_error"); ?>"><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
         </section>
      </div>


