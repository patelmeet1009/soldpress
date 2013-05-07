<?php

function custom_post_type() {

		$labels = array(
			'name'                => _x( 'Properties', 'Post Type General Name', 'text_domain' ),
			'singular_name'       => _x( 'Property', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'           => __( 'Property', 'text_domain' ),
			'parent_item_colon'   => __( 'Parent Property:', 'text_domain' ),
			'all_items'           => __( 'All Properties', 'text_domain' ),
			'view_item'           => __( 'View Property', 'text_domain' ),
			'add_new_item'        => __( 'Add New Property', 'text_domain' ),
			'add_new'             => __( 'New Property', 'text_domain' ),
			'edit_item'           => __( 'Edit Property', 'text_domain' ),
			'update_item'         => __( 'Update Property', 'text_domain' ),
			'search_items'        => __( 'Search properties', 'text_domain' ),
			'not_found'           => __( 'No properties found', 'text_domain' ),
			'not_found_in_trash'  => __( 'No properties found in Trash', 'text_domain' ),
		);

		$args = array(
			'label'               => __( 'property', 'text_domain' ),
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
			'menu_icon'           => '',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,		
			'capability_type'     => 'page',
		);

		register_post_type( 'property', $args );
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