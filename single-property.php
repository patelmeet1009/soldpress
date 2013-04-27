<?php
 get_header(); 
?>

<div class="sixteen columns outercontainer bigheading">
	<div class="four columns alpha">
		&nbsp;
	</div>
	<div class="twelve columns omega">
		<h2 id="title" class="blogtitle"><?php the_title(); ?></h2>
	</div>
</div>	


<div class="sixteen columns outercontainer" id="content">
	<div class="four columns alpha" id="leftsidebar">
		<?php get_sidebar() ?>
	</div>
	
	<div class="twelve columns omega">	
	
		<?php 
				$arr_sliderimages = get_gallery_images();
			?>	
		<?php if (count($arr_sliderimages) > 0) { ?>
					<div id="sliderimage">
						<?php if(count($arr_sliderimages) > 1) { ?>
							<div class="imagehover"></div>
						<?php } ?>
						
						<?php
							$count = 1;
							$hide = "";
							foreach ($arr_sliderimages as $image) { 
							if($count != 1) {
									$hide = "style='display: none;'";
								}
								$resized = aq_resize($image, 700, 400,true);
								$resizedthumb = aq_resize($image, 62, 62,true);
							?>
							
							<div class="image" <?php echo $hide; ?>>
								<img class="detailpagebigimage" alt="" rel="<?php echo $resizedthumb ?>" src="<?php echo $resized ?>" />
							</div>
							
						<?php
						$count = $count + 1;
						} ?>
					</div><!-- end sliderimage -->
				<?php } ?>
				
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>		
			 <div <?php post_class() ?>    id="post-<?php echo the_ID(); ?>">
		
					<?php if (get_option('wp_showblogimage') == 'show') { ?> 
						<?php
						if ( has_post_thumbnail() ) {
							
							the_post_thumbnail('medium');
						}  ?>
					 <?php } ?>
					
<?php include get_template_directory() . '/includes/postmeta.php'; ?>
<?php the_tags() ?>
					<?php the_content(); ?>						
					<?php if ($post->comment_status == "open") { ?>
					<?php comments_template('', true); ?>
					<?php } ?>
				
			</div>
		<?php endwhile; else: ?>
		 
		
<?php endif; ?>
</div>
</div>

<?php get_footer(); ?>