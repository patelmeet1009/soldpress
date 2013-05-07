<?php

add_action( 'admin_menu', 'soldpress_admin_menu' );

function soldpress_admin_menu() {
	add_options_page( 'SoldPress Plugin Options', 'SoldPress', 'manage_options', 'soldpress', 'soldpress_account_options' );
	add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
	register_setting( 'sc-settings-group', 'sc-username' );
	register_setting( 'sc-settings-group', 'sc-password' );
	register_setting( 'sc-settings-group', 'sc-url' );
	register_setting( 'sc-settings-group', 'sc-template' );
	register_setting( 'sc-settings-group', 'sc-language' );
	register_setting( 'sc-settings-group', 'sc-sync-enabled' );
}

function soldpress_account_options() {
	if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

?>
<h2>SoldPress Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'sc-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Username</th>
        <td><input type="text" class="regular-text" name="sc-username" value="<?php echo get_option('sc-username','CXLHfDVrziCfvwgCuL8nUahC'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Password</th>
        <td><input type="password" class="regular-text" name="sc-password" value="<?php echo get_option('sc-password','mFqMsCSPdnb5WO1gpEEtDCHH'); ?>" /></td>
        </tr>      
        <tr valign="top">
        <th scope="row">Url</th>
        <td><input type="text" class="regular-text" name="sc-url" value="<?php echo get_option('sc-url','http://sample.data.crea.ca/Login.svc/Login'); ?>" /></td>
        </tr>
 		<tr valign="top">
        <th scope="row">Template Location</th>
        <td><input type="text" class="regular-text" name="sc-template" value="<?php echo get_option('sc-template','wp-content/plugins/soldpress/template/'); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Language</th>
        <td><input type="text" class="regular-text" name="sc-template" value="<?php echo get_option('sc-language','en-CA'); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Sync Enabled</th>
        <td><input type="text" class="regular-text" name="sc-template" value="<?php echo get_option('sc-sync-enabled','1'); ?>" /></td>
        </tr>
	<tr valign="top">
        <th scope="row">Last Update</th>
        <td><?php echo date('r', get_option('sc-lastupdate' )) ?></td>
        </tr>
    </table>
    <?php submit_button(); ?>  
</form>

<form method="post" id="test_connection">    
	<?php submit_button('Test Connection', 'secondary', 'test_connection', false); ?> 
	<?php submit_button('Manual Sync', 'secondary', 'sync', false); ?> 
	<?php submit_button('Clear Listings', 'secondary', 'delete', false); ?> 
</form>
<br><br>
	<?php if (get_option('sc-status' ) == true) { ?>
		<div id="message" class="updated"><p>CREA Data Sync Active</p>
		   <ul>
				<li>Status : <?php echo get_option('sc-status' ) ?></li>
				<li>Start : <?php echo get_option('sc-sync-start' ) ?></li>
				<li>End : <?php echo get_option('sc-sync-end' ) ?></li>
				<li>Error : <?php echo get_option('sc-sync-error' ) ?> </li>	  
				<li>Discription : <?php echo get_option('sc-sync-status' ) ?> </li>
			</ul>
		</div>
	<?php } ?>	
<br><br>
<div>
<img src="<?php echo plugins_url( 'images/soldpress.jpg' , __FILE__ );?>" >
<br>
&copy; 2013 Sanskript Solution, Inc.</div>

 <div class = "postbox">
            <div class = "handlediv">
                <br>
            </div>
            <h3 class = "hndle"><span><?php _e('Debug Information', 'bulk-delete'); ?></span></h3>
            <div class = "inside">
            <p>Debug</p>
                <table cellspacing="10">
                    <tr>
                        <th align = "right"><?php _e('Available memory size ', 'bulk-delete');?></th>
                        <td><?php echo ini_get( 'memory_limit' ); ?></td>
                    </tr>
                    <tr>
                        <th align = "right"><?php _e('Script time out ', 'bulk-delete');?></th>
                        <td><?php echo ini_get( 'max_execution_time' ); ?></td>
                    </tr>
                    <tr>
                        <th align = "right"><?php _e('Script input time ', 'bulk-delete'); ?></th>
                        <td><?php echo ini_get( 'max_input_time' ); ?></td>
                    </tr>
  		    <tr>
                        <th align = "right"><?php _e('My Sql Connect Timeou ', 'bulk-delete'); ?></th>
                        <td><?php echo ini_get( 'mysql.connect_timeout' ); ?></td>
                    </tr>

                </table>
            </div>
        </div>
		
<?php 

	add_action( 'in_admin_footer', 'admin_footer' );
    /**
     * Adds Footer links. Based on http://striderweb.com/nerdaphernalia/2008/06/give-your-wordpress-plugin-credit/
     */
    function admin_footer() {
        $plugin_data = get_plugin_data( __FILE__ );
        printf('%1$s ' . __("plugin", 'soldpress') .' | ' . __("Version", 'soldpress') . ' %2$s | '. __('by', 'soldpress') . ' %3$s<br />', $plugin_data['Title'], $plugin_data['Version'], $plugin_data['Author']);
    }
//$date = new DateTime();
//echo $date->getTimestamp();
	
	if (isset($_POST["test_connection"])) {  
	   		
		$adapter= new soldpress_adapter();
		if($adapter->connect())
		{
			return $adapter-> logserverinfo();		
		}
	} 
	
	if (isset($_POST["sync"])) {  
	
		 soldpress_listintgs();
	}
	
	if (isset($_POST["delete"])) {  

	/*	global $wpdb;
		$meta_values = array();
foreach ($metadata as $key => $value) {
	$meta_values[] = $wpdb->prepare('( %s, %s, %s)', $post_id, $key, $value);
    }
	$values = implode(', ', $meta_values);
	$wpdb->query("INSERT INTO $wpdb->postmeta ($post_id, meta_key, meta_value) VALUES $values");
*/

		echo 'delete';
		$mycustomposts = get_posts( array( 'post_type' => 'property', 'numberposts' => 500) );
			foreach( $mycustomposts as $mypost ) {
				echo $mypost->ID;
			// Delete's each post.
				wp_delete_post( $mypost->ID, true);
			// Set to False if you want to send them to Trash.
		}
	}
}

?>