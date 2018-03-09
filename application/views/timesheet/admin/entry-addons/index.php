<?php
/**
 * Index form that views entry additional data tables list
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
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
                           <button id="add-entry" class="btn btn-primary" data-link="<?php echo ROOT_RELATIVE_PATH .'admin/timesheet-entry-addition/create'; ?>" data-delete="<?php echo ROOT_RELATIVE_PATH .'admin/timesheet-entry-addition/delete'; ?>" data-ajax-success="<?php echo lang( "timesheet_entry_add_notif_add_success" ); ?>"  data-ajax-error="<?php echo lang( "timesheet_entry_add_notif_add_error" ); ?>" data-delete-success="<?php echo lang( "timesheet_entry_add_notif_delete_success" );?>" data-delete-error="<?php echo lang( "timesheet_entry_add_notif_delete_error" );?>" data-update-success="<?php echo lang( "timesheet_entry_add_notif_edit_success" );?>" data-update-error="<?php echo lang( "timesheet_entry_add_notif_edit_error" );?>"><?php echo lang("timesheet_entry_add_add_btn_text"); ?></button>
                           <input type="hidden" value='<?php echo $option; ?>' id="entry-type" />
                        </div>
                     

                  <h2 class="panel-title"><?php echo lang( "timesheet_entry_add_list_title" ); ?></h2>
                     </header>
                     <div class="panel-body">
                        <form id="table-multiplier">
                           <table class="table table-striped mb-none tbl_tea" id="datatable-editable">
                              <thead>
                                 <tr>
                                    <th><?php echo lang( "timesheet_entry_add_tbl_hdr_id" ); ?></th>
                                    <th><?php echo lang( "timesheet_entry_add_tbl_hdr_name" ); ?></th>
                                    <th><?php echo lang( "timesheet_entry_add_tbl_hdr_desc" ); ?></th>
                                    <th><?php echo lang( "timesheet_entry_add_tbl_hdr_type" ); ?></th>
                                    <th><?php echo lang( "timesheet_entry_add_tbl_hdr_value" ); ?></th>
                                    <th><?php echo lang( "timesheet_entry_add_tbl_hdr_action" ); ?></th>
                                 </tr>
                              </thead>   
                           </table>
                        </form>
                     </div>
                  </section>
               <!-- end: page -->
            </section><?php // tag opened in _templates/page-header.php ?>
            
            <!-- end: section role="main" class="content-body" -->

      