<?php
/**
 * Timesheet Index Page
 *
 * Displays the timesheet diary and allows users to add/edit the jobs and tasks they have been working on
 * The timesheets use the jQuery FullCalendar plugin
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

// Functions to print select dropdown options and draggable lists
require_once ( $_SERVER['DOCUMENT_ROOT'] . '/assets-system/php/timesheet-functions.php' );
?>

               <!-- start: page -->
               <div class="row">
                  <div class="col-md-9">
                     <div id="message-area"></div>
                     <section class="panel panel-featured">
                        <div class="panel-body">
                           <div id="calendar" class="manual"></div>

                           <!-- Job selection modal -->
                           <a class="mb-xs mt-xs mr-xs btn btn-default modal-with-form-job-chooser modal-choose-job-link mfp-hide" id="#modal-choose-job-link" href="#modalChooseJob">Choose Job</a>
                           <div id="modalChooseJob" class="modal-block modalChooseJob modal-diary-entry mfp-hide">
                              <section class="panel">
                                 <header class="panel-heading">
                                    <h2 class="panel-title"><?php echo lang( 'timesheet_mdl_choose_job_title' ); ?></h2>
                                 </header>
                                 <div class="panel-body">
                                    <div class="modal-wrapper">
                                       <div class="modal-text">
                                          <form>
                                             <select name="job-chooser" id="job-chooser" data-placeholder="<?php echo lang( 'timesheet_fm_placeholder_job' ); ?>" class="job-chooser chosen-select">
                                                <option value=""></option>
<?php print_job_options( $jobObjArray ); ?>
                                             </select>
                                             <button class="btn btn-primary modal-confirm-entry job-chooser-confirm mfp-hide"><?php echo lang( 'system_btn_confirm' ); ?></button>
                                             <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </section>
                           </div>

                           <!-- Task selection modal -->
                           <a class="mb-xs mt-xs mr-xs btn btn-default modal-with-form-task-chooser modal-choose-task-link mfp-hide" id="#modal-choose-task-link" href="#modalChooseTask">Choose Job</a>
                           <div id="modalChooseTask" class="modal-block modalChooseTask modal-diary-entry mfp-hide">
                              <section class="panel">
                                 <header class="panel-heading">
                                    <h2 class="panel-title"><?php echo lang( 'timesheet_mdl_choose_task_title' ); ?></h2>
                                 </header>
                                 <div class="panel-body">
                                    <div class="modal-wrapper">
                                       <div class="modal-text">
                                          <form>
                                             <select name="task-chooser" id="task-chooser" data-placeholder="<?php echo lang( 'timesheet_fm_placeholder_task' ); ?>" class="task-chooser chosen-select">
                                                <option value=""></option>
<?php print_task_options( $taskObjArray ); ?>
                                             </select>
                                             <button class="btn btn-primary modal-confirm-entry task-chooser-confirm mfp-hide"><?php echo lang( 'system_btn_confirm' ); ?></button>
                                             <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </section>
                           </div>

                           <!-- Comment entry modal -->
                           <a class="mb-xs mt-xs mr-xs btn btn-default modal-with-form-comment modal-comment-link mfp-hide" id="#modal-comment-link" href="#modalComment">Add a comment</a>
                           <div id="modalComment" class="modal-block modalComment modal-diary-entry mfp-hide">
                              <section class="panel">
                                 <header class="panel-heading">
                                    <h2 class="panel-title"><?php echo lang( 'timesheet_mdl_comment_title' ); ?></h2>
                                    <p class="panel-subtitle"><?php echo lang( 'timesheet_mdl_comment_subtitle' ); ?></p>
                                 </header>
                                 <div class="panel-body">
                                    <div class="modal-wrapper">
                                       <div class="modal-text">
                                          <form>
                                             <textarea name="initial-comment" id="initial-comment" class="form-control" placeholder="<?php echo lang( 'timesheet_fm_placeholder_comment' ); ?>" rows="3" data-plugin-textarea-autosize></textarea>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                                 <footer class="panel-footer text-right">
                                    <button class="btn btn-primary comment-action"><?php echo lang( 'timesheet_fm_btn_add' ); ?></button>
                                    <button class="btn btn-primary modal-confirm-entry-comment comment-confirm mfp-hide"><?php echo lang( 'timesheet_fm_btn_add' ); ?></button>
                                    <button class="btn btn-default modal-dismiss"><?php echo lang( 'system_btn_cancel' ); ?></button>
                                 </footer>
                              </section>
                           </div>

                           <!-- Edit entry modal -->
                           <a class="mb-xs mt-xs mr-xs btn btn-default modal-with-form-edit-entry modal-edit-entry-link mfp-hide" id="#modal-edit-entry" href="#modalEditEntry">Edit Entry</a>
                           <div id="modalEditEntry" class="modal-block modalEditEntry modal-diary-entry mfp-hide">
                              <section class="panel">
                                 <header class="panel-heading">
                                    <h2 class="panel-title"><?php echo lang( 'timesheet_mdl_edit_title' ); ?></h2>
                                 </header>
                                 <div class="panel-body">
                                    <div class="modal-wrapper">
                                       <div class="modal-text">
                                          <form class="form-horizontal mb-lg" id="entry-form">

                                             <div class="form-group">
                                                <div class="col-sm-12">
                                                   <select name="job-chooser" id="job-chooser-edit" data-placeholder="<?php echo lang( 'timesheet_fm_placeholder_job' ); ?>" class="job-chooser chosen-select-edit" title="<?php echo lang( 'timesheet_fm_placeholder_job' ); ?>" required>
                                                      <option value=""></option>
<?php print_job_options( $jobObjArray ); ?>
                                                   </select>
                                                </div>
                                             </div>

                                             <div class="form-group">
                                                <div class="col-sm-12">
                                                   <select name="task-chooser" id="task-chooser-edit" data-placeholder="<?php echo lang( 'timesheet_fm_placeholder_task' ); ?>" class="task-chooser chosen-select-edit" title="<?php echo lang( 'timesheet_fm_placeholder_task' ); ?>" required>
                                                      <option value=""></option>
<?php print_task_options( $taskObjArray ); ?>
                                                   </select>
                                                </div>
                                             </div>

                                             <div class="form-group">
                                                <div class="col-sm-12">
                                                   <div class="input-group" id="edit-date">
                                                   </div>
                                                </div>
                                             </div>

                                             <div class="form-group">
                                                <div class="col-sm-12">
                                                   <div class="input-group">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-fw fa-clock-o"></i>
                                                      </span>
                                                      <input type="text" name="start-time" id="start-time" data-plugin-timepicker class="form-control" data-plugin-options='{ "minuteStep": 15 }' onfocus="this.blur();" readonly>
                                                      <span class="input-group-addon" id="time-text">to</span>
                                                      <input type="text" name="end-time" id="end-time" data-plugin-timepicker class="form-control" data-plugin-options='{ "minuteStep": 15 }' onfocus="this.blur();" readonly>
                                                   </div>
                                                </div>
                                             </div>

                                             <div class="form-group">
                                                <div class="col-sm-12">
                                                   <textarea name="task-comment" id="task-comment" class="form-control" placeholder="<?php echo lang( 'timesheet_fm_placeholder_comment' ); ?>" rows="3" data-plugin-textarea-autosize></textarea>
                                                </div>
                                             </div>

                                             <div class="form-group">
                                                <div class="col-sm-12">
                                                   <div class="input-group mb-md">
                                                      <span class="input-group-addon">$</span>
                                                      <input name="disbursement" id="disbursement" type="text" class="form-control numeric" placeholder="<?php echo lang( 'timesheet_fm_placeholder_disbursement' ); ?>">
                                                   </div>
                                                </div>
                                             </div>

                                             <input type="hidden" name="entryId" id="entryId" value="" />
                                             <input type="hidden" name="start-moment" id="start-moment" value="" />
                                             <input type="hidden" name="end-moment" id="end-moment" value="" />
                                             <input type="hidden" name="entryObject" id="entryObject" value="" />
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                                 <footer class="panel-footer">
                                    <div class="row">
                                       <div class="col-md-12 text-right">
                                          <button class="btn btn-primary modal-confirm-edit edit-entry-confirm"><i class="fa fa-check"></i> <?php echo lang( 'timesheet_fm_btn_update' ); ?></button>
                                          <button class="btn btn-danger modal-confirm-delete edit-entry-confirm" data-toggle="confirm-delete"><i class="fa fa-trash"></i> <?php echo lang( 'timesheet_fm_btn_delete' ); ?></button>
                                          <button class="btn btn-default modal-dismiss"><i class="fa fa-times"></i> <?php echo lang( 'system_btn_cancel' ); ?></button>
                                       </div>
                                    </div>
                                 </footer>
                              </section>
                           </div>

                           <div id="toolbar-options" class="hidden">
                              <a href="#"><i class="fa fa-plane"></i></a>
                              <a href="#"><i class="fa fa-car"></i></a>
                              <a href="#"><i class="fa fa-bicycle"></i></a>
                           </div>

                        </div>
                     </section>
                  </div>
                  <div class="col-md-3">


                     <div class="row">
                        <div class="col-md-12">
                           <section class="panel panel-featured">
                              <header class="panel-heading" data-panel-toggle>
                                 <h2 class="panel-title"><?php echo lang( 'timesheet_list_personal_title' ); ?></h2>
                                 <p class="panel-subtitle"><?php echo lang( 'timesheet_list_personal_subtitle' ); ?></p>
                              </header>
                              <div class="panel-body" id="droppable-add-job">
                                 <div class="draggable-events" id="personal-events"></div>
                              </div>
                              <div class="panel-footer text-right" id="droppable-remove-job">
                                 <span class="panel-subtitle"><?php echo lang( 'timesheet_drag_remove' ); ?></span><i class="fa fa-trash fa-lg ml-sm"></i>
                              </div>
                           </section>
                        </div>

                        <div class="col-md-12">
                           <section class="panel panel-featured">
                              <header class="panel-heading" data-panel-toggle>
                                 <h2 class="panel-title"><?php echo lang( 'timesheet_list_job_title' ); ?></h2>
                              </header>
                              <div class="panel-body">
                                 <div id="draggable-jobs">
                                    <div class="input-group mb-md">
                                       <span class="input-group-addon">
                                          <i class="fa fa-search"></i>
                                       </span>
                                       <input type="text" class="form-control search" placeholder="<?php echo lang( 'timesheet_list_job_filter_placeholder' ); ?>">
                                    </div>
                                    <div class="scrollable visible-slider" data-plugin-scrollable style="height: 350px;">
                                       <div class="scrollable-content">

                                          <div class="draggable-events draggable-job list">
<?php print_job_draggables( $jobObjArray, false ); ?>
                                          </div>

                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </section>
                        </div>

                        <div class="col-md-12">
                           <section class="panel panel-featured">
                              <header class="panel-heading" data-panel-toggle>
                                 <h2 class="panel-title"><?php echo lang( 'timesheet_list_task_title' ); ?></h2>
                              </header>
                              <div class="panel-body" id="taskPanel">
                                 <div id="draggable-tasks">
                                    <div class="input-group mb-md">
                                       <span class="input-group-addon">
                                          <i class="fa fa-search"></i>
                                       </span>
                                       <input type="text" class="form-control search" placeholder="<?php echo lang( 'timesheet_list_task_filter_placeholder' ); ?>">
                                    </div>
                                    <div class="scrollable visible-slider" data-plugin-scrollable style="height: 350px;">
                                       <div class="scrollable-content">

                                          <div class="draggable-events draggable-task list">
<?php print_task_draggables( $taskObjArray ); ?>
                                          </div>

                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </section>
                        </div>
                     </div>


                  </div>
               </div>
               <!-- end: page -->
            </section><?php // tag opened in _templates/page-header.php ?>
            
            <!-- end: section role="main" class="content-body" -->
