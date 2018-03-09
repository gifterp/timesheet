<?php
/**
 * User form for adding/editing checklist details
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

      <div id="modalForm" class="zoom-anim-dialog modal-block modal-header-color modal-block-primary mfp-hide">
         <section class="panel">
            <header class="panel-heading">
               <h2 class="panel-title" id="form-title"><?php echo lang( "checklist_add_fm_title" ); ?></h2>
            </header>
 
            <div class="panel-body">
               <form id="form-checklist" class="form-horizontal form-modal form-checklist" >
          <?php echo form_input(['name' => 'checklistId', 'id' => 'check-list-id', 'class'=> 'form-control', 'type' => 'hidden']); ?> 
           <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( "checklist_fm_title_lbl" ); ?><span class="required">*</span></label>
               <div class="col-sm-9">
                  <input type="text" name="title" id="title" class="form-control" placeholder="<?php echo lang( "checklist_fm_title_ph" ); ?> " required maxlength="100" />
               </div> 
            </div>
            <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( "checklist_fm_desc_lbl" ); ?> <span class="required">*</span></label>
               <div class="col-sm-9">
                  <textarea type="text" name="description" id="description" class="form-control" placeholder="<?php echo lang( "checklist_fm_desc_ph" ); ?>" required maxlength="100" rows="2" /></textarea>
               </div> 
            </div>
                          
               </form> 
 
            </div>
            <footer class="panel-footer">
               <div class="row">
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary" id="submit-checklist" data-form="add" ><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
         </section>
      </div>


