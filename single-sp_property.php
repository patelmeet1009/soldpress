<?php if(get_option('sp_debug') == '1'){ ?>   
<div class="alert alert-warning"><strong>This is a template for a simple listing website. Use it as a starting point to create something more unique </div>
<?php } ?>   
<?php
function my_scripts_method() {
	wp_enqueue_script('jquery', false, array(), false, true);
	wp_enqueue_script(
		'bootstrap',
		'http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap.min.js',
		array('jquery'), 
        '2.3.1', 
        true);
	wp_enqueue_script(
		'jquery.cycle2',
		'//cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/20130409/jquery.cycle2.min.js',
		array('jquery'), 
        '2', 
        true);

	/*	wp_enqueue_script(
		'analyticsclick',
		get_post_meta($post->ID,'dfd_AnalyticsClick',true),
		array(''), 
        '1', 
        true);*/
		
	//	get_post_meta($post->ID,'dfd_AnalyticsClick',true);

}

add_action( 'wp_enqueue_scripts', 'my_scripts_method' ); // wp_enqueue_scripts action hook to link only on the front-end


function soldpress_styles()  
{ 
  wp_register_style( 'bootstrap-style', 
    'netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css', 
    array(), 
    '2.3.1', 
    'all' );

  // enqueing:
  wp_enqueue_style( 'custom-style' );
}

add_action('wp_enqueue_scripts', 'soldpress_styles');

function sp_copywrite() {
    echo '<p><div class="alert alert-error">Warning. This A Beta Version And Not To Be Used In Production. </div></p>';
}
add_action('wp_footer', 'sp_copywrite');
?>

<?php get_header(); ?>
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
	<style>
		.well2 {
		min-height: 20px;
		padding: 19px;
		margin-bottom: 20px;
		background-color: #f5f5f5;
		border: 1px solid #e3e3e3;
		}
		
		.addressbox {
			padding: 5px;
			background: #F3F3F3;
		}
		
		.sp_key{
			font-weight: bold;
			display: block;
		}
		
		.sp_value{
			display: block;
		}
	</style>
	<h1><?php the_title(); ?></h1>		
		<div class="well2">
			<div class="row">
				<div class="span4">MLS®: <?php echo get_post_meta($post->ID,'dfd_ListingId',true); ?> </div>	
				<div class="span4 pull-right"><span class="pull-right">For Sale: $<?php echo get_post_meta($post->ID,'dfd_ListPrice',true); ?></span></div>
			</div>
		</div>
	<div class="container-fluid">	
		<div class="row-fluid">
				<div class="span8">
							<p>
								<div class="cycle-slideshow" data-cycle-fx="scrollHorz" data-cycle-timeout="0" data-cycle-pager="#adv-custom-pager" data-cycle-pager-template="<a href='#'><img src='{{src}}' width=40 height=40></a>">
									<?php 
										$photos = get_children( array('post_parent' => get_the_ID(), 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
										if($photos){
											foreach ($photos as $photo) {
												echo '<image src="' . wp_get_attachment_url($photo->ID,'thumbnail') . '">';
											}
										}
									?>			
									<div id=adv-custom-pager class="center external"></div>
								</div>
							</p>
							<p>
								<div class="addressbox"><?php echo get_post_meta($post->ID,'dfd_UnparsedAddress',true); ?> <?php echo get_post_meta($post->ID,'dfd_City',true); ?>, <?php echo get_post_meta($post->ID,'dfd_StateOrProvince',true); ?> <?php echo get_post_meta($post->ID,'dfd_PostalCode',true); ?>
								</div>
							</p>
							<p class="muted">
								<?php echo get_post_meta($post->ID,'dfd_PublicRemarks',true); ?>
							</p>
								<table class="table table-striped table-condensed ">
											  <tbody>
												<tr>
													<td class="sp_key">Bathrooms</td>
													<td><?php echo get_post_meta($post->ID,'dfd_BathroomsTotal',true); ?></td>
													<td class="sp_key">Bedrooms</td>
													<td><?php echo get_post_meta($post->ID,'dfd_BedroomsTotal',true); ?></td>			
												</tr>
												<tr>
													<td class="sp_key">Property Type</td>
													<td><?php echo get_post_meta($post->ID,'dfd_PropertyType',true); ?></td>
													<td class="sp_key">Built in</td>
													<td><?php echo get_post_meta($post->ID,'dfd_YearBuilt',true); ?></td>	
												</tr>
												<tr>
													<?php if(get_post_meta($post->ID,'dfd_LotSizeArea',true) != '0'){ ?>   
														<td class="sp_key">LotSize:</td>
														<td><?php echo get_post_meta($post->ID,'dfd_LotSizeArea',true); ?> <?php echo get_post_meta($post->ID,'dfd_LotSizeUnits',true); ?></td>
													<?php } else {?> 
														<td></td>
														<td></td>
													<?php } ?> 
													<td class="sp_key">Building Area</td>
													<td><?php echo get_post_meta($post->ID,'dfd_BuildingAreaTotal',true); ?> <?php echo get_post_meta($post->ID,'dfd_BuildingAreaUnits',true); ?></td>	
												</tr>
											  </tbody>
								</table>
								<table class="table">
									 <caption>Building</caption>
										<tbody>
											<tr>
												<td><span class="sp_key">Bathrooms</span><span><?php echo get_post_meta($post->ID,'dfd_BathroomsTotal',true);?></span></td>
												<td><span class="sp_key">Bedrooms</span><span><?php echo get_post_meta($post->ID,'dfd_BedroomsTotal',true);?></span></td>
											</tr>
											<tr>
												<td><span class="sp_key">Property Type</span><span><?php echo get_post_meta($post->ID,'dfd_PropertyType',true);?></span></td>
												<td><span class="sp_key">Built in</span><span><?php echo get_post_meta($post->ID,'dfd_YearBuilt',true);?></span></td>
											</tr>
											<tr>
												<td><span class="sp_key">LotSize</span><span><?php echo get_post_meta($post->ID,'dfd_LotSizeArea',true); ?> <?php echo get_post_meta($post->ID,'dfd_LotSizeUnits',true); ?></span></td>
												<td><span class="sp_key">Building Area</span><span><?php echo get_post_meta($post->ID,'dfd_BuildingAreaTotal',true); ?> <?php echo get_post_meta($post->ID,'dfd_BuildingAreaUnits',true); ?></span></td>
											</tr>											
										</tbody>
								</table>
								<table class="table">
									 <caption>Building</caption>
										<tbody>
											<tr>
												<td><span class="sp_key">Garage</span><span><?php echo get_post_meta($post->ID,'dfd_GarageYN',true);?></span></td>
												<td><span class="sp_key">Carport</span><span><?php echo get_post_meta($post->ID,'dfd_CarportYN',true);?></span></td>
											</tr>
											<tr>
												<td><span class="sp_key">CoveredSpaces</span><span><?php echo get_post_meta($post->ID,'dfd_CoveredSpaces',true);?></span></td>
												<td><span class="sp_key">Attached Garage</span><span><?php echo get_post_meta($post->ID,'dfd_AttachedGarageYN',true);?></span></td>
											</tr>
											<tr>
												<td><span class="sp_key">Open Parking</span><span><?php echo get_post_meta($post->ID,'dfd_OpenParkingYN',true);?></span></td>
												<td><span class="sp_key">GarageSpaces</span><span><?php echo get_post_meta($post->ID,'dfd_CoveredSpaces',true);?></span></td>
											</tr>											
											<tr>
												<td><span class="sp_key">Lot Features</span><span><?php echo get_post_meta($post->ID,'dfd_LotFeatures',true);?></span></td>
												<td><span class="sp_key"></span><span><?php echo get_post_meta($post->ID,'dfd_Dummy',true);?></span></td>
											</tr>
										</tbody>
								</table>
								<table class="table table-striped table-condensed ">
									 <caption>Building</caption>
										<tbody>
											<tr>
												<td class="sp_key">CoveredSpaces</td>
												<td><?php echo get_post_meta($post->ID,'dfd_CoveredSpaces',true); ?></td>
												<td class="sp_key">GarageSpaces</td>
												<td><?php echo get_post_meta($post->ID,'dfd_GarageSpaces',true); ?></td			
											</tr>
											<tr>
												<td class="sp_key">Open Parking</td>
												<td><?php echo get_post_meta($post->ID,'dfd_OpenParkingYN',true); ?></td>
												<td class="sp_key">GarageSpaces</td>
												<td><?php echo get_post_meta($post->ID,'dfd_GarageSpaces',true); ?></td			
											</tr>
											<tr>
												<td class="sp_key">Lot Features</td>
												<td><?php echo get_post_meta($post->ID,'dfd_LotFeatures',true); ?></td>
												<td class="sp_key"></td>
												<td><?php echo get_post_meta($post->ID,'dfd_Dummy',true); ?></td			
											</tr>
										</tbody>
								</table>
								<table class="table table-striped table-condensed ">
									 <caption>Building</caption>
										<tbody>
											<tr>
												<td class="sp_key">Bathrooms(Half)</td>
												<td><?php echo get_post_meta($post->ID,'dfd_BathroomsHalf',true); ?></td>
												<td class="sp_key">Flooring</td>
												<td><?php echo get_post_meta($post->ID,'dfd_Flooring',true); ?></td>			
											</tr>
											<tr>
												<td class="sp_key">Cooling</td>
												<td><?php echo get_post_meta($post->ID,'dfd_Cooling',true); ?></td>
												<td class="sp_key"></td>
												<td><?php echo get_post_meta($post->ID,'dfd_Dummy',true); ?></td>	
											</tr>
											<tr>
												<td class="sp_key">Heating</td>
												<td><?php echo get_post_meta($post->ID,'dfd_Heating',true); ?></td>	
												<td class="sp_key">Heating Fuel</td>
												<td><?php echo get_post_meta($post->ID,'dfd_HeatingFuel',true); ?></td>														
										
											</tr>
											<tr>
												<td class="sp_key">Fireplace Fuel</td>
												<td><?php echo get_post_meta($post->ID,'dfd_FireplaceFuel',true); ?></td>
												<td class="sp_key">Fireplace Features</td>
												<td><?php echo get_post_meta($post->ID,'dfd_FireplaceFeatures',true); ?></td>	
											</tr>
											<tr>
												<td class="sp_key">Fireplaces</td>
												<td><?php echo get_post_meta($post->ID,'dfd_FireplacesTotal',true); ?></td>	
												<td class="sp_key"></td>
												<td><?php echo get_post_meta($post->ID,'dfd_Dummy',true); ?></td>	
											</tr>	
											
											
										</tbody>
								</table>
								<table class="table table-striped table-condensed ">
									 <caption>Rooms</caption>
									 <tbody>
										<tr>
											<th>Level</th>
											<th>Type</th>
											<th>Dimensions</th>
										</tr>									
										<?php
											for ($i=1; $i<=20; $i++)
											  {	
												if(get_post_meta($post->ID,'dfd_RoomLevel' . $i ,true) != ''){
													 echo "<tr data-dp='".'dfd_RoomLevel' . $i . "'>";
													 echo "<td>" . get_post_meta($post->ID,'dfd_RoomLevel' . $i ,true) . "</td>";	
													 echo "<td>" . get_post_meta($post->ID,'dfd_RoomType' . $i ,true) . "</td>";
													 echo "<td>" . get_post_meta($post->ID,'dfd_RoomDimensions' . $i ,true) . "</td>";
													 echo "</tr>";
												 }
											  }
											?>
									</tbody>
								</table>
							<p>
								<?php echo get_post_meta($post->ID,'dfd_ListAOR',true); ?> <?php echo get_post_meta($post->ID,'dfd_ModificationTimestamp',true); ?> 
							</p>
				</div>
				<div class="span4">
					<!-- Agent --><h3>Agent Details</h3>
					<div class="row-fluid">
						<div class="span12">
							<address>
							  <strong><?php echo get_post_meta($post->ID,'dfd_ListAgentFullName',true); ?></strong><br>
							<?php echo get_post_meta($post->ID,'dfd_ListAgentDesignation',true); ?><br>
							<?php if(get_post_meta($post->ID,'dfd_ListAgentOfficePhone',true) != ''){ ?>  	
								<abbr title="Phone">O:</abbr> <?php echo get_post_meta($post->ID,'dfd_ListAgentOfficePhone',true); ?></br>
							<?php }?> 
							<?php if(get_post_meta($post->ID,'dfd_ListAgentPager',true) != ''){ ?> 
							  <abbr title="Pager">P:</abbr> <?php echo get_post_meta($post->ID,'dfd_ListAgentPager',true); ?></br>
							<?php }?> 
							<?php if(get_post_meta($post->ID,'dfd_ListAgentFax',true) != ''){ ?>   
							  <abbr title="Fax">F:</abbr> <?php echo get_post_meta($post->ID,'dfd_ListAgentFax',true); ?></br>
							<?php }?> 
							<?php if(get_post_meta($post->ID,'dfd_ListAgentURL',true) != ''){ ?>   
							  <abbr title="Web">W:</abbr> <?php echo get_post_meta($post->ID,'dfd_ListAgentURL',true); ?></br>
							<?php }?> 
							<?php if(get_post_meta($post->ID,'dfd_ListAgentCellPhone',true) != ''){ ?>   
							  <abbr title="Cell">C:</abbr> <?php echo get_post_meta($post->ID,'dfd_ListAgentCellPhone',true); ?></br>
							<?php }?> 
							</address>
							<address>
							<?php echo get_post_meta($post->ID,'dfd_ListOfficeName',true); ?></br>
							<?php echo get_post_meta($post->ID,'dfd_ListOfficePhone',true); ?></br>
							<?php echo get_post_meta($post->ID,'dfd_ListOfficeURL',true); ?></br>
							</address>
						</div>	
					</div>	
					<div class="row-fluid">			
						<div class="span12">	
							<!-- Co Agent -->
							<address>
								<strong><?php echo get_post_meta($post->ID,'dfd_CoListAgentFullName',true); ?></strong><br>
								<?php echo get_post_meta($post->ID,'dfd_CoListAgentDesignation',true); ?><br>
								<?php if(get_post_meta($post->ID,'dfd_CoListAgentOfficePhone',true) != ''){ ?>  	
									<abbr title="Phone">O:</abbr> <?php echo get_post_meta($post->ID,'dfd_CoListAgentOfficePhone',true); ?></br>
								<?php }?> 
								<?php if(get_post_meta($post->ID,'dfd_CoListAgentPager',true) != ''){ ?> 							
									<abbr title="Pager">P:</abbr> <?php echo get_post_meta($post->ID,'dfd_CoListAgentPager',true); ?></br>
								<?php }?> 
								<?php if(get_post_meta($post->ID,'dfd_CoListAgentFax',true) != ''){ ?> 							
									<abbr title="Fax">F:</abbr> <?php echo get_post_meta($post->ID,'dfd_CoListAgentFax',true); ?></br>
								<?php }?> 
								<?php if(get_post_meta($post->ID,'dfd_CoListAgentURL',true) != ''){ ?> 							
									<abbr title="Web">W:</abbr> <?php echo get_post_meta($post->ID,'dfd_CoListAgentURL',true); ?></br>
								<?php }?> 
								<?php if(get_post_meta($post->ID,'dfd_CoListAgentCellPhone',true) != ''){ ?> 				
									<abbr title="Cell">C:</abbr> <?php echo get_post_meta($post->ID,'dfd_CoListAgentCellPhone',true); ?></br>
								<?php }?> 							
							</address>
							<address>
							<?php echo get_post_meta($post->ID,'dfd_CoListOfficeName',true); ?></br>
							<?php echo get_post_meta($post->ID,'dfd_CoListOfficePhone',true); ?></br>
							<?php echo get_post_meta($post->ID,'dfd_CoListOfficeURL',true); ?></br>
							</address>
						</div>	
					</div>
				</div>
		</div>
	</div>
<!-- empty element for pager links -->
<?php //echo get_post_meta($post->ID,'dfd_AnalyticsClick',true); ?>
<?php //echo get_post_meta($post->ID,'dfd_AnalyticsView',true); ?>
<p>
©1998-2013 The Canadian Real Estate Association. All rights reserved. MLS®, Multiple Listing Service®, and all related graphics are trademarks of The Canadian Real Estate Association. REALTOR®, REALTORS®, and all related graphics are trademarks of REALTOR® Canada Inc. a corporation owned by The Canadian Real Estate Association and the National Association of REALTORS®. </p>
<p>©2013 Sanskript Solutions, Inc. All rights reserved. Powered by SoldPress.</p>
<?php get_footer(); ?>