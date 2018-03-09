<?php
/**
 * User form for adding/editing contact details
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?> 
<div id="modalForm" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
            <section class="panel">
               <header class="panel-heading">
                  <h2 class="panel-title" id="form-title"><?php echo lang( "contact_add_fm_title" ); ?></h2>
               </header> 
                
         <div class="panel-body">       
            <form id="form-contact" class="form-horizontal form-modal form-contact" >
          <?php echo form_input(['name' => 'contactPersonId', 'id' => 'contactPersonId', 'class'=> 'form-control', 'type' => 'hidden']); ?> 
           <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "contact_fm_name_lbl" ); ?><span class="required">*</span></label>
                                 <div class="col-sm-9">
                                    <input type="text" name="name" id="name" maxlength="50" class="form-control" placeholder="<?php echo lang( "contact_fm_name_ph" ); ?>" required/>
                                 </div>
                              </div>
                               <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "contact_fm_company_lbl" ); ?><span class="required">*</span></label>
                                 <div class="col-sm-9">
                                    <input type="text" name="company" class="form-control" maxlength="55" id="company" placeholder="<?php echo lang( "contact_fm_company_ph" ); ?>" required/>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "contact_fm_address1_lbl" ); ?><span class="required">*</span></label>
                                 <div class="col-sm-9">
                                    <input type="text" name="address1" id="address1" class="form-control"  maxlength="50" placeholder="<?php echo lang( "contact_fm_address1_ph" ); ?>" required="required" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "contact_fm_address2_lbl" ); ?></label>
                                 <div class="col-sm-9">
                                     <input type="text" name="address2" id="address2" class="form-control"  maxlength="50" placeholder="<?php echo lang( "contact_fm_address2_ph" ); ?>"  />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang( "contact_fm_phone_lbl" ); ?></label>
                                 <div class="col-md-6 control-label">
                                    <div class="input-group">
                                       <span class="input-group-addon">
                                          <i class="fa fa-phone"></i>
                                       </span>
                                       <input id="phone" name="phone" class="form-control numeric" placeholder="<?php echo lang( "contact_fm_phone_ph" ); ?>" data-msg-required="Please put valid phone number" maxlength="15" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "contact_fm_fax_lbl" ); ?></label>
                                 <div class="col-sm-9">
                                    <input type="text" name="fax" id="fax" class="form-control numeric" placeholder="<?php echo lang( "contact_fm_fax_ph" ); ?>" data-msg-required="Please put valid fax number" maxlength="15"/>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang( "contact_fm_mobile_lbl" ); ?></label>
                                 <div class="col-md-6 control-label">
                                    <div class="input-group">
                                       <span class="input-group-addon">
                                          <i class="fa fa-phone"></i>
                                       </span>
                                       <input id="mobile" name="mobile" class="form-control numeric" placeholder="<?php echo lang( "contact_fm_mobile_ph" ); ?>" data-msg-required="Please put valid mobile number" maxlength="15" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "contact_fm_email_lbl" ); ?><span class="required">*</span></label>
                                 <div class="col-sm-9">
                                    <div class="input-group">
                                       <span class="input-group-addon">
                                          <i class="fa fa-envelope"></i>
                                       </span>
                                       <input type="email" id="email" name="email" maxlength="90" class="form-control" placeholder="<?php echo lang( "contact_fm_email_ph" ); ?>" required/>
                                    </div>
                                 </div>
                                 <div class="col-sm-9">

                                 </div>
                              </div> 
         
         </div>
        <footer class="panel-footer">
               <div class="row">
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary modal-confirm-submit" id="modal-confirm-button" data-form-id="#form-contact" data-ajax-url="/contact/create" data-ajax-method="updateFromFormData" data-ajax-success="<?php echo lang( "contact_notif_add_success" ); ?>" data-ajax-error="<?php echo lang( "contact_notif_add_error" ); ?>"><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
      </section>
          </form>
   </div>

