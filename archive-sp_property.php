<?php get_header(); ?>
<?php

function wpbeginner_numeric_posts_nav() {

	if( is_singular() )
		return;

	global $wp_query;

	/** Stop execution if there's only 1 page */
	if( $wp_query->max_num_pages <= 1 )
		return;

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	/**	Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;

	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="pagination"><ul>' . "\n";

	/**	Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<li>%s</li>' . "\n", get_previous_posts_link() );

	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) )
			echo '<li><a href="#">…</a></li>';
	}

	/**	Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/**	Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo '<li><a href="#">…</a></li>' . "\n";

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/**	Next Post Link */
	if ( get_next_posts_link() )
		printf( '<li>%s</li>' . "\n", get_next_posts_link() );

	echo '</ul></div>' . "\n";

}

?>
<section>
    <div id="content" role="main">
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
    <?php if ( have_posts() ) : ?>
        <header class="page-header">
            <h1 class="page-title">Property Listings</h1> 
        </header>
 <div class="container">
	<div class="hero-unit">test</div>
	<?php wpbeginner_numeric_posts_nav(); ?>
            <!-- Display table headers -->
            <?php while ( have_posts() ) : the_post(); ?>
<div class="row">
			<div class="span2">
				<?php 
$photos = get_children( array('post_parent' => get_the_ID(), 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
if ($photos) {
		foreach ($photos as $photo) {
			// get the correct image html for the selected size
			echo '<a href="<?php the_permalink(); ?>" class="thumbnail"><image src="' . wp_get_attachment_url($photo->ID,'thumbnail') . '"></a>';
			//$thumbimg = wp_get_attachment_link( $attachment->ID, 'thumbnail-size', true );
			//echo $thumbimg;
			break;
		}
}else{
		echo 'No Image';
}
?>			
			</div>	 			
			<div class="span8">
				<a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?></a></br><?php echo esc_html( get_post_meta( get_the_ID(), 'ListPrice', true ) ); ?><br>
<?php echo esc_html( get_post_meta( get_the_ID(), 'City', true ) ); ?><br>
<?php echo esc_html( get_post_meta( get_the_ID(), 'BedroomsTotal', true ) ); ?> Bedrooms |  
<?php echo esc_html( get_post_meta( get_the_ID(), 'BathroomsTotal', true ) ); ?> Bathrooms | <?php echo esc_html( get_post_meta( get_the_ID(), 'LotSizeArea', true ) ); ?>  
<?php echo esc_html( get_post_meta( get_the_ID(), 'LotSizeUnits', true ) ); ?> 	  
			</div>
		</div>
            <?php endwhile; ?>

		
	
            <!-- Display page navigation -->
        <?php global $wp_query;
        if ( isset( $wp_query->max_num_pages ) && $wp_query->max_num_pages > 1 ) { ?>
            <nav id="<?php echo $nav_id; ?>">
                <div class="nav-previous"><?php next_posts_link( '<span class="meta-nav">&larr;</span> Older listings'); ?></div>
                <div class="nav-next"><?php previous_posts_link( 'Newer listins<span class= "meta-nav">&rarr;</span>' ); ?></div>
            </nav>
        <?php };
    endif; ?>
</div>
    </div>
</section>
<br /><br />
<script src="http://code.jquery.com/jquery.js"></script>
<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
<?php get_footer(); ?>