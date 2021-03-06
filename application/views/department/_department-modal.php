<?php
/**
 * User form for adding/editing department details
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
                  <h2 class="panel-title" id="form-title"><?php echo lang( "department_add_fm_title" ); ?></h2>
               </header>  
                
         <div class="panel-body">       
            <form id="form-department" class="form-horizontal form-modal form-department" >
          <?php echo form_input(['name' => 'departmentId', 'id' => 'departmentId', 'class'=> 'form-control', 'type' => 'hidden']); ?> 
           <div class="form-group">
               <label class="col-sm-3 control-label"><?php echo lang( "department_fm_name_lbl" ); ?><span class="required">*</span></label>
               <div class="col-sm-9">
                  <input type="text" name="name" id="name" maxlength="50" class="form-control" placeholder="<?php echo lang( "department_fm_name_ph" ); ?>" required/>
               </div>
            </div>
         </div>
        <footer class="panel-footer">
               <div class="row">
                  <div class="col-md-12 text-right">
                     <button class="btn btn-primary modal-confirm-submit" id="modal-confirm-button" data-form-id="#form-department" data-ajax-url="/department/create" data-ajax-method="updateFromFormData" data-ajax-success="<?php echo lang( "department_notif_add_success" ); ?>" data-ajax-error="<?php echo lang( "department_notif_add_error" ); ?>"><?php echo lang( 'system_btn_confirm' ); ?></button>
                     <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                  </div>
               </div>
            </footer>
      </section>
          </form>
   </div>

