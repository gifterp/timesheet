<?php
/**
 * Index form that views mail data tables list
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
                           <button id="addToTable" class="btn btn-primary add" data-link="<?php echo ROOT_RELATIVE_PATH .'mail/create'; ?>" data-delete="<?php echo ROOT_RELATIVE_PATH .'mail/delete'; ?>" data-ajax-success="<?php echo lang( "mail_notif_add_success" ); ?>"  data-ajax-error="<?php echo lang( "mail_notif_add_error" ); ?>" 
                           data-update-success="<?php echo lang( "mail_notif_edit_success" ); ?>"  data-update-error="<?php echo lang( "mail_notif_edit_error" ); ?>" data-delete-success="<?php echo lang( "mail_notif_delete_success" );?>" data-delete-error="<?php echo lang( "mail_notif_delete_error" );?>"><?php echo lang("mail_add_btn_text"); ?></button>

                        </div>
                     

                  <h2 class="panel-title"><?php echo lang( "mail_list_title" ); ?></h2>
                     </header>
                     <div class="panel-body">
                        <form id="table-mail">
                           <table class="table table-striped mb-none" id="datatable-editable">
                              <thead>
                                 <tr>
                                    <th><?php echo lang( "mail_tbl_hdr_id" ); ?></th>
                                    <th><?php echo lang( "mail_tbl_hdr_send" ); ?></th>
                                    <th><?php echo lang( "mail_tbl_hdr_recipient" ); ?></th>
                                    <th><?php echo lang( "mail_tbl_hdr_desc" ); ?></th>
                                    <th><?php echo lang( "mail_tbl_hdr_actions" ); ?></th>
                                 </tr>
                              </thead>   
                           </table>
                        </form>
                     </div>
                  </section>
               <!-- end: page -->
            </section><?php // tag opened in _templates/page-header.php ?>
            
            <!-- end: section role="main" class="content-body" -->

     



