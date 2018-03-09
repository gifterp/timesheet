<?php
/**
 * Index form that views job type data tables list
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
                           <button id="addToTable" class="btn btn-primary add" data-link="<?php echo ROOT_RELATIVE_PATH .'admin/mockinvoice_categories/save'; ?>" data-delete="<?php echo ROOT_RELATIVE_PATH .'admin/mockinvoice_categories/delete_single_row'; ?>" data-ajax-success="<?php echo lang( "mockinvoice_categories_notif_add_success" ); ?>"  data-ajax-error="<?php echo lang( "mockinvoice_categories_notif_add_error" ); ?>" 
                            data-update-success="<?php echo lang( "mockinvoice_categories_notif_edit_success" ); ?>"  data-update-error="<?php echo lang( "mockinvoice_categories_notif_edit_error" ); ?>" data-delete-success="<?php echo lang( "mockinvoice_categories_notif_delete_success" );?>" data-delete-error="<?php echo lang( "mockinvoice_categories_notif_delete_error" );?>"><?php echo lang("mockinvoice_categories_add_btn_text"); ?></button>

                        </div>
                        <h2 class="panel-title"><?php echo lang("mockinvoice_categories_list_title");?></h2>
                     </header>
                     <div class="panel-body">
                        <form id="table-form">
                              <table class="table  table-striped" id="datatable-editable">
                                 <thead>
                                    <tr>
                                       <th><?php echo lang("mockinvoice_categories_tbl_hdr_id");?></th>
                                       <th><?php echo lang("mockinvoice_categories_tbl_hdr_name");?></th>
                                       <th><?php echo lang("mockinvoice_categories_tbl_hdr_actions");?></th>
                                    </tr>
                                 </thead>
                              </table>
                        </form>
                     </div>
                  </section>
               <!-- end: page -->
            </section><?php // tag opened in _templates/page-header.php ?>
            
            <!-- end: section role="main" class="content-body" -->
