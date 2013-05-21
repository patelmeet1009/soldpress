<?php
/*
Plugin Name: SoldPress
Plugin URI: http://www.sanskript.com/products/soldpress
Description: SoldPress is a WordPress plugin to enable CREA’s members to easily disseminate MLS® listing content on WordPress Sites.
Version:  0.9.5.3 Beta
Author: Amer Gill
Author URI: http://www.sanskript.com
License: GPL2
*/

	define('SOLDPRESS_VERSION', '0953');
	define('SOLDPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ));
	
	ini_set('max_execution_time', 600);
	ini_set('mysql.connect_timeout', 600);
	ini_set('default_socket_timeout', 600);

	add_filter( 'query_vars','soldpress_query_vars' );
	//add_theme_support('post-thumbnails');

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
		wp_clear_scheduled_hook('soldpress_photo_sync');
	}

	register_activation_hook(__FILE__, 'soldpress_activation');

	add_action('soldpress_listing_sync', 'soldpress_listintgs');
	add_action('soldpress_photo_sync', 'soldpress_photo');

	function soldpress_activation() {
		
		//Perform Version Upgrades
		$currentVerison = get_option( 'sc-version', '0922');
		
		if(currentVerison == 0922)
		{
			//We need to delete the username and password and get the user to reenter because fundlement changes have occurent to the application
			update_option( 'sc-version', SOLDPRESS_VERSION);
			update_option( 'sc-username','' ); 
			update_option( 'sc-password','');
		}
	
		update_option( 'sc-status', '');
		update_option( 'sc-sync-start','' ); 
		update_option( 'sc-sync-end','' ); 
		
		//Deactive Sync When Reactivation Occurs
		update_option( 'sc-sync-enabled',false ); 
		
		//Create SoldPress Directory
		$wp_upload_dir = wp_upload_dir();
		$target = $wp_upload_dir['basedir']. '/soldpress/';
		//	if ( wp_mkdir_p( $target ) === TRUE ) //Future Error
		wp_mkdir_p( $target );
		
		//Remove Old Jobs
		wp_clear_scheduled_hook('soldpress_listing_sync');
		wp_clear_scheduled_hook('soldpress_photo_sync');
		
		//Schedule The Events
		wp_schedule_event( time(), 'daily', 'soldpress_listing_sync');
		wp_schedule_event( time(), 'hourly', 'soldpress_photo_sync');
		
		
	}

	function soldpress_listintgs() {		

		date_default_timezone_set('UTC');
		$lastupdate = get_option('sc-lastupdate');
		
		if(!$lastupdate){
			//Sync Last 90 days
			$days = 90;
			$date = new DateTime();	
			$date->sub(new DateInterval('P' . $days . 'D'));	
			update_option( 'sc-lastupdate', new DateTime());
		}else
		{
			$date = new DateTime();
			//$date = $lastupdate ;			
			//$date->add(new DateInterval('P' . 1 . 'D'));
			update_option( 'sc-lastupdate', $date);
		}

		//TODO:Do a future check/ if the date time is before now don't do any thing the usemay have reactivate the plugin.
		//We Can Only Check Once Per Hour So Make Sure This Not Call 
		$adapter= new soldpress_adapter();
		
		if($adapter->connect())
		{
			$loginURL = get_option("sc-url","http://sample.data.crea.ca/Login.svc/Login");
			if($loginURL == "http://sample.data.crea.ca/Login.svc/Login"){
				return $adapter-> sync_residentialproperty("LastUpdated=2011-05-08T22:00:17Z");	
			}
			else{
				return $adapter-> sync_residentialproperty("LastUpdated=" . $date->format('Y-m-d'));	
			}
		}
	}

	function soldpress_photo() {
		echo 'soldpress_photo';
		$adapter= new soldpress_adapter();
		if($adapter->connect())
		{
			return $adapter-> sync_pictures();	
		}
	}

	add_filter( 'template_include', 'include_template_function', 1 );

	function include_template_function( $template_path ) {
		if ( get_post_type() == 'sp_property' ) {
			if ( is_single() ) {
				if ( $theme_file = locate_template( array ( 'single-sp_property.php' ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = plugin_dir_path( __FILE__ ) . '/single-sp_property.php';
				}
			}
		elseif ( is_archive() ) {
				if ( $theme_file = locate_template( array ( 'archive-sp_property.php' ) ) ) {
					$template_path = $theme_file;
				} else { $template_path = plugin_dir_path( __FILE__ ) . '/archive-sp_property.php';
	 
				}
			}
		}
    return $template_path;
}

	add_action( 'in_admin_footer', 'admin_footer' );
	function admin_footer() {
		$plugin_data = get_plugin_data( __FILE__ );
		printf('%1$s ' . __("plugin", 'SoldPress') .' | ' . __("Version", 'SoldPress') . ' %2$s | '. __('by', 'SoldPress') . ' %3$s | Not for Distribution<br />', $plugin_data['Title'], $plugin_data['Version'], $plugin_data['Author']);
	}

?>