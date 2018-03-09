<?php
/**
 * Job view page
 *
 * This lists all the job details. Includes customer details, job details, cadastral details (if any)
 *
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>  
               <input name="invoiceId" type="hidden" value='<?php echo $invoiceId; ?>' />
               <!-- start: page -->
               <div class="row">
                  <div class="col-md-12">
                     <div class="panel-body bg-primary job-header"></div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-8">
                      <!-- section for customer details  starts here -->
                     <section class="panel panel-featured">
                        <header class="panel-heading" data-panel-toggle>
                           <h2 class="panel-title"><?php echo lang( "job_title_customer_details" ); ?></h2>
                        </header>
                        <div class="panel-body customer-panel-details pt-xs"></div>
                        <div class="panel-footer text-right parentVerify">
                            <button id="customer-id modal-choose-customer-link "  data-customer-id="<?php echo $jobDetails->customerId; ?>" href="#modalEditChooseJob" class="btn btn-primary modal-with-form-customer-chooser modal-choose-customer-link edit edit-customer" data-id="<?php echo $jobId; ?>"><?php echo lang( "job_edit_cust_btn_text" ); ?> </button>
                        </div>
                        
                      
                     </section>
                     <!-- section for customer details  ends here -->

                     <section class="panel panel-featured">
                        <header class="panel-heading" data-panel-toggle>
                           <h2 class="panel-title"><?php echo lang( "job_title_details" ); ?></h2>
                        </header>
                        <!-- Job Details Panel -->
                        <div class="panel-body job-panel-details pt-xs"></div>
                        <div class="panel-footer">
                           <div class="row">
                              <div class="text-left col-md-6 pull-left">
                              <?php if ( $_SESSION['accessLevel'] == 99 || $_SESSION['accessLevel'] == 50 ) { ?>
                                 <div class="switch switch-sm switch-primary ib mr-xs">
                                    <!-- Hidden field value is used unless checkbox is checked -->
                                    <input type="hidden"    name="archived" id="archivedHidden" value="1" />
                                    <input type="checkbox"  name="archived" id="archived" value="0" data-plugin-ios-switch  checked="checked" />
                                 </div>
                              <?php } ?>
                                 <p class="ib job-archived">
                                    <span class="label label-success archived-off hidden"><strong><?php echo lang( "job_archive_status_current" ); ?></strong></span>
                                    <span class="label label-warning archived-on hidden"><strong><?php echo lang( "job_archive_status_archive" ); ?></strong></span>
                                 </p>
                              </div>
                              <div class="text-right col-md-6">
                                 <button href="#add-check-list" data-form="#add-check-list" data-title="<?php echo lang( "job_checklist_add_fm_title" ); ?>"  class="btn btn-primary modal-with-form modal-with-zoom-anim add-form add-checklist-button <?php echo $checklistButtonHidden; ?>"> <?php echo lang( "checklist_add_btn_text" ); ?> </button>
                                 <button href="#add-cadastral"  data-form="#add-cadastral" data-url="/job/create_job_cadastral" data-title="<?php echo lang( "job_add_cadastral_fm_title" ); ?>" data-links="<?php echo ROOT_RELATIVE_PATH . 'job/'; ?>"  data-success="<?php echo lang( "job_notif_cadastral_add_success" ); ?>" data-error="<?php echo lang( "job_notif_cadastral_add_error" ); ?>" data-cadastral-id="<?php echo $jobDetails->cadastralId; ?>" class="btn btn-primary modal-with-form modal-with-zoom-anim add-form cadastral-button <?php echo $cadastralButtonHidden; ?>"> <?php echo lang( "job_add_cadastral_btn_text" ); ?> </button>
                                 <button href="#add-job"  data-id="<?php echo $jobDetails->jobId; ?>" data-form="#add-job" class="btn btn-primary modal-with-form modal-with-zoom-anim edit"><?php echo lang( "job_edit_btn_text" ); ?> </button>
                                 <input type="hidden" value="<?php echo $jobDetails->archived; ?>" id="isArchived" />
                             </div>
                           </div>
                        </div>
                     </section>

                     <!-- section for cadastral  start here -->
                     <section class="panel panel-featured cadastral-panel <?php echo $cadastralPanelHidden; ?>">
                        <header class="panel-heading" data-panel-toggle>
                              <input type="hidden" value="<?php echo $jobDetails->cadastralId; ?>" id="cadastralId" />
                              <input type="hidden" value="<?php echo $settings->hasCadastral; ?>" id="hasCadastral" />
                           <h2 class="panel-title"><?php echo lang( "job_title_cadastral_details" ); ?>
                           </h2>
                        </header>
                        <!-- Job Cadastral Panel -->
                        <div class="panel-body job-cadastral pt-xs"></div>
                        

                        <div class="panel-footer text-right">
                            <button href="#add-cadastral" data-form="#add-cadastral"  data-title="<?php echo lang( "job_edit_cadastral_fm_title" ); ?>" class="btn btn-primary modal-with-form modal-with-zoom-anim add-form "  data-cadastral-id="<?php echo $jobDetails->cadastralId; ?>"> <?php echo lang( "job_edit_cadastral_btn_text" ); ?>  </button>
                        </div>
                     </section>
                     <!-- section for cadastral  ends here -->

                     <!-- section for checklist  starts here -->
                     <section class="panel panel-featured checklist-panel <?php echo $checklistPanelHidden; ?>">
                        <header class="panel-heading" data-panel-toggle>
                           <input id="error-form-job-checklist" type="hidden" value='<?php echo lang( "checklist_item_fm_error" ); ?>' />
                           <input type="hidden" value="<?php echo $countList; ?>" id="count-list">
                           <h2 class="panel-title"><?php echo lang( "job_title_checklist" ); ?></h2>
                        </header>
                        <div class="panel-body">
                           <div class="row">
                              <!-- div for checklist form output -->
                              <div class="col-md-12">
                                 <div id="form-container"></div> <!-- container holds the checklist data form -->
                              </div>
                           </div>
                        </div>
                        <div class="panel-footer text-right checklistVerify">
                           <button href="#add-check-list" data-links="<?php echo ROOT_RELATIVE_PATH . 'job/'; ?>" data-form="#add-check-list" data-title="<?php echo lang( "job_checklist_add_fm_title" ); ?>" data-success="<?php echo lang( "checklist_notif_add_success" ); ?>" data-error="<?php echo lang( "checklist_notif_add_error" ); ?>" data-job-id="<?php echo $jobId; ?>" data-url="/job/create_job_checklist" class="btn btn-primary modal-with-form modal-with-zoom-anim add-form">
                            <?php echo lang( "checklist_add_btn_text" ); ?> </button>
                        </div>
                     </section>


                  <!-- section for notes  starts here -->
                  <section class="panel panel-featured">
                        <header class="panel-heading" data-panel-toggle>
                              <input name="user" type="hidden" value='<?php echo $option; ?>' />
                              <input name="userId" type="hidden" value='<?php echo $_SESSION['userId']; ?>' />
                              <input name="jobId" type="hidden" value='<?php echo $jobId; ?>' />
                           <h2 class="panel-title"><?php echo lang("job_title_notes"); ?></h2>
                        </header>
                        <div class="panel-body">
                           <form id="tbl-job-notes">
                              <table class="table table-striped mb-none tbl_job_notes display"  id="datatable-editable-notes">
                                 <thead>
                                    <tr>
                                       <th><?php echo lang("job_notes_tbl_hdr_id"); ?></th>
                                       <th><?php echo lang("job_notes_tbl_hdr_note_date"); ?></th>
                                       <th><?php echo lang("job_notes_tbl_hdr_user_member"); ?></th>
                                       <th><?php echo lang("job_notes_tbl_hdr_comment"); ?></th>
                                       <th><?php echo lang("job_notes_tbl_hdr_actions"); ?></th>
                                    </tr>
                                 </thead>   
                              </table>
                           </form>
                        </div>
                        <div class="panel-footer text-right">
                           <button id="add-notes" class="btn btn-primary add" data-link="<?php echo ROOT_RELATIVE_PATH . 'job/create-job-notes'; ?>" data-delete="<?php echo ROOT_RELATIVE_PATH . 'job/deletenotes'; ?>" data-ajax-success="<?php echo lang( "job_notes_notif_add_success" ); ?>"  data-ajax-error="<?php echo lang( "job_notes_notif_add_error" ); ?>" data-ajax-update-success="<?php echo lang( "job_notes_notif_edit_success" ); ?>"  data-ajax-update-error="<?php echo lang( "job_notes_notif_edit_error" ); ?>" data-delete-success="<?php echo lang( "job_notes_notif_delete_success" ); ?>" data-delete-error="<?php echo lang( "job_notes_notif_delete_error" ); ?>"><?php echo lang( "job_notes_add_btn_text" ); ?> </button>
                        </div>
                  </section>
                  <!-- section for notes  ends here -->
    
                  </div>

                  <div class="col-md-4">

                     <!-- section for sub files  starts here -->
                     <section class="panel panel-featured">
                        <header class="panel-heading" data-panel-toggle>
                              <input type="hidden" id="checkFiles" value="<?php echo $jobDetails->parentJobId; ?>">
                           <h2 class="panel-title sub-header"></h2>
                        </header>
                        <div class="panel-body sub-files-details"></div>
                        <div class="panel-footer text-right">
                           <button href="#add-job" data-form="#form-job" data-title="<?php echo lang("job_add_child_fm_title"); ?>"  class="btn btn-primary modal-with-form modal-with-zoom-anim add-form job-add child-job"><?php echo lang( "job_add_btn_text" ); ?></button>
                        </div>
                     </section>
                     <!-- section for sub files  ends here -->

                     <!-- section for job invoice  starts here -->
                     <section class="panel panel-featured">
                        <header class="panel-heading" data-panel-toggle>
                           <h2 class="panel-title"><?php echo lang( "job_title_invoices" ); ?></h2>
                        </header>
                        <div class="panel-body">
                           <form id="tbl-job-invoice">
                              <table class="table table-striped mb-none display" id="datatable-editable-invoice">
                                 <thead>
                                    <tr>
                                       <th><?php echo lang( "job_invoice_tbl_hdr_id" ); ?></th>
                                       <th><?php echo lang( "job_invoice_tbl_hdr_inv_date" ); ?></th>
                                       <th><?php echo lang( "job_invoice_tbl_hdr_inv_no" ); ?></th>
                                       <th><?php echo lang( "job_invoice_tbl_hdr_amount" ); ?></th>
                                       <th><?php echo lang( "job_invoice_tbl_hdr_mock_inv" ); ?></th>
                                       <th><?php echo lang( "job_invoice_tbl_hdr_actions" ); ?></th>
                                    </tr>
                                 </thead>   
                              </table>
                           </form>
                        </div>
                        <div class="panel-footer text-right">
                           <button id="add-invoice" class="btn btn-primary add" data-link="<?php echo ROOT_RELATIVE_PATH . 'job/create-job-invoice'; ?>" data-delete="<?php echo ROOT_RELATIVE_PATH . 'job/deleteinvoice'; ?>" data-ajax-success="<?php echo lang( "job_invoice_notif_add_success" );?>"  data-ajax-error="<?php echo lang("job_invoice_notif_add_error");?>" data-ajax-update-success="<?php echo lang( "job_invoice_notif_edit_success" );?>"  data-ajax-update-error="<?php echo lang("job_invoice_notif_edit_error");?>" data-delete-success="<?php echo lang( "job_invoice_notif_delete_success" ); ?>" data-delete-error="<?php echo lang( "job_invoice_notif_delete_error" ); ?>"><?php echo lang( "job_invoice_add_btn_text" ); ?></button>
                        </div>
                     </section>
                     <!-- section for job invoice  ends here -->
                  </div>
               </div>
               <!-- end: page -->

            </section><?php // tag opened in _templates/page-header.php ?>

            <!-- end: section role="main" class="content-body" -->




