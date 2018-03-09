<?php
/**
 * Advanced search page
 *
 * Allows advanced searches to be performed on jobs and also has a list of all current jobs
 * with the ability to filter
 *
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
?>
           	<!-- start: page --> 
			   <div class="row"> 
			      <div class="col-md-8">
			                  <section class="panel panel-featured">
			                     <header class="panel-heading" data-panel-toggle>
			                        <h2 class="panel-title"><?php echo lang( 'adv_search_sect_title' ); ?></h2>
			                     </header>

										<div class="panel-body">
										<form id="form-advance-search" class="form-advance-search" action="<?php echo ROOT_RELATIVE_PATH; ?>search/result" method="POST">	
											<div class="row">
												<div class="col-md-12 text-right">
													<label class="pr-xs pt-xs"><?php echo lang( "search_archive_checkbox" ); ?></label>
					                           <div class="checkbox-custom chekbox-primary pull-right mr-sm pt-sm">
					                              <input  value="1" id="archived"  aria-required="true" type="checkbox">
					                              <label></label>
					                           </div>
					                           <?php echo form_input(['name' => 'j.archived', 'id' => 'jarchived', 'class'=> 'form-control', 'type' => 'hidden', 'value' => 0]); ?>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="well well-sm p-xs mb-sm"><strong><?php echo lang( 'search_fm_hdr_keyword_lbl' ); ?></strong></div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<input class="form-control" name="c.name" id="name" type="text" placeholder="<?php echo lang( 'search_fm_name_ph' ); ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="well well-sm p-xs mb-sm mt-md"><strong><?php echo lang( 'search_fm_hdr_job_details_lbl' ); ?></strong></div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_job_type_lbl' ); ?></label>
														<input class="form-control" name="j.jobType" id="jobType" type="text" placeholder="<?php echo lang( 'search_fm_job_type_ph' ); ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_suburb_lbl' ); ?></label>
														<input class="form-control" name="j.suburb" id="suburb" type="text" placeholder="<?php echo lang( 'search_fm_suburb_ph' ); ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_street_lbl' ); ?></label>
														<input class="form-control" name="j.streetName" type="text" placeholder="<?php echo lang( 'search_fm_street_ph' ); ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_post_code_lbl' ); ?></label>
														<input class="form-control" name="j.postcode" id="postcode" type="text" placeholder="<?php echo lang( 'search_fm_post_code_ph' ); ?>">
													</div>
												</div>
											</div>
											<div class="row <?php echo $cadastralPanelHidden; ?>">  
												<div class="col-md-12">
													<div class="well well-sm p-xs mb-sm mt-md"><strong><?php echo lang( 'search_fm_hdr_job_cadastral_lbl' ); ?></strong></div>
												</div>
											</div>
											<div class="row <?php echo $cadastralPanelHidden; ?>">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_plan_category_lbl' ); ?></label>
														<input class="form-control" name="tc.planningCategory" id="planningCategory" type="text" placeholder="<?php echo lang( 'search_fm_plan_category_ph' ); ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_plan_no_lbl' ); ?></label>
														<input class="form-control" name="tc.planNo" id="planNo" type="text" placeholder="<?php echo lang( 'search_fm_plan_no_ph' ); ?>">
													</div>
												</div>
											</div>
											<div class="row <?php echo $cadastralPanelHidden; ?>">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_parish_lbl' ); ?></label>
														<input class="form-control" name="tc.parishName" id="parishName" type="text" placeholder="<?php echo lang( 'search_fm_parish_ph' ); ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_crown_allotment_lbl' ); ?></label>
														<input class="form-control" name="tc.crownAllotment" id="postcode" type="text" placeholder="<?php echo lang( 'search_fm_crown_allotment_ph' ); ?>">
													</div>
												</div>
											</div>
											<div class="row <?php echo $cadastralPanelHidden; ?>">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_section_lbl' ); ?></label>
														<input class="form-control" name="tc.section" type="text" placeholder="<?php echo lang( 'search_fm_section_ph' ); ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_township_lbl' ); ?></label>
														<input class="form-control" name="tc.township" type="text" placeholder="<?php echo lang( 'search_fm_township_ph' ); ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="well well-sm p-xs mb-sm mt-md"><strong><?php echo lang( 'search_fm_hdr_area_lbl' ); ?></strong></div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_easting_lbl' ); ?></label>
														<input class="form-control" name="j.easting" type="text" placeholder="<?php echo lang( 'search_fm_easting_ph' ); ?>">
													</div>
												</div>
								
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_northing_lbl' ); ?></label>
														<input class="form-control" name="j.northing" type="text" placeholder="<?php echo lang( 'search_fm_northing_ph' ); ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_zone_lbl' ); ?></label>
														<input class="form-control" name="j.zone" type="text" placeholder="<?php echo lang( 'search_fm_zone_ph' ); ?>" value="<?php echo $settings->defaultZone;?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_distance_lbl' ); ?></label>
														<input class="form-control" name="distance" type="text" placeholder="<?php echo lang( 'search_fm_distance_ph' ); ?>" value="1000">
													</div>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-md-12">
													<button class="btn btn-primary mb-xs mt-xs mr-xs btn btn-primary btn-lg btn-block" id="search-confirm-button" name="submit-search" data-form-id="#form-advance-search" data-ajax-url="/search/result" ><?php echo lang( 'system_btn_search' ); ?></button>
												</div>
											</div>
											</form>
											<form class="form-horizontal form-bordered" action="#">
										</div>

			                  </section>
			      </div>
			      <div class="col-md-4">
			      	<div class="row">
			            <div class="col-md-12">
			               <section class="panel panel-featured">
			                  <header class="panel-heading" data-panel-toggle>
			                     <h2 class="panel-title"><?php echo lang( 'search_job_sect_title' ); ?></h2>
			                  </header>
			                  <div class="panel-body">
                                 <div id="draggable-jobs">
                                    <div class="input-group mb-md">
                                       <span class="input-group-addon">
                                          <i class="fa fa-search"></i>
                                       </span>
                                       <input type="text" class="form-control search" placeholder="<?php echo lang( 'seach_list_job_filter_ph' ); ?>">
                                    </div>
                                    <div class="scrollable visible-slider" data-plugin-scrollable style="height: 350px;">
                                       <div class="scrollable-content">

                                          <div class="list">
<?php print_job_list_filter( $jobObjArray, false, true ); ?>
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
            