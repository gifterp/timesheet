<?php
/**
 * Index form that views user data tables list
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
<?php if ( $_SESSION['accessLevel'] > 49 ) { ?>
                        <button href="#modalForm" class="btn btn-primary modal-with-form modal-with-zoom-anim add" data-focus-element="#firstName"><?php echo lang("user_add_btn_text"); ?> </button>
<?php } ?>
                     </div>
                     <h2 class="panel-title"><?php echo lang("user_list_title"); ?></h2>
                  </header>
                  <div class="panel-body">
                     <table class="table table-striped mb-none tbl_user" id="datatable-editable">
                        <thead>
                           <tr>
                              <th><?php echo lang("user_tbl_hdr_name"); ?></th>
                              <th><?php echo lang("user_tbl_hdr_username"); ?></th>
                              <th><?php echo lang("user_tbl_hdr_charge_rate"); ?></th>
                              <th><?php echo lang("user_tbl_hdr_access_level"); ?></th>
                              <th><?php echo lang("user_tbl_hdr_status"); ?></th>
                              <th><?php echo lang("user_tbl_hdr_actions"); ?></th>
                           </tr>
                        </thead>
                     </table>
                  </div>
               </section>
               <!-- end: page -->
            </section><?php // tag opened in _templates/page-header.php ?>

            <!-- end: section role="main" class="content-body" -->
