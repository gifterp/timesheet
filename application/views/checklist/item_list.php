<?php
/**
 * Index form that views check item data list
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
// Functions to display html checklist form

?> 
   
               <!-- start: page -->
               <div class="row">
                  <div class="col-md-6">
                  <section class="panel panel-featured">
                     <header class="panel-heading" data-panel-toggle>
                        <div class="panel-actions">
                           <button class="btn btn-primary modal-with-zoom-anim add-list-row" id="add-check" data-link="<?php echo ROOT_RELATIVE_PATH; ?>checklist/create_item_checklist" data-success-msg="<?php echo lang( "checklist_notif_add_item_success" ); ?>" data-error-msg="<?php echo lang( "checklist_notif_add_item_error" ); ?>">
                              <?php echo lang( "checklist_add_btn_item_text" ); ?> 
                           </button>
                        </div> 
                        <h2 class="panel-title"><?php echo lang( "checklist_edit_item_details" ); ?></h2>
                     </header>

                     <input type="hidden" value="<?php echo $checklistItemCount; ?>" id="counter">
                     <input type="hidden" value="<?php echo $checklistDetails->checklistId; ?>" id="check-list-id">
                     <div class="panel-body" id="list-container">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <section class="panel panel-primary">
                        <header class="panel-heading" data-panel-toggle>
                           <h2 class="panel-title"><?php echo lang( "checklist_item_name" ); ?></h2>
                        </header>
                        <div class="panel-body">
                           <div id="checklist-container"></div>
                        </div>
                     </section>
                  </div>
               </div>
               <!-- end: page -->

            </section><?php // tag opened in _templates/page-header.php ?>

            <!-- end: section role="main" class="content-body" -->
