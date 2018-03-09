<?php
/**
 * Checklist selection form modal for job section page
 *
 *  
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?> 
 
      <div id="add-check-list" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
         <section class="panel">
            <header class="panel-heading">
               <h2 class="panel-title" id="form-title"></h2>
            </header>
 
            <div class="panel-body"> 
               <form id="form-checklist" class="form-horizontal form-modal form-checklist" >
                  <?php echo form_input(['name' => 'jobId', 'class'=> 'form-control', 'type' => 'hidden', 'value' => $jobId ]); ?> 
                  <div class="form-group">
                     <label class="col-sm-3 control-label"><?php echo lang( "job_checklist_fm_checklist_lbl" ); ?></label>
                     <div class="col-sm-9">
                        <div class="input-group">
                           <span class="input-group-addon">
                              <i class="fa fa-fw fa-check"></i>
                           </span>
                           <select class="form-control checklist-selector" name="checklistId" id="checklistId" ></select>
                        </div>
                     </div>
                  </div>
      
                  
               </form>

            </div>
            <footer class="panel-footer">
               <div class="row">
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary " id="submit-checklist" data-form="add" ><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
         </section>
      </div>


