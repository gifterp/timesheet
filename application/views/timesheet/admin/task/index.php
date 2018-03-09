<?php
/**
 * Administration - Timesheet Settings Index Page
 *
 * Gives administrators an easy way to change the settings for the timesheet diary
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>

   <!-- start: page -->
   <div class="row">
      <div class="col-md-8">
         <div class="row">
            <div class="col-md-12">
                  <section class="panel panel-featured">
                     <header class="panel-heading" data-panel-toggle>
                        <div class="panel-actions">
                         <button href="#groupmodal" class="btn btn-primary modal-with-form modal-with-zoom-anim add-group"  data-focus-element="#groupName"><?php echo lang( "timesheet_adm_group_fm_btn_add" ); ?> </button>
                        </div>
                        <h2 class="panel-title"><?php echo lang( 'timesheet_adm_group_display_title' ); ?></h2>
                     </header>
                     <div class="panel-body group-panel"></div>
                  </section>
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-12">
               <section class="panel panel-featured">
                  <header class="panel-heading" data-panel-toggle>
                     <h2 class="panel-title"><?php echo lang( 'timesheet_adm_task_display_title' ); ?></h2>
                  </header>
                  <div class="panel-body task-list"></div>
               </section>
            </div>
         </div>
      </div>
   </div>
   <!-- end: page -->
</section><?php // tag opened in _templates/page-header.php ?>

<!-- end: section role="main" class="content-body" -->
