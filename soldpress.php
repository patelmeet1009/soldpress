<?php
/*
Plugin Name: SoldPress
Plugin URI: http://www.sanskript.com/products/soldpress
Description: SoldPress is a WordPress plugin to enable CREA’s members to easily disseminate MLS® listing content on WordPress Sites.
Version: 0.9.5 A
Author: Amer Gill
Author URI: http://www.sanskript.com
License: GPL2
*/

add_filter( 'query_vars','soldpress_query_vars' );

require_once(dirname(__FILE__)."/adapter.php");
require_once(dirname(__FILE__)."/shortcodes.php");
include_once(dirname(__FILE__).'/settings.php');
include_once(dirname(__FILE__).'/custom_field_types.php');

function soldpress_query_vars( $vars )
{
    array_push($vars, 'listingid');
    array_push($vars, 'listingkey');
    return $vars;
}

register_deactivation_hook(__FILE__,'deactivate_cron_hook');

function deactivate_cron_hook(){
wp_clear_scheduled_hook('soldpress_listing_sync');
}

/*register_deactivation_hook(__FILE__, soldpress_deactivate);

function soldpress_deactivate() 
{
	unregister_setting( 'sc-settings-group', 'sc-username' );
	unregister_setting( 'sc-settings-group', 'sc-password' );
	unregister_setting( 'sc-settings-group', 'sc-url' );
	unregister_setting( 'sc-settings-group', 'sc-template' );
}*/

register_activation_hook(__FILE__, 'soldpress_activation');

add_action('soldpress_listing_sync', 'soldpress_hourly');

function soldpress_activation() {

	//wp_schedule_event( time(), 'daily', 'soldpress_listing_sync');
	wp_schedule_event( time(), 'hourly', 'soldpress_listing_sync');
}

function soldpress_hourly() {
	// do something every hour
	update_option( 'sc-lastupdate', time() );
	
	$adapter= new soldpress_adapter();
	if($adapter->connect())
	{
		return $adapter-> sync_residentialproperty("LastUpdated=2011-05-08T22:00:17Z","");	//HardCode Time For Testing	
	}

}

add_filter( 'template_include', 'include_template_function', 1 );

function include_template_function( $template_path ) {
    if ( get_post_type() == 'property' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-property.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-property.php';
            }
        }
    }
    return $template_path;
}

?>