<?php if(get_option('sc_debug') == '1'){ ?>   
<div class="alert alert-warning"><strong>This is a template for a simple listing website. Use it as a starting point to create something more unique </div>
<?php } ?> 
<?php  
include_once(dirname(__FILE__).'/theme.php');
include_once(dirname(__FILE__).'/general.php');
?>
  
<?php
function soldpress_analytics() {

	/*	wp_enqueue_script(
		'analyticsclick',
		get_post_meta($post->ID,'dfd_AnalyticsClick',true),
		array(''), 
        '1', 
        true);*/
		
	//	get_post_meta($post->ID,'dfd_AnalyticsClick',true);

}

add_action( 'wp_enqueue_scripts', 'soldpress_analytics' ); // wp_enqueue_scripts action hook to link only on the front-end


?>

<?php get_header(); ?>
	<h2><?php the_title(); ?></h2>	
	<div id="sp_disclaimer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="sp_disclaimer" aria-hidden="true">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Terms of Use Agreement</h3>
		  </div>
		  <div class="modal-body">
			<p>Terms of Use Agreement
BY ACCESSING ANY OF THE WEB SITES OPERATED BY THE CANADIAN REAL ESTATE ASSOCIATION, INCLUDING REALTOR.ca, THE USER AGREES TO BE BOUND BY ALL OF THE TERMS FOR USE AND AGREES THAT THESE TERMS CONSTITUTE A BINDING CONTRACT BETWEEN THE USER AND THE CANADIAN REAL ESTATE ASSOCIATION (CREA)

These Terms of Use apply to all users, except to the extent that CREA has developed specific policies for member real estate Boards, Associations and brokers/salespersons who are members of CREA. Those organizations and entities should also refer to the Policies of the MLS® and Technology Council.

Copyright
This database and all materials on this site are protected by copyright laws and are owned by The Canadian Real Estate Association (CREA) or by the member who has supplied the data. Property listings and other data available on this site are intended for the private, non-commercial use by individuals. Any commercial use of the listings or data in whole or in part, directly or indirectly, is specifically forbidden except with the prior written authority of the owner of the copyright.

Users may, subject to these Terms and Conditions, print or otherwise save individual pages for private use. However, property listings and/or data may not be modified or altered in any respect, merged with other data or published in any form, in whole or in part. The prohibited uses include “screen scraping”, “database scraping” and any other activity intended to collect, store, reorganize or manipulate or publish data on the pages produced by, or displayed on the CREA web sites.

Trade Marks
REALTOR®, REALTORS® and the REALTOR® logo are certification marks owned by REALTOR® Canada Inc., a corporation jointly owned by the National Association of REALTORS® and The Canadian Real Estate Association. The REALTOR® trade marks are used to identify real estate services provided by brokers and salespersons who are members of CREA and who accept and respect a strict Code of Ethics, and are required to meet consistent professional standards of business practice which is the consumer's assurance of integrity.

MLS®, Multiple Listing Service®, and the associated logos are all registered certification marks owned by CREA and are used to identify real estate services provided by brokers and salespersons who are members of CREA.

Other trade marks used on the CREA web sites may be owned by real estate boards and other third parties. Nothing contained on this site gives any user the right or license to use any trade mark displayed on this site without the express permission of the owner.

Links
All links to any CREA website must be accompanied by a prominent notice which makes it clear to a browser that the link leads to a website of The Canadian Real Estate Association.

This notice may make reference to the domain name itself (e.g. REALTOR.ca or ICX.CA) or may refer to CREA.
No materials, names or marks may be used with the link to give the erroneous impression to a user that the individual, entity or website is somehow affiliated with CREA or any of CREA’s web sites;
Unless CREA expressly agrees otherwise, all links to any CREA website must connect to the home page of the website;
All links must be displayed in text or by using the graphic buttons used by CREA;The link must result in a new, fully functional, full screen browser window occupied solely by the pages created by the CREA® website.
Framing
Consumers and third parties may not under any circumstances use technology to display the content of CREA's web sites in a frame or in any other manner that is different from how it would appear if a user typed the URL into the browser line.

Liability and Warranty Disclaimer
CREA makes no representations about the suitability of the data or graphics published on this site. Everything on this site is provided "As Is" without warranty of any kind including all implied warranties and conditions of merchantability, fitness for a particular purpose, title and non-infringement. Neither CREA nor any of its suppliers shall be liable for any direct, incidental, consequential, indirect or punitive damages arising out of your access to or use of this site.

Revisions
CREA may at any time revise these Terms of Use by updating this posting. All users of this site are bound by these conditions and should therefore periodically visit this page to review any changes to these requirements.</p>
		  </div>
		  <div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary">Accept</button>
		  </div>
	</div>
	<script>				
			var j = jQuery.noConflict();
			
			j( document ).ready(function() {
				if (j('#sp_disclaimer').length > 0) {
					if (j.cookie('disclaimer_accepted') != 'yes') {
						j('#sp_disclaimer').modal({backdrop:'static',keyboard:false})
						j('#sp_disclaimer').on('hide',function(){
							j.cookie('disclaimer_accepted','yes',{expires:30,path: '/'});
						})
					}			
				}
			});			
	</script>
	<?php 
		$sp_slideshow = array();
		
		$photos = get_children( array('post_parent' => get_the_ID(), 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
		if($photos){
			foreach ($photos as $photo) {
				$sp_slideshowimage = wp_get_attachment_url($photo->ID,'thumbnail') ;
				$sp_slideshow[] = $sp_slideshowimage;
			}
		}						
	?>
	<div class="well2">
		<?php if($sp_slideshow){ ?>
		<div class="cycle-slideshow" data-cycle-fx="carousel" data-cycle-timeout="2000">
			<div class="cycle-prev"></div>
			<div class="cycle-next"></div>
			<?php 
				foreach($sp_slideshow  as &$sp_slideshowimage){
					echo '<image src="' . $sp_slideshowimage. '">';
				}
			?>			
		</div>
		<?php } ?>
		<div class="well3">
			<div class="row">
				<div class="span4">MLS®: <?php echo get_post_meta($post->ID,'dfd_ListingId',true); ?> </div>	
				<div class="span4 pull-right"><span class="pull-right"><strong>For Sale: $<?php echo get_post_meta($post->ID,'dfd_ListPrice',true); ?></strong></span></div>
			</div>					
		</div>		
		<img src="<?php echo plugins_url( 'images/realtor.jpg' , __FILE__ ); ?>"> MLS® 
	</div>	
	<div class="container-fluid">	
		<div class="row-fluid">
			<div class="span8">	
				<div class="well3">									
						<?php
				
				$max_per_row = 2;
				$item_count = 0;
				echo '<table class="table"><caption>' . get_post_meta($post->ID,'dfd_UnparsedAddress',true) . ', ' . get_post_meta($post->ID,'dfd_City',true) . ', ' . get_post_meta($post->ID,'dfd_StateOrProvince',true). ' ' . get_post_meta($post->ID,'dfd_PostalCode',true) . '</caption><tbody>';
				echo '<tr>';
				$array = array("dfd_BathroomsTotal" => "Bathrooms", "dfd_BedroomsTotal" => "Bedrooms", "dfd_PropertyType" => "Property Type","dfd_YearBuilt" => "Built in", "dfd_LotSizeArea" => "LotSize","dfd_BuildingAreaTotal" => "Building Area");
				foreach ($array as $i => $value) {
					if ($item_count == $max_per_row)
					{
						echo '</tr><tr>';
						$item_count = 0;
					}
					$meta = get_post_meta($post->ID,$i,true);
					$meta = trim($meta,",");
									
					if($meta != "0"){
						if($meta != ""){
							if($i == 'dfd_LotSizeArea'){
								$meta =  $meta . ' ' .get_post_meta($post->ID,'dfd_LotSizeUnits',true); 
							}
					
							if($i == 'dfd_BuildingAreaTotal'){
								$meta =  $meta . ' ' .get_post_meta($post->ID,'dfd_BuildingAreaUnits',true); 
							}
							
							$name = $value;
							echo '<td><span class="sp_key">' .$name.'</span><span>' .$meta .'</span></td>';					
							$item_count++;	
						}							
					}
				}
				if ($item_count != $max_per_row )
					{
						if ($item_count != 0)
						{
							echo '<td></td>';
						}
					}
				echo '</tr>';
				echo '</tbody></table>';
				
				?>
				</div>
				<div class="well3">
					<table class="table">
						 <caption>Description</caption>
						 <tbody>
								<tr>
									<td>
										<p class="muted">
											<?php echo get_post_meta($post->ID,'dfd_PublicRemarks',true); ?>
										</p>
									</td>
								</tr>
							</tbody>
					</table>
				</div>							
				<div class="well3">
					<?php
				
				$max_per_row = 2;
				$item_count = 0;
				echo '<table class="table"><caption>Details</caption><tbody>';
				echo '<tr>';
				$array = array("dfd_GarageYN" => "Garage", 
					"dfd_CarportYN" => "Carport",
					"dfd_CarportSpaces" => "Carport Spaces",
					"dfd_CoveredSpaces" => "Coverd Spaces",
					"dfd_AttachedGarageYN" => "Attached Garage",
					"dfd_OpenParkingYN" => "Open Parking",
					"dfd_OpenParkingSpaces" => "Open Parking Spaces",
					"dfd_ParkingTotal" => "Parking Total",
					"dfd_GarageYN" => "Garage",
					"dfd_LotFeatures" => "Features",
					"dfd_WaterfrontYN" => "Waterfront",				
					"dfd_ArchitecturalStyle" => "Architectural Style",
					"dfd_CommunityFeatures" => "Community Features",
					"dfd_ConstructionMaterials" => "Construction Materials",
					"dfd_Fencing" => "Fencing",
					"dfd_FrontageLength" => "Frontage Length",
					"dfd_FrontageType" => "Frontage Type",
					"dfd_GreenBuildingCertification" => "Green Building Certification",
					"dfd_GreenCertificationRating" => "Green CertificationRating",
					"dfd_Roof" => "Roof",
					"dfd_View" => "View",
					"dfd_ViewYN" => "View",
					"dfd_WaterBodyName" => "Water Body Name",
					"dfd_WaterfrontYN" => "Waterfront",
					"dfd_Zoning" => "Zoning");
					
				foreach ($array as $i => $value) {
					if ($item_count == $max_per_row)
					{
						echo '</tr><tr>';
						$item_count = 0;
					}
					$meta = get_post_meta($post->ID,$i,true);
					$meta = trim($meta,",");
					if($meta != "0"){	
							if($meta != ""){
								$name = $value;
								echo '<td><span class="sp_key">' .$name.'</span><span>' .$meta .'</span></td>';					
								$item_count++;	
							}							
						}
				}
				if ($item_count != $max_per_row )
					{
						if ($item_count != 0)
						{
							echo '<td></td>';
						}
					}
				echo '</tr>';
				echo '</tbody></table>';
				
				?>
				</div>
				<div class="well3">
				
				<?php
				
					$max_per_row = 2;
					$item_count = 0;
					echo '<table class="table"><caption>Building</caption><tbody>';
					echo '<tr>';
									
					$array = array("dfd_BathroomsHalf" => "Bathrooms(Half)",
					"dfd_Flooring" => "Flooring",
					"dfd_Cooling" => "Cooling",
					"dfd_CoolingYN" => "CoolingYN",
					"dfd_Heating" => "Heating",
					"dfd_HeatingFuel" => "Heating Fuel", 
					"dfd_FireplaceFuel" => "Fireplace Fuel",
					"dfd_FireplacesTotal" => "Fireplaces",
					"dfd_Levels" => "Levels",
					"dfd_NumberOfUnitsTotal" => "Number Of Units Total",
					"dfd_PoolYN" => "Pool",					
					"dfd_PoolFeatures" => "Pool Features",
					"dfd_Sewer" => "Sewer",	
					"dfd_Stories" => "Stories");
					
					foreach ($array as $i => $value) {
						if ($item_count == $max_per_row)
						{
							echo '</tr><tr>';
							$item_count = 0;
						}
						$meta = get_post_meta($post->ID,$i,true);
						$meta = trim($meta,",");
						
						if($meta != "0"){	
							if($meta != ""){
								$name = $value;
								echo '<td><span class="sp_key">' .$name.'</span><span>' .$meta .'</span></td>';					
								$item_count++;	
							}							
						}
					}
					if ($item_count != $max_per_row )
					{
						if ($item_count != 0)
						{
							echo '<td></td>';
						}
					}
					echo '</tr>';
					echo '</tbody></table>';
					
					?>
				</div>		
				<?php 
					$rooms = array();
					for ($i=1; $i<=20; $i++)
					{	
						if(get_post_meta($post->ID,'dfd_RoomLevel' . $i ,true) != ''){
							$room = array(
							'RoomLevel' =>  get_post_meta($post->ID,'dfd_RoomLevel' . $i ,true),
							'RoomType' => get_post_meta($post->ID,'dfd_RoomType' . $i ,true),
							'RoomDimensions' => get_post_meta($post->ID,'dfd_RoomDimensions' . $i ,true),);
							$rooms[] = $room;
							
						 }
					}
									
					//echo var_dump($rooms);
				?>
				<?php if($rooms){ ?> 
				<div class="well3">			
					<table class="table table-striped table-condensed ">
							 <caption>Rooms</caption>
							 <tbody>
								<tr>
									<th>Level</th>
									<th>Type</th>
									<th>Dimensions</th>
								</tr>		
								
								
								<?php
									foreach($rooms  as &$room){
										     echo "<tr>";
											 echo "<td>" . $room['RoomLevel'] . "</td>";	
											 echo "<td>" . $room['RoomType']. "</td>";	;
											 echo "<td>". $room['RoomDimensions']. "</td>";	
											 echo "</tr>";
									}
								?>
							</tbody>
					</table>	
				</div>
				<?php }?>				
				<script>
					// Enable the visual refresh
					//google.maps.visualRefresh = true;

					var address = '<?php echo get_post_meta($post->ID,'dfd_UnparsedAddress',true); ?> , <?php echo get_post_meta($post->ID,'dfd_StateOrProvince',true); ?> <?php echo get_post_meta($post->ID,'dfd_PostalCode',true); ?>';	
					var map;				
					function initialize() {					
						var geocoder = new google.maps.Geocoder();		
						geocoder.geocode( { 'address' : address }, function( results, status ) {
							if( status == google.maps.GeocoderStatus.OK ) {
								var latlng = results[0].geometry.location;
								var mapOptions = {
									zoom: 15,
									center: latlng,
									mapTypeId: google.maps.MapTypeId.ROADMAP, 
									streetViewControl: true
								};
						  
															
								map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
								
								var marker = new google.maps.Marker({
									position:results[0].geometry.location,								
									map: map,
									scrollwheel: false,
									streetViewControl:true
								});
								
								
								<?php if(get_option( 'sc-layout-streetviewmap',false)){ ?> 
								
									var mapOptionsStreet = {
										zoom: 14,
										center: latlng,
										mapTypeId: google.maps.MapTypeId.ROADMAP, 
										streetViewControl: true
									};
									
									mapstreet = new google.maps.Map(document.getElementById('map-street'), mapOptionsStreet);	
									
									var panoramaOptions = {
										position: results[0].geometry.location,
										  pov: {
											heading: 0,
											pitch: 0,
											zoom: 1
										  },
										visible: true
									};
									
									var panorama = new  google.maps.StreetViewPanorama(document.getElementById("map-street"), panoramaOptions);
									mapstreet.setStreetView(panorama);
									panorama.setVisible(true);
								<?php }; ?>									
							}else{
							//	alert("Geocode was not successful for the following reason: " + status);
							}
						});
					}
					
					google.maps.event.addDomListener(window, 'load', initialize);
				</script>
				<?php if(get_option('sc-layout-ariealmap',true)){ ?> 				
				<table class="table table-striped table-condensed ">
						 <caption>Map</caption>
						 <tbody>
							<tr>
								<td>
									<?php if(get_option('sc-layout-streetviewmap',false)){ ?> 
										<div id="map-street" class="well-map"></div>
									<?php }?>
									
									<div id="map-canvas" class="well-map"></div>	
																
								</td>
							</tr>
						</tbody>
					</table>
				<?php }?>		
			</div>
			<?php if(get_option('sc-layout-agentlisting',true)){ ?> 
			<div class="span4 well2">			
				<!-- Agent --><h3>Agent Details</h3>
				<div class="row-fluid">
					<div class="well3 span12">
						<?php if(get_post_meta($post->ID,'sc-sync-picture-agent',true) != ''){
							if(get_post_meta($post->ID,'sc-sync-picture-agent-file',true) != ''){ ?> 
							<img src="<?php $wp_upload_dir = wp_upload_dir();  echo $wp_upload_dir['baseurl'] .'/soldpress/'. get_post_meta($post->ID,'sc-sync-picture-agent-file',true); ?>">
						<?php }}?>
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
						<?php if(get_post_meta($post->ID,'sc-sync-picture-office',true) != ''){
							if(get_post_meta($post->ID,'sc-sync-picture-office-file',true) != ''){ ?> 	
							<img src="<?php $wp_upload_dir = wp_upload_dir();  echo $wp_upload_dir['baseurl'] .'/soldpress/'. get_post_meta($post->ID,'sc-sync-picture-office-file',true); ?>">
						<?php }}?>
						<address>
						<small><?php echo get_post_meta($post->ID,'dfd_ListOfficeName',true); ?></small></br>
						<?php echo get_post_meta($post->ID,'dfd_ListOfficePhone',true); ?></br>
						<?php echo get_post_meta($post->ID,'dfd_ListOfficeFax',true); ?></br>
						<?php echo get_post_meta($post->ID,'dfd_ListOfficeURL',true); ?></br>
						</address>
					</div>	
				</div>	
				<?php if(get_post_meta($post->ID,'dfd_CoListAgentFullName',true) != ''){ ?>  
				<div class="row-fluid">			
					<div class="well3 span12">	
						<?php if(get_post_meta($post->ID,'sc-sync-picture-agent',true) != ''){
								if(get_post_meta($post->ID,'sc-sync-picture-coagent-file',true) != ''){
							?> 
							<img src="<?php $wp_upload_dir = wp_upload_dir();  echo $wp_upload_dir['baseurl'] .'/soldpress/'. get_post_meta($post->ID,'sc-sync-picture-coagent-file',true); ?>">
						<?php }}?>
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
						<small><?php echo get_post_meta($post->ID,'dfd_CoListOfficeName',true); ?></small></br>
						<?php echo get_post_meta($post->ID,'dfd_CoListOfficePhone',true); ?></br>
						<?php echo get_post_meta($post->ID,'dfd_CoListOfficeURL',true); ?></br>
						</address>
					</div>
									
				</div>
				<?php }?> 
				<div class="row-fluid">			
					<div class="well3 span12">
				<p><small>Data Provided by <?php echo get_post_meta($post->ID,'dfd_ListAOR',true); ?></small></p>
				<p><small>Last Modified<?php echo get_post_meta($post->ID,'dfd_ModificationTimestamp',true); ?></small></p>		
					</div>
				</div>			
			</div>
		<?php }?>			
		</div>				
	</div>

<!-- empty element for pager links -->
<?php //echo get_post_meta($post->ID,'dfd_AnalyticsClick',true); ?>
<?php //echo get_post_meta($post->ID,'dfd_AnalyticsView',true); ?>
<p><small>
©1998-2013 The Canadian Real Estate Association. All rights reserved. MLS®, Multiple Listing Service®, and all related graphics are trademarks of The Canadian Real Estate Association. REALTOR®, REALTORS®, and all related graphics are trademarks of REALTOR® Canada Inc. a corporation owned by The Canadian Real Estate Association and the National Association of REALTORS®.</small> </p>
<p><small>Powered by SoldPress. <!-- ©2013 Sanskript Solutions, Inc. All rights reserved.--> </small></p>
<?php get_footer(); ?>