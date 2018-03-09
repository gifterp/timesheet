<?php
/**
 * Job view page
 *
 * This list all the job in data tables
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

               <!-- start: page -->
               <section class="panel">
                  <header class="panel-heading">
                     <div class="panel-actions">
                        <button id="modal-choose-customer-link " href="#modalChooseJob" class="btn btn-primary modal-with-form-customer-chooser modal-choose-customer-link add-job pull-right"><?php echo lang( "job_add_btn_text" ); ?></button>
                        <label class="pr-xs pt-xs"><?php echo lang( "job_archive_checkbox" ); ?></label>
                        <div class="checkbox-custom chekbox-primary pull-right mr-sm pt-sm">
                           <input  value="1" id="arc" aria-required="true" type="checkbox">
                           <label></label>
                        </div>
                     </div>

                     <h2 class="panel-title"><?php echo lang( "job_sect_title" ); ?></h2>
                  </header>
                  <div class="panel-body">
                     <table class="table table-striped datatable-row-link" id="job-editable">
                        <thead>
                           <tr>
                              <th><?php echo lang( "job_tbl_hdr_id" ); ?></th>
                              <th><?php echo lang( "job_tbl_hdr_customer" ); ?></th>
                              <th><?php echo lang( "job_tbl_hdr_job" ); ?></th>
                              <th><?php echo lang( "job_tbl_hdr_job_type" ); ?></th>
                              <th><?php echo lang( "job_tbl_hdr_suburb" ); ?></th>
                           </tr>
                        </thead>
                     </table>
                  </div>
               </section>
               <!-- end: page -->
               </section><?php // tag opened in _templates/page-header.php ?>

            <!-- end: section role="main" class="content-body" -->


  