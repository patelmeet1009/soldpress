<div class="alert alert-warning"><strong>This is a template for a simple listing website. Use it as a starting point to create something more unique </div>

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
</style>
	<h1><?php the_title(); ?></h1>
	
	    <section id="carousel">
                        <div id="myCarousel" class="carousel slide">
                            <ol class="carousel-indicators">
							<?php 
								$photos = get_children( array('post_parent' => get_the_ID(), 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
								if($photos){
									$first = true;
									$count = 0;
									foreach ($photos as $photo) {
										
										if($first){
											echo ' <li data-target="#myCarousel" data-slide-to="0" class="active"></li>';
											$first =false;
										}
										else{
											echo '<li data-target="#myCarousel" data-slide-to="' . $count .'"></li>';						
										}
										$count = $count + 1;
									}
								}
							?>	
                            </ol>
                            <div class="carousel-inner">
                            <?php 
								$photos = get_children( array('post_parent' => get_the_ID(), 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
								if($photos){
									$first = true;
									foreach ($photos as $photo) {
										
										if($first){
										echo '<div class="item active"><image src="' . wp_get_attachment_url($photo->ID,'thumbnail') . '"></div>';
											$first =false;
										}
										else{
										echo '<div class="item"><image src="' . wp_get_attachment_url($photo->ID,'thumbnail') . '"></div>';
										
										}
									}
								}
							?>	
                            </div>
                            <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                            <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
                        </div>
                    </section>
			<div class="well2">
				<div class="row">
					<div class="span4">MLS®: <?php echo get_post_meta($post->ID,'dfd_ListingId',true); ?> </div>	
					<div class="span4 pull-left">For Sale: $<?php echo get_post_meta($post->ID,'dfd_ListPrice',true); ?></div>
				</div>
			</div>
	<div class="container-fluid">
		<div class="alert alert-success">
			<div class="row">
				<div class="span4">MLS®: <?php echo get_post_meta($post->ID,'dfd_ListingId',true); ?> </div>	
				<div class="span4 pull-left">For Sale: $<?php echo get_post_meta($post->ID,'dfd_ListPrice',true); ?></div>
			</div>
		</div>		
		<div class="row-fluid">
				<div class="span8">
							<p>
							<div class="cycle-slideshow" 
								data-cycle-fx=scrollHorz
								data-cycle-timeout=0
								data-cycle-pager="#adv-custom-pager"
								data-cycle-pager-template="<a href='#'><img src='{{src}}' width=40 height=40></a>">
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
								<div class="alert alert-success"><?php echo get_post_meta($post->ID,'dfd_UnparsedAddress',true); ?> <?php echo get_post_meta($post->ID,'dfd_City',true); ?>, <?php echo get_post_meta($post->ID,'dfd_StateOrProvince',true); ?> <?php echo get_post_meta($post->ID,'dfd_PostalCode',true); ?>
								</div>
							</p>
							<p><?php echo get_post_meta($post->ID,'dfd_PublicRemarks',true); ?>
							</p>

							<p>
								<table class="table table-striped table-condensed ">
											  <tbody>
												<tr>
													<td>Bathrooms</td>
													<td><?php echo get_post_meta($post->ID,'dfd_BathroomsTotal',true); ?></td>
													<td>Bedrooms</td>
													<td><?php echo get_post_meta($post->ID,'dfd_BedroomsTotal',true); ?></td>			
												</tr>
												<tr>
													<td>Property Type</td>
													<td><?php echo get_post_meta($post->ID,'dfd_PropertyType',true); ?></td>
													<td>Built in</td>
													<td><?php echo get_post_meta($post->ID,'dfd_YearBuilt',true); ?></td>	
												</tr>
												<tr>
													<td>LotSize:</td>
													<td><?php echo get_post_meta($post->ID,'dfd_LotSizeArea',true); ?> <?php echo get_post_meta($post->ID,'dfd_LotSizeUnits',true); ?></td>
													<td>Building Area</td>
													<td><?php echo get_post_meta($post->ID,'dfd_BuildingAreaTotal',true); ?> <?php echo get_post_meta($post->ID,'dfd_BuildingAreaUnits',true); ?></td>	
												</tr>
											  </tbody>
								</table>
							  </p>  
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
												 echo "<tr data-dp='".'dfd_RoomLevel' . $i . "'>";
												 echo "<td>" . get_post_meta($post->ID,'dfd_RoomLevel' . $i ,true) . "</td>";	
												 echo "<td>" . get_post_meta($post->ID,'dfd_RoomType' . $i ,true) . "</td>";
												 echo "<td>" . get_post_meta($post->ID,'dfd_RoomDimensions' . $i ,true) . "</td>";
												 echo "</tr>";
											  }
											?>
									</tbody>
								</table>
							  </p>   
				</div>
				<div class="span4">
					<!-- Agent --><h3>Agent Details</h3>
					<div class="row-fluid">
						<div class="span6">
							<address>
							  <strong><?php echo get_post_meta($post->ID,'dfd_ListAgentFullName',true); ?></strong><br>
							  <?php echo get_post_meta($post->ID,'dfd_ListAgentDesignation',true); ?><br>

							  <abbr title="Phone">O:</abbr> <?php echo get_post_meta($post->ID,'dfd_ListAgentOfficePhone',true); ?></br>
							  <abbr title="Pager">P:</abbr> <?php echo get_post_meta($post->ID,'dfd_ListAgentPager',true); ?></br>
							  <abbr title="Fax">H:</abbr> <?php echo get_post_meta($post->ID,'dfd_ListAgentFax',true); ?></br>
							  <abbr title="Web">W:</abbr> <?php echo get_post_meta($post->ID,'dfd_ListAgentURL',true); ?></br>
							  <abbr title="Cell">C:</abbr> <?php echo get_post_meta($post->ID,'dfd_ListAgentCellPhone',true); ?></br>
							</address>
							<address>
							<?php echo get_post_meta($post->ID,'dfd_ListOfficeName',true); ?>
							<?php echo get_post_meta($post->ID,'dfd_ListOfficePhone',true); ?>
							<?php echo get_post_meta($post->ID,'dfd_ListOfficeURL',true); ?>
							</address>
						</div>	
					</div>	
					<div class="row-fluid">			
						<div class="span6">	
							<!-- Co Agent -->
							<address>
							  <strong><?php echo get_post_meta($post->ID,'dfd_CoListAgentFullName',true); ?></strong><br>
							  <?php echo get_post_meta($post->ID,'dfd_CoListAgentDesignation',true); ?><br>
							  <abbr title="Phone">O:</abbr> <?php echo get_post_meta($post->ID,'dfd_CoListAgentOfficePhone',true); ?></br>
							  <abbr title="Pager">P:</abbr> <?php echo get_post_meta($post->ID,'dfd_CoListAgentPager',true); ?></br>
							  <abbr title="Fax">H:</abbr> <?php echo get_post_meta($post->ID,'dfd_CoListAgentFax',true); ?></br>
							  <abbr title="Web">W:</abbr> <?php echo get_post_meta($post->ID,'dfd_CoListAgentURL',true); ?></br>
							  <abbr title="Cell">C:</abbr> <?php echo get_post_meta($post->ID,'dfd_CoListAgentCellPhone',true); ?></br>
							</address>
							<address>
							<?php echo get_post_meta($post->ID,'dfd_CoListOfficeName',true); ?>
							<?php echo get_post_meta($post->ID,'dfd_CoListOfficePhone',true); ?>
							<?php echo get_post_meta($post->ID,'dfd_CoListOfficeURL',true); ?>
							</address>
						</div>	
					</div>
				</div>
		</div>
	</div>
<!-- empty element for pager links -->
<?php //echo get_post_meta($post->ID,'dfd_AnalyticsClick',true); ?>
<?php //echo get_post_meta($post->ID,'dfd_AnalyticsView',true); ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/20130409/jquery.cycle2.min.js"></script>


<?php get_footer(); ?>

©1998-2013 The Canadian Real Estate Association. All rights reserved. MLS®, Multiple Listing Service®, and all related graphics are trademarks of The Canadian Real Estate Association. REALTOR®, REALTORS®, and all related graphics are trademarks of REALTOR® Canada Inc. a corporation owned by The Canadian Real Estate Association and the National Association of REALTORS®. 
<br>
©2013 Soldpress Plugin. Beta Not To Be Used In Production.