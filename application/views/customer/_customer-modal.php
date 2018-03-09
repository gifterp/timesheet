<?php
/**
 * User form for adding/editing customer details
 *
 *  
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @author     Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?> 

      <div id="modalForm" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
         <section class="panel">
            <header class="panel-heading">
               <h2 class="panel-title" id="form-title"><?php echo lang( "customer_add_fm_title" ); ?></h2>
            </header> 
 
            <div class="panel-body">
               <form id="form-customer" class="form-horizontal form-modal form-customer" >
          <?php echo form_input(['name' => 'customerId', 'id' => 'customerId', 'class'=> 'form-control', 'type' => 'hidden']); ?> 
           <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "customer_fm_name_lbl" ); ?> <span class="required">*</span></label>
                                 <div class="col-sm-9">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="<?php echo lang( "customer_fm_name_ph" ); ?>" required maxlength="100" />
                                 </div> 
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label">
                                 <?php echo lang( "customer_fm_customer_type_lbl" ); ?>
                                 <span class="required" aria-required="true">*</span>
                                 </label>
                                 <div class="col-sm-9">
                                 <?php echo form_dropdown( 'customerType', lang( 'customer_fm_customer_opt' ), @$_POST['customerType'], 'class="form-control"' ); ?>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "customer_fm_address1_lbl" ); ?><span class="required">*</span></label>
                                 <div class="col-sm-9">
                                    <input type="text" name="address1" id="address1" class="form-control"  maxlength="50" placeholder="<?php echo lang( "customer_fm_address1_ph" ); ?>" required="required" />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "customer_fm_address2_lbl" ); ?></label>
                                 <div class="col-sm-9">
                                     <input type="text" name="address2" id="address2" class="form-control"  maxlength="50" placeholder="<?php echo lang( "customer_fm_address2_ph" ); ?>"  />
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "customer_fm_city_lbl" ); ?> </label>
                                 <div class="col-sm-9">
                                    <input type="text" name="city" id="city" class="form-control" maxlength="50" placeholder="<?php echo lang( "customer_fm_city_ph" ); ?>"/>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "customer_fm_region_lbl" ); ?> </label>
                                 <div class="col-sm-9">
                                    <input type="text" name="region" id="region" class="form-control" maxlength="20" placeholder="<?php echo lang( "customer_fm_region_ph" ); ?>"/>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "customer_fm_postcode_lbl" ); ?></label>
                                 <div class="col-sm-9">
                                    <input type="text" name="postCode" id="postCode" maxlength="6" class="form-control" placeholder="<?php echo lang( "customer_fm_postcode_ph" ); ?>"/>
                                 </div> 
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang( "customer_fm_phone_lbl" ); ?></label>
                                 <div class="col-md-6 control-label">
                                    <div class="input-group">
                                       <span class="input-group-addon">
                                          <i class="fa fa-phone"></i>
                                       </span>
                                       <input id="phone" name="phone" class="form-control numeric" placeholder="<?php echo lang( "customer_fm_phone_ph" ); ?>" data-msg-required="<?php echo lang( "customer_fm_phone_msg" ); ?>" maxlength="15">
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "customer_fm_fax_lbl" ); ?></label>
                                 <div class="col-sm-9">
                                    <input type="text" name="fax" id="fax" class="form-control numeric" placeholder="<?php echo lang( "customer_fm_fax_ph" ); ?>" data-msg-required="<?php echo lang( "customer_fm_fax_msg" ); ?>" maxlength="15"/>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang( "customer_fm_mobile_lbl" ); ?></label>
                                 <div class="col-md-6 control-label">
                                    <div class="input-group">
                                       <span class="input-group-addon">
                                          <i class="fa fa-phone"></i>
                                       </span>
                                       <input id="mobile" name="mobile" class="form-control numeric" placeholder="<?php echo lang( "customer_fm_mobile_ph" ); ?>" data-msg-required="<?php echo lang( "customer_fm_mobile_msg" ); ?>" maxlength="15" >
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "customer_fm_email_lbl" ); ?><span class="required">*</span></label>
                                 <div class="col-sm-9">
                                    <div class="input-group">
                                       <span class="input-group-addon">
                                          <i class="fa fa-envelope"></i>
                                       </span>
                                       <input type="email" id="email" maxlength="100" name="email" class="form-control" placeholder="<?php echo lang( "customer_fm_email_ph" ); ?>" required />
                                    </div>
                                 </div>
                                 <div class="col-sm-9">

                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-3 control-label"><?php echo lang( "customer_fm_active_lbl" ); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( "customer_fm_active_msg" ); ?>"></i></label>
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
                     <button class="btn btn-primary modal-confirm-submit" id="modal-confirm-button" data-form-id="#form-customer" data-ajax-url="/customer/create" data-ajax-method="updateFromFormData" data-ajax-success="<?php echo lang( "customer_notif_add_success" ); ?>" data-ajax-error="<?php echo lang( "customer_notif_add_error" ); ?>"><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
         </section>
      </div>


