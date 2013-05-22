<?php



function custom_post_type() {
		$slug = get_option('sc-slug','listing');
		$labels = array(
			'name'                => _x( 'Listings', 'Post Type General Name', 'text_domain' ),
			'singular_name'       => _x( 'Listing', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'           => __( 'Listing', 'text_domain' ),
			'parent_item_colon'   => __( 'Parent Listing', 'text_domain' ),
			'all_items'           => __( 'All Listings', 'text_domain' ),
			'view_item'           => __( 'View Listing', 'text_domain' ),
			'add_new_item'        => __( 'Add New Listing', 'text_domain' ),
			'add_new'             => __( 'New Listing', 'text_domain' ),
			'edit_item'           => __( 'Edit Listing', 'text_domain' ),
			'update_item'         => __( 'Update Listing', 'text_domain' ),
			'search_items'        => __( 'Search listings', 'text_domain' ),
			'not_found'           => __( 'No listing found', 'text_domain' ),
			'not_found_in_trash'  => __( 'No listing found in Trash', 'text_domain' ),
		);

		$rewrite = array(
			'slug'                => $slug,
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);

		$args = array(
			'label'               => __( 'sp_property', 'text_domain' ),
			'description'         => __( 'Property information pages', 'text_domain' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon' 	      => plugins_url( '/images/soldpress-home-admin.png' , __FILE__ ), 
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,	
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);

		register_post_type( 'sp_property', $args );
		flush_rewrite_rules( false );
	}

function listing_meta_boxes() {
		global $post;			
		$custom_field_keys = get_post_custom_keys();
		foreach ( $custom_field_keys as $key => $value ) {
					$valuet = trim($value);
					if ( '_' == $valuet{0} )
						continue;				
					$meta_box_value = get_post_meta($post->ID, $value, true);	
					if($meta_box_value != '')
					{
						echo'<h2>'.$value.'</h2>';
						echo'<input readonly type="text" name="'.$value.'_value" value="'.$meta_box_value.'" size="55" /><br />';
					}
					
		}		
	}

// Hook into the 'init' action
add_action('init', 'custom_post_type', 0 );
add_action("admin_init", "admin_init");
 
function admin_init()
{
// add_meta_box( 'listing_meta_boxes', 'Property Details', 'listing_meta_boxes', 'property', 'normal', 'high' );
}


?>