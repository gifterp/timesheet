<?php
/**
 * Job form for adding of cadastral  details
 *
 *  
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?> 
 
      <div id="add-cadastral" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide cadestral-add-form">
         <section class="panel" >
            <header class="panel-heading">
               <h2 class="panel-title" id="form-title"></h2>
            </header>
  
            <div class="panel-body">
               <form id="form-cadastral" class="form-horizontal form-modal form-cadastral" >
               <?php echo form_input(['name' => 'cadastralId', 'value'=> $jobDetails->cadastralId, 'id' => 'cadastralId', 'class'=> 'form-control', 'type' => 'hidden']); ?>
               <?php echo form_input(['name' => 'invoiceId', 'value' => $invoiceId, 'class'=> 'form-control', 'type' => 'hidden']); ?>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-4 control-label"><?php echo lang( "job_cadastral_fm_council_lbl" ); ?><span class="required">*</span></label>
                        <div class="col-sm-8">
                           <select class="form-control  mb-md" id="councilId" name="councilId">
                              <?php foreach ( $council as $councilRow ) { ?>
                                 <option value="<?php echo $councilRow->councilId; ?>" ><?php echo $councilRow->name; ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-4 control-label"><?php echo lang( "job_cadastral_fm_ps_no_lbl" ); ?></label>
                        <div class="col-sm-8">
                           <input type="text" name="psNo" class="form-control" maxlength="11" id="psNo" placeholder="<?php echo lang( "job_cadastral_fm_ps_no_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_plan_category_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="planningCategory" class="form-control" maxlength="50" id="planningCategory" placeholder="<?php echo lang( "job_cadastral_fm_plan_category_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-12">
                     <strong><?php echo lang( "job_cadastral_fm_hdr_parish_particular" ); ?></strong>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_parish_name_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="parishName" class="form-control" maxlength="50" id="parishName" placeholder="<?php echo lang( "job_cadastral_fm_parish_name_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_crown_allotment_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="crownAllotment" class="form-control" maxlength="50" id="crownAllotment" placeholder="<?php echo lang( "job_cadastral_fm_crown_allotment_ph" ); ?>"  />
                        </div>
                     </div>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_section_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="section" class="form-control" maxlength="50" id="section" placeholder="<?php echo lang( "job_cadastral_fm_section_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_township_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="township" class="form-control" maxlength="50" id="township" placeholder="<?php echo lang( "job_cadastral_fm_township_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-12">
                     <strong><?php echo lang( "job_cadastral_fm_hdr_parent_title" ); ?></strong>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_lot_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="lot" class="form-control mb-md" maxlength="50" id="lot" placeholder="<?php echo lang( "job_cadastral_fm_lot_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_plan_no_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="planNo" class="form-control mb-md" maxlength="10" id="planNo" placeholder="<?php echo lang( "job_cadastral_fm_plan_no_ph" ); ?>"  />
                        </div>
                     </div>
                  </div>
               </div> 
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_vol1_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="vol1" class="form-control mb-md" maxlength="6" id="vol1" placeholder="<?php echo lang( "job_cadastral_fm_vol1_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_vol2_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="fol1" class="form-control mb-md" maxlength="3" id="fol1" placeholder="<?php echo lang( "job_cadastral_fm_vol2_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_vol3_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="vol2" class="form-control mb-md" maxlength="6" id="vol2" placeholder="<?php echo lang( "job_cadastral_fm_vol3_ph" ); ?>"  />
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_fol1_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="fol2" class="form-control mb-md" maxlength="3" id="fol2" placeholder="<?php echo lang( "job_cadastral_fm_fol1_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_fol2_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="vol3" class="form-control" maxlength="6" id="vol3" placeholder="<?php echo lang( "job_cadastral_fm_fol2_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo lang( "job_cadastral_fm_fol3_lbl" ); ?></label>
                        <div class="col-sm-9">
                           <input type="text" name="fol3" class="form-control" maxlength="3" id="fol3" placeholder="<?php echo lang( "job_cadastral_fm_fol3_ph" ); ?>" />
                        </div>
                     </div>
                  </div>
               </div>
              
        
               </form>

            </div>
            <footer class="panel-footer">
               <div class="row">
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary " id="submit-cadastral" data-form="add" ><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
         </section>
      </div>


