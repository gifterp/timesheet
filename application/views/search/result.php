<?php 
/**
 * Advanced result search page
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
			         <div class="row">
			            <div class="col-md-12">
			                  <section class="panel panel-featured">
			                     <header class="panel-heading" data-panel-toggle>
			                        <h2 class="panel-title"><?php echo lang( 'search_sect_title' ); ?></h2>
			                     </header>
			                     <div class="panel-body <?php echo $searchPanel; ?>" id="search-panel">
			                    <input id="query-value" value='<?php echo urlencode ( http_build_query ( $querySearch ) ); ?>' type="hidden"/>
			                     	<table class="table  table-striped table-hover datatable-row-link" id="search-table">
			                           <thead>
			                              <tr>
			                              	<th><?php echo lang( 'search_tbl_hdr_id' ); ?></th>
			                                 <th><?php echo lang( 'search_tbl_hdr_customer' ); ?></th>
			                                  <th><?php echo lang( 'search_tbl_hdr_job' ); ?></th>
			                                 <th><?php echo lang( 'search_tbl_hdr_job_type' ); ?></th>
			                                 <th><?php echo lang( 'search_tbl_hdr_suburb' ); ?></th>
			                              </tr>
			                           </thead>
			                        </table>
			                     </div>
			                  </section>
			            </div>
			         </div>
			      </div>
			      <div class="col-md-4">
			      <section class="panel panel-featured">
			                     <header class="panel-heading" data-panel-toggle>
			                        <h2 class="panel-title"><?php echo lang( 'adv_search_sect_title' ); ?></h2>
			                     </header>

										<div class="panel-body">
										<form id="form-advance-search" class="form-advance-search"  action="<?php echo ROOT_RELATIVE_PATH; ?>search/result" method="POST">	
											<div class="row">
												<div class="col-md-12 text-right">
													<label class="pr-xs pt-xs"><?php echo lang( "search_archive_checkbox" ); ?></label>
					                           <div class="checkbox-custom chekbox-primary pull-right mr-sm pt-sm">
					                           	<?php
					                           		$checked 	= '';
					                           		$archived 	= 0;
					                           		if ( isset ( $querySearch['j_archived'] ) ) {
					                           			if( $querySearch['j_archived'] == 1 ) {
					                           				$archived 	= '1';
					                           				$checked 	= 'checked';
					                           			} 
					                           		} 
					                           	?>
					                              <input  value="1" id="archived"  aria-required="true" type="checkbox" <?php echo $checked;?> >
					                              <label></label> 
					                           </div>
					                           <?php echo form_input(['name' => 'j.archived', 'id' => 'jarchived', 'class'=> 'form-control', 'type' => 'hidden', 'value' => $archived ] ); ?>
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
														<input class="form-control" name="c.name" id="name" type="text" placeholder="<?php echo lang( 'search_fm_name_ph' ); ?>" value="<?php echo ( isset ( $querySearch['c_name'] ) ? $querySearch[ 'c_name' ] : '' )?>">
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
														<input class="form-control" name="j.jobType" id="jobType" type="text" placeholder="<?php echo lang( 'search_fm_job_type_ph' ); ?>" value="<?php echo ( isset ( $querySearch['j_jobType'] ) ? $querySearch[ 'j_jobType' ] : '' )?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_suburb_lbl' ); ?></label>
														<input class="form-control" name="j.suburb" id="suburb" type="text" placeholder="<?php echo lang( 'search_fm_suburb_ph' ); ?>" value="<?php echo ( isset ( $querySearch['j_suburb'] ) ? $querySearch[ 'j_suburb' ] : '' )?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_post_code_lbl' ); ?></label>
														<input class="form-control" name="j.postcode" id="postcode" type="text" placeholder="<?php echo lang( 'search_fm_post_code_ph' ); ?>" value="<?php echo ( isset ( $querySearch['j_postcode'] ) ? $querySearch[ 'j_postcode' ] : '' )?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_street_lbl' ); ?></label>
														<input class="form-control" name="j.streetName" type="text" placeholder="<?php echo lang( 'search_fm_street_ph' ); ?>" value="<?php echo ( isset ( $querySearch['j_streetName'] ) ? $querySearch[ 'j_streetName' ] : '' )?>">
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
														<input class="form-control" name="tc.planningCategory" id="planningCategory" type="text" placeholder="<?php echo lang( 'search_fm_plan_category_ph' ); ?>" value="<?php echo ( isset ( $querySearch['tc_planningCategory'] ) ? $querySearch[ 'tc_planningCategory' ] : '' )?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_plan_no_lbl' ); ?></label>
														<input class="form-control" name="tc.planNo" id="planNo" type="text" placeholder="<?php echo lang( 'search_fm_plan_no_ph' ); ?>" value="<?php echo ( isset ( $querySearch['tc_planNo'] ) ? $querySearch[ 'tc_planNo' ] : '' )?>">
													</div>
												</div>
											</div>
											<div class="row <?php echo $cadastralPanelHidden; ?>">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_parish_lbl' ); ?></label>
														<input class="form-control" name="tc.parishName" id="parishName" type="text" placeholder="<?php echo lang( 'search_fm_parish_ph' ); ?>" value="<?php echo ( isset ( $querySearch['tc_parishName'] ) ? $querySearch[ 'tc_parishName' ] : '' )?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_crown_allotment_lbl' ); ?></label>
														<input class="form-control" name="tc.crownAllotment" id="postcode" type="text" placeholder="<?php echo lang( 'search_fm_crown_allotment_ph' ); ?>" value="<?php echo ( isset ( $querySearch['tc_crownAllotment'] ) ? $querySearch[ 'tc_crownAllotment' ] : '' )?>">
													</div>
												</div>
											</div>
											<div class="row <?php echo $cadastralPanelHidden; ?>">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_section_lbl' ); ?></label>
														<input class="form-control" name="tc.section" type="text" placeholder="<?php echo lang( 'search_fm_section_ph' ); ?>" value="<?php echo ( isset ( $querySearch['tc_section'] ) ? $querySearch[ 'tc_section' ] : '' )?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_township_lbl' ); ?></label>
														<input class="form-control" name="tc.township" type="text" placeholder="<?php echo lang( 'search_fm_township_ph' ); ?>" value="<?php echo ( isset ( $querySearch['tc_township'] ) ? $querySearch[ 'tc_township' ] : '' )?>">
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
														<input class="form-control" name="j.easting" type="text" placeholder="<?php echo lang( 'search_fm_easting_ph' ); ?>" value="<?php echo ( isset ( $querySearch['j_easting'] ) ? $querySearch[ 'j_easting' ] : '' )?>">
													</div>
												</div>
								
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_northing_lbl' ); ?></label>
														<input class="form-control" name="j.northing" type="text" placeholder="<?php echo lang( 'search_fm_northing_ph' ); ?>" value="<?php echo ( isset ( $querySearch['j_northing'] ) ? $querySearch[ 'j_northing' ] : '' )?>" >
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_zone_lbl' ); ?></label>
														<input class="form-control" name="j.zone" id="zone" type="text" placeholder="<?php echo lang( 'search_fm_zone_ph' ); ?>" value="<?php echo ( isset ( $querySearch['j_zone'] ) ? $querySearch[ 'j_zone' ] : '' )?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php echo lang( 'search_fm_distance_lbl' ); ?></label>
														<input class="form-control" name="distance" id="distance" type="text" placeholder="<?php echo lang( 'search_fm_distance_ph' ); ?>" value="<?php echo ( isset ( $querySearch['distance'] ) ? $querySearch[ 'distance' ] : '' )?>">
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
										</div>

			                  </section>
			      </div>
			   </div>
			   <!-- end: page -->
            </section><?php // tag opened in _templates/page-header.php ?>

            <!-- end: section role="main" class="content-body" -->
            