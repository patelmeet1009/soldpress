<?php
/*
Plugin Name: SoldPress
Plugin URI: http://www.sanskript.com/products/soldpress
Description: SoldPress is a WordPress plugin to enable CREA’s members to easily disseminate MLS® listing content on WordPress Sites.
Version: 1.0.0
Author: Amer Gill
Author URI: http://www.sanskript.com
License: GPL2
*/

add_filter( 'query_vars','soldpress_query_vars' );

require_once(dirname(__FILE__)."/adapter.php");
require_once(dirname(__FILE__)."/shortcodes.php");
include_once(dirname(__FILE__).'/settings.php');


function soldpress_query_vars( $vars )
{
    array_push($vars, 'listingid');
    array_push($vars, 'listingkey');
    return $vars;
}

register_deactivation_hook(__FILE__, soldpress_deactivate);

function soldpress_deactivate() 
{
	unregister_setting( 'sc-settings-group', 'sc-username' );
	unregister_setting( 'sc-settings-group', 'sc-password' );
	unregister_setting( 'sc-settings-group', 'sc-url' );
	unregister_setting( 'sc-settings-group', 'sc-template' );
}

?>