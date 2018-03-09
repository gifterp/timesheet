<?php
/**
 * Index form that views checklist data tables list
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?> 
    
                  <!-- start: page --> 
                  <section class="panel">
                     <header class="panel-heading" data-panel-toggle>
                        <div class="panel-actions">
                           <button href="#modalForm" class="btn btn-primary modal-with-form modal-with-zoom-anim add" ><?php echo lang( "checklist_add_btn_text" ); ?></button>
                        </div>
                     
                        <h2 class="panel-title"><?php echo lang( "checklist_list_title" ); ?></h2>
                     </header> 
                     <div class="panel-body">
                        <table class="table  table-striped tbl-checklist datatable-row-link" id="datatable-editable">
                           <thead>
                              <tr>
                                 <th><?php echo lang( "checklist_tbl_hdr_id" ); ?></th>
                                 <th><?php echo lang( "checklist_tbl_hdr_title" ); ?></th>
                                 <th><?php echo lang( "checklist_tbl_hdr_desc" ); ?></th>
                                 <th><?php echo lang( "checklist_tbl_hdr_action" ); ?></th>
                              </tr>
                           </thead>
                        </table>
                     </div>
                  </section>
                  <!-- end: page -->
               </section><?php // tag opened in _templates/page-header.php ?> 
               <!-- end: section role="main" class="content-body" -->



