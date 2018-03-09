<?php
/**
 * Job form for adding of Job  details
 *
 *  
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>  
      <!-- choose customer -->                 
      <div id="modalChooseJob" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
         <section class="panel">
            <header class="panel-heading">
               <h2 class="panel-title"><?php echo lang( 'job_mdl_choose_customer_title' ); ?></h2>
            </header>
            <div class="panel-body">
               <div class="modal-wrapper">
                  <div class="modal-text">
                        <select class="customer-chooser chosen-select" id="customer-chooser" name="customerId" data-placeholder="<?php echo lang( 'job_fm_ph_customer' ); ?>">
                        <option value=""></option>
                        <?php foreach ( $customer as $customerRow ) { ?>
                           <option value="<?php echo $customerRow->customerId; ?>" ><?php echo $customerRow->name; ?></option>
                        <?php } ?>
                     </select>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </div>
         </section>
      </div>

      <!-- edit choose customer -->   
      <div id="modalEditChooseJob" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
         <section class="panel">
            <header class="panel-heading">
               <h2 class="panel-title"><?php echo lang( 'job_mdl_update_customer_title' ); ?></h2>
            </header>
            <div class="panel-body">
               <div class="modal-wrapper">
                  <div class="modal-text">
                     <div class="form-group customer-form-details"></div>
                     <div class="form-group">
                        <select class="customer-chooser chosen-select-edit" id="customer-chooser-edit" name="customerId" data-placeholder="<?php echo lang( 'job_fm_ph_customer' ); ?>">
                           <option value=""></option>
                           <?php foreach ( $customer as $customerRow ) { ?>
                              <option value="<?php echo $customerRow->customerId; ?>" ><?php echo $customerRow->name; ?></option>
                           <?php } ?>
                        </select>
                     </div>
                        
                  </div>
                 
               </div>
            </div>
             <footer class="panel-footer">
               <div class="row">
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary customer-confirm"><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss customer-reset"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
         </section>
      </div>   
      <button href="#add-job" data-form="#form-job"  class="btn btn-primary modal-with-form modal-with-zoom-anim add-form hidden job-add" data-focus-element="#jobReferenceNo"><?php echo lang( "job_add_btn_text" ); ?></button>
      <!-- Job selection modal -->
      <div id="add-job" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide add-job-form">
         <section class="panel">
            <header class="panel-heading">
               <h2 class="panel-title" id="form-title"><?php echo lang("job_add_fm_title"); ?></h2>
            </header> 
 
            <div class="panel-body">
               <form id="form-job" class="form-horizontal form-modal form-job" >
               <?php echo form_input(['name' => 'jobId', 'id' => 'jobId', 'class'=> 'form-control', 'type' => 'hidden']); ?> 
               <?php echo form_input(['name' => 'customerId', 'id' => 'customerId', 'class'=> 'form-control', 'type' => 'hidden']); ?> 
               <?php echo form_input(['name' => 'parentJobId', 'id' => 'parentJobId', 'class'=> 'form-control', 'type' => 'hidden', 'value' => null ]); ?> 
               <?php echo form_input(['name' => 'isParent', 'id' => 'isParent', 'class'=> 'form-control', 'type' => 'hidden', 'value' => null ]); ?>
                  <div class="row">
                     <div class="col-md-6"> 
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_job_ref_lbl" ); ?><span class="required">*</span></label>
                           <div class="col-sm-8">
                              <input type="text" name="jobReferenceNo" class="form-control" maxlength="10" id="jobReferenceNo" placeholder="<?php echo lang( "job_fm_job_ref_ph" ); ?>" required />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_job_name_lbl" ); ?><span class="required">*</span></label>
                           <div class="col-sm-8">
                              <input type="text" name="jobName" class="form-control" maxlength="100" id="jobName" placeholder="<?php echo lang( "job_fm_job_name_ph" ); ?>" required/>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_job_manager_lbl" ); ?><span class="required">*</span></label>
                           <div class="col-sm-8">
                              <select class="form-control" id="userId" name="userId" required="required">
                                  <option value="" >  <?php echo lang( "job_fm_job_manager_ph" ); ?> </option>
                                 <?php foreach ( $user->result() as $userRow ) { ?>
                                    <option value="<?php echo $userRow->userId; ?>" > <?php 
                                    echo $userRow->name; ?> </option>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_job_dept_lbl" ); ?><span class="required">*</span></label>
                           <div class="col-sm-8">
                              <select class="form-control" id="departmentId" name="departmentId" required="required">
                                  <option value="" >  <?php echo lang( "job_fm_job_dept_ph" ); ?> </option>
                                 <?php foreach ( $department as $deptRow ) { ?>
                                    <option value="<?php echo $deptRow->departmentId; ?>" > <?php 
                                    echo $deptRow->name; ?> </option>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_job_type_lbl" ); ?></label>
                           <div class="col-sm-8">
                               <input type="text" name="jobType" class="form-control mb-md" maxlength="100" id="jobType" placeholder="<?php echo lang( "job_fm_job_type_ph" ); ?>" />
                              <select class="form-control" id="job-type" name="customerType" >
                                  <option value="" >  <?php echo lang( "job_fm_select_job_type_ph" ); ?>  </option>
                                 <?php foreach ( $jobType as $jobTypeRow ) { ?>
                                    <option value="<?php echo $jobTypeRow->name; ?>" ><?php 
                                    echo $jobTypeRow->name; ?></option>
                                 <?php } ?>
                              </select>
                             
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6"> 
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_street_no_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="streetNo" class="form-control"  id="streetNo" placeholder="<?php echo lang( "job_fm_street_no_ph" ); ?>" />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_street_name_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="streetName" class="form-control"  id="streetName" placeholder="<?php echo lang( "job_fm_street_name_ph" ); ?>"  />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_suburb_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="suburb" class="form-control"  id="suburb" placeholder="<?php echo lang( "job_fm_suburb_ph" ); ?>"  />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_post_code_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="postCode" class="form-control"  id="postCode" placeholder="<?php echo lang( "job_fm_post_code_ph" ); ?>"  />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_easting_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="easting" class="form-control"  id="easting" placeholder="<?php echo lang( "job_fm_easting_ph" ); ?>"  />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_northing_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="northing" class="form-control" maxlength="10" id="northing" placeholder="<?php echo lang( "job_fm_northing_ph" ); ?>" />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_zone_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="zone" class="form-control" maxlength="10" id="zone" placeholder="<?php echo lang( "job_fm_zone_ph" ); ?>"  />
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="well well-sm p-xs mb-sm mt-sm"><strong><?php echo lang( "job_fm_hdr_other_lbl" ); ?></strong></div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_budget_lbl" ); ?><span class="required">*</span></label>
                           <div class="col-sm-8">
                              <div class="input-group">
                                 <span class="input-group-addon">
                                    <i class="fa fa-fw fa-dollar"></i>
                                 </span>
                                 <input class="form-control numeric" type="text" name="budget" id="budget" placeholder="<?php echo lang("job_fm_budget_ph");?>" maxlength="11"  required />
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_po_lbl" ); ?><span class="required">*</span></label>
                           <div class="col-sm-8">
                              <input type="text" name="purchaseOrderNo" class="form-control" maxlength="15" id="purchaseOrderNo" placeholder="<?php echo lang( "job_fm_po_ph" ); ?>"  required="required"/>
                           </div>
                        </div>
                        <!-- <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_crowm_allotment_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="crownAllotment" class="form-control" maxlength="50" id="crownAllotment" placeholder="<?php echo lang( "job_fm_crowm_allotment_ph" ); ?>"  />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_section_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="section" class="form-control" maxlength="50" id="section" placeholder="<?php echo lang( "job_fm_section_ph" ); ?>"  />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_parish_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="parish" class="form-control" maxlength="50" id="parish" placeholder="<?php echo lang( "job_fm_parish_ph" ); ?>"  />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_area_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="area" class="form-control" maxlength="15" id="area" placeholder="<?php echo lang( "job_fm_area_ph" ); ?>"  />
                           </div>
                        </div> -->
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_tender_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="tender" class="form-control numeric" maxlength="1" id="tender" placeholder="<?php echo lang( "job_fm_tender_ph" ); ?>"  />
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_recieved_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="received" class="form-control datepicker" id="received" placeholder="<?php echo lang( "job_fm_recieved_ph" ); ?>"  />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_start_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="start" class="form-control datepicker" id="start" placeholder="<?php echo lang( "job_fm_start_ph" ); ?>"  />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-sm-4 control-label"><?php echo lang( "job_fm_finish_lbl" ); ?></label>
                           <div class="col-sm-8">
                              <input type="text" name="finish" class="form-control datepicker"  id="finish" placeholder="<?php echo lang( "job_fm_finish_ph" ); ?>"  />
                           </div>
                        </div>
                     </div>
                  </div>
               </form>

            </div>
            <footer class="panel-footer">
               <div class="row">
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary " id="submit-job" data-form="add" ><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                     <button class="btn btn-default hidden reset" type="reset"><?php echo lang( 'system_btn_reset' ); ?></button>
                  </div>
               </div>
            </footer>
         </section>
      </div>


