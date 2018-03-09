<?php
/**
 * Administration - System Settings Page
 *
 * Gives administrators an easy way to change the settings for the system page
 *
 * @author      Gifter Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>

               <!-- start: page -->
               <div class="row">
                  <div class="col-md-12">
                     <div class="row">
                        <div class="col-md-12">
                              <section class="panel panel-featured">
                                 <header class="panel-heading" data-panel-toggle>
                                    <h2 class="panel-title"><?php echo lang( 'system_settings_adm_set_display_title' ); ?></h2>
                                    <p class="panel-subtitle"><?php echo lang( 'system_settings_adm_set_display_subtitle' ); ?></p>
                                 </header>
                                 <div class="panel-body">
                                    <form class="form-horizontal form-bordered" id="form-setting">
                                       <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="col-md-4 control-label" for="businessName"><?php echo lang( 'system_settings_adm_business_lbl' ); ?></label>
                                             <div class="col-md-6">
                                                <div class="input-group mb-sm">
                                                      <span class="input-group-addon">
                                                         <i class="fa fa-university"></i>
                                                      </span>
                                                      <input type="text" name="businessName" id="businessName" class="form-control" value="" placeholder="Input System Business Name" />
                                                      <span class="input-group-addon text-primary">
                                                         <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'system_settings_adm_business_ttip' ); ?>"></i>
                                                      </span>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="col-md-4 control-label" for="jobNameFormat"><?php echo lang( 'system_settings_adm_job_format_lbl' ); ?></label>
                                             <div class="col-md-6">
                                                <div class="input-group mb-sm">
                                                   <span class="input-group-addon">
                                                      <i class="fa fa-list"></i>
                                                   </span>
                                                   <input type="text" name="jobNameFormat" id="jobNameFormat" class="form-control" maxlength="20" value="" placeholder="Input System Job Name Format" />
                                                   <span class="input-group-addon text-primary">
                                                      <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'system_settings_adm_job_format_ttip' ); ?>"></i>
                                                   </span>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="col-md-4 control-label" for="defaultZone"><?php echo lang( 'system_settings_adm_default_zone_lbl' ); ?></label>
                                             <div class="col-md-6">
                                                <div class="input-group mb-sm">
                                                   <span class="input-group-addon">
                                                      <i class="fa fa-map-marker"></i>
                                                   </span>
                                                   <input type="text" name="defaultZone" id="defaultZone" class="form-control" value="" placeholder="Input System Default Zone" />
                                                   <span class="input-group-addon text-primary">
                                                      <i class="fa fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'system_settings_adm_default_zone_ttip' ); ?>"></i>
                                                   </span>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="form-group">
                                          <div class="col-md-12">
                                             <label class="col-md-4 control-label" for="hasCadastral"><?php echo lang( 'system_settings_adm_has_cadastral_lbl' ); ?><i class="fa fa-question-circle ml-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo lang( 'system_settings_adm_has_cadastral_ttip' ); ?>"></i></label>
                                             <div class="col-md-6">
                                                <div class="switch switch-sm switch-primary">
                                                   <input type="checkbox" name="hasCadastral" id="hasCadastral" data-plugin-ios-switch checked />
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </form>
                                 </div>
                              </section>
                        </div>
                     </div>
                  </div>
               
               </div>
               <!-- end: page -->
            </section><?php // tag opened in _templates/page-header.php ?>
            
            <!-- end: section role="main" class="content-body" -->
