<?php  

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once(dirname(__FILE__).'/theme.php');
include_once(dirname(__FILE__).'/general.php');

?>

<?php get_header(); ?>
<?php include_once(dirname(__FILE__).'/disclaimer.php');?>
<section>
     <?php if ( have_posts() ) : ?>
		 <div id="content" role="main">
			<header class="page-header">
				<h1 class="page-title">Property Listings</h1> 
			</header>
			<div class="container">
				<?php numeric_posts_nav(); ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="row well3">
							<div class="span2">
								<?php 
									$photos = get_children( array('post_parent' => get_the_ID(), 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
									if ($photos) {
											foreach ($photos as $photo) {
												echo '<a href="' .  get_the_ID() . '" class="thumbnail"><image src="' . wp_get_attachment_url($photo->ID,'thumbnail') . '"></a>';
												break;
											}
									}else{
											echo '<a href="' .  get_the_ID() . '" class="thumbnail"><image src="' . plugins_url( 'images/soldpress.jpg' , __FILE__ ) . '"></a>';
									}
									?>			
							</div>	 			
							<div class="span8">
								<a href="<?php the_permalink(); ?>">								
									<?php the_title(); ?></a></br>$<?php echo esc_html( get_post_meta( get_the_ID(), 'dfd_ListPrice', true ) ); ?><br>
									<?php echo esc_html( get_post_meta( get_the_ID(), 'dfd_City', true )); ?><br>
									<?php echo esc_html( get_post_meta( get_the_ID(), 'dfd_BedroomsTotal', true ) ); ?> Bedrooms |  
									<?php echo esc_html( get_post_meta( get_the_ID(), 'dfd_BathroomsTotal', true ) ); ?> Bathrooms 
									<?php if(get_post_meta($post->ID,'dfd_LotSizeArea',true) != '0'){ ?> 
										| <?php echo esc_html( get_post_meta( get_the_ID(), 'dfd_LotSizeArea', true )); ?> <?php echo esc_html( get_post_meta( get_the_ID(), 'dfd_LotSizeUnits', true ) ); ?>
									<?php } ?> 				
							</div>
					</div>
				<?php endwhile; ?>
				<!-- Display page navigation -->
				<?php numeric_posts_nav(); ?>
			</div>
		</div>
	<?php endif; ?>
</div>
    </div>
</section>
<p><small>“MLS®, REALTOR®, and the associated logos are trademarks of The Canadian Real Estate Association</small> </p> </p>
<p><small>Powered by SoldPress. <!-- ©2013 Sanskript Solutions, Inc. All rights reserved.--> </small></p>
<?php get_footer(); ?>