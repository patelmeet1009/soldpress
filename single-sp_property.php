	<?php if(get_option('sc_debug') == '1'){ ?>   
	<div class="alert alert-warning"><strong>This is a template for a simple listing website. Use it as a starting point to create something more unique </div>
	<?php } ?> 
	<?php  
	include_once(dirname(__FILE__).'/theme.php');
	include_once(dirname(__FILE__).'/general.php');
	?>
	 <?php

		function soldpress_analytics() {
			$s = get_post_meta($post->ID,'dfd_AnalyticsClick',true);
			$s = str_replace("<![CDATA[","",$s);
			$s = str_replace("]]>","",$s);
			echo $s;
		}
		add_action('wp_head', 'soldpress_analytics');

	?>
	<?php get_header(); ?>
	<h2><?php the_title(); ?></h2>	
	<?php include_once(dirname(__FILE__).'/disclaimer.php');?>	
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
		<div class="row">
				<div class="span4"><img src="<?php echo plugins_url( 'images/realtor.jpg' , __FILE__ ); ?>"> <img src="<?php echo plugins_url( 'images/mls.jpg' , __FILE__ ); ?>"> </div>	
				<div class="span4 pull-right">	
				<?php
					
					if(get_option( 'sc-layout-walkscore',false)){				
						 function getWalkScore($lat, $lon, $address) {					 
							  //Call Google To Get Lat and Long
							  $address=urlencode($address);
							  $googleapiurl = "http://maps.googleapis.com/maps/api/geocode/json?address=$address&sensor=false";
							  $geo = @file_get_contents($googleapiurl);								  
							  $result = json_decode($geo, true);
							  if($result['status'] == 'OK'){							   
								  $location = $result['results'][0]['geometry']['location'];
								  $lat = $location['lat'];
								  $lon = $location['lng'];							 
								  $url = "http://api.walkscore.com/score?format=json&address=$address";
								  $url .= '&lat=' . $lat . '&lon=' . $lon . '&wsapikey='. get_option('sc-layout-walkscore',true);
								  $str = @file_get_contents($url); 
								  return $str; 				  
							  }
							  //We Need To Store Lat Long
						 } 
						
						 $lat = $_GET['lat']; 
						 $lon = $_GET['lon']; 
						 $address = stripslashes(get_post_meta($post->ID,'dfd_UnparsedAddress',true) . ', ' . get_post_meta($post->ID,'dfd_City',true) . ', ' . get_post_meta($post->ID,'dfd_StateOrProvince',true). ' ' . get_post_meta($post->ID,'dfd_PostalCode',true));
						 $json = getWalkScore($lat,$lon,$address);						
						 $result = json_decode($json, true);
						 if($result["status"] == '1')
						 {
							$walkscore = '<div id="walkscore-div" class="pull-right"><p><a target="_blank" href="'. $result["ws_link"].'"><img src="'. $result["logo_url"].'"><span class="walkscore-scoretext">'. $result["walkscore"].'</span></a><span id="ws_info"><a href=". $result["more_info_link"]." target="_blank"><img src="'. $result["more_info_icon"].'" width="13" height="13" "=""></a></span></p></div>';
							echo $walkscore;
					 
						}
					}
				 
		?></div>
		</div>			

		
	
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

	<?php
		$s = get_post_meta($post->ID,'dfd_AnalyticsClick',true);
		$s = str_replace("<![CDATA[","",$s);
		$s = str_replace("]]>","",$s);
		echo $s;
		
		$s = get_post_meta($post->ID,'dfd_AnalyticsView',true);
		$s = str_replace("<![CDATA[","",$s);
		$s = str_replace("]]>","",$s);
		echo $s;
	?>

<p><small>“MLS®, REALTOR®, and the associated logos are trademarks of The Canadian Real Estate Association</small> </p> </p>
<p><small>Powered by SoldPress. <!-- ©2013 Sanskript Solutions, Inc. All rights reserved.--> </small></p>
<?php get_footer(); ?>