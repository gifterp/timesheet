<?php
/**
 * Sidebar Right
 *
 * Displays a calendar to jump directly to timesheet diary dates
 * Allows users with permission to view other users timesheet entries
 *
 * @author      Matt Batten <matt@improvedsoftware.com.au>
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>
         </div>
         <!-- end: inner wrapper -->

         <aside id="sidebar-right" class="sidebar-right">
            <div class="nano">
               <div class="nano-content">
                  <a href="#" class="mobile-close visible-xs">
                     Collapse <i class="fa fa-chevron-right"></i>
                  </a>

                  <div class="sidebar-right-wrapper">

                     <div class="sidebar-widget widget-calendar">
                        <h6>Jump to Diary Date</h6>
                        <div id="timesheet-datepicker" data-plugin-datepicker data-plugin-skin="dark" data-date-format="yyyy-mm-dd"></div>
                     </div>
<?php if ($_SESSION['accessLevel'] > 49) { ?>
                     <div>
                        <h6>Currently viewing diary of</h6>
                        <div class="form-group mt-md">
                           <div class="col-sm-12">
                              <select data-plugin-selectTwo data-plugin-options='{"width": "100%"}' class="form-control populate select2-multiple" name="timesheet-user-switcher" id="timesheet-user-switcher" style="width: 100%">
<?php //printStaffOptions($db); ?>
                              </select>
                           </div>
                        </div>
                     </div>
<?php } ?>

                  </div>
               </div>
            </div>
         </aside>

      </section>
      <!-- end: section body -->
