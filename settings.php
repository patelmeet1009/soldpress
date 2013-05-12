<?php

add_action( 'admin_menu', 'soldpress_admin_menu' );

function soldpress_admin_menu() {
	add_options_page( 'SoldPress Plugin Options', 'SoldPress', 'manage_options', 'soldpress', 'soldpress_account_options' );
	add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
	register_setting( 'sc-settings-credentials', 'sc-username' );
	register_setting( 'sc-settings-credentials', 'sc-password' );
	register_setting( 'sc-settings-credentials', 'sc-url' );
	register_setting( 'sc-settings-credential', 'sc-template' );
	register_setting( 'sc-settings-credential', 'sc-language' );
	register_setting( 'sc-settings-sync', 'sc-sync-enabled' );
}

function soldpress_account_options() {

	if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options';  
?>
	
	<h2><img src="http://soldvancouver.sanskript.com/wp-content/plugins/soldpress/images/soldpress.jpg" width="32px" height="32px">
SoldPress Settings</h2>

	<h2 class="nav-tab-wrapper">  
		<a href="?page=soldpress&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">General Options</a>  
		<a href="?page=soldpress&tab=sync_options" class="nav-tab <?php echo $active_tab == 'sync_options' ? 'nav-tab-active' : ''; ?>">Sync Options</a>  
		<a href="?page=soldpress&tab=debug_options" class="nav-tab <?php echo $active_tab == 'debug_options' ? 'nav-tab-active' : ''; ?>">Debug Options</a>  
		</h2>  
	<?php  

	 if( $active_tab == 'display_options' ) {  ?>
			
			<form method="post" action="options.php">
				<?php settings_fields( 'sc-settings-credentials' ); ?>
				<h3 class="title">Credtentials</h3>
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
				</table>
				<h3 class="title">General</h3>
				<table class="form-table">
					<tr valign="top">
					<th scope="row">Template Location</th>
					<td><input type="text" class="regular-text" name="sc-template" value="<?php echo get_option('sc-template','wp-content/plugins/soldpress/template/'); ?>" /></td>
					</tr>
					<tr valign="top">
					<th scope="row">Language</th>
					<td>
						<select name="sc-language" class="" id="sc-language">
							<option value="en-CA" <?php selected( 'en-CA', get_option( 'sc-language' ) ); ?>>en-CA</option>
							<option value="en-FR" <?php selected( 'en-FR', get_option( 'sc-language' ) ); ?>>en-FR</option>
						</select>
					</tr>
				</table>
				<?php submit_button(); ?>  
			</form>
	<form method="post" id="test_connection">    
			<?php submit_button('Test Connection', 'secondary', 'test_connection', false); ?> 
	</form>	
	<?php } ?>
	<?php if( $active_tab == 'sync_options' ) {  ?>
		<h3 class="title">General</h3>
			<form method="post" action="options.php">
				<?php settings_fields( 'sc-settings-sync' ); ?>
				<table class="form-table">
					<tr valign="top">
					<th scope="row">Sync Enabled</th>
					<td><input name="sc-sync-enabled" id ="sc-sync-enabled" value="1" type="checkbox" <?php checked( '1', get_option( 'sc-sync-enabled' ) ); ?>  /></td>
					</tr>
					<tr valign="top">
					<th scope="row">Last Update</th>
					<td><?php echo date('r', get_option('sc-lastupdate' )) ?></td>
					</tr>
				</table>
				<table>
				
				<?php submit_button(); ?>  
			</form>
		
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
		<?php } else {?>	
			<div id="message" class="updated"><p>No Sync Active</p></div>
		<?php } ?>	
		<h3 class="title">Schedule</h3>
		<table class="widefat">
			<thead>
				<tr class="thead">
					<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries"></th>
					<th>Time</th>
					<th>Function</th>
					<th>Schedule</th>
					<th>Interval</th>
					<th>Options</th>
				</tr>
			</thead>
			<tfoot>
				<tr class="thead">
					<th scope="col" class="check-column"><input type="checkbox" class="check-all-entries"></th>
					<th>Function</th>
					<th>Time</th>
					<th>Schedule</th>
					<th>Interval</th>
					<th>Last Run</th>
					<th>Options</th>
				</tr>
			</tfoot>
			<tbody>
				<tr class="entry-row alternate">
					<th scope="row" class="check-column"><input type="checkbox" name="schedules[]" class="entries" value="1"></th>
					<td>soldpress_listing_sync
						<div class="row-actions" style="margin:0; padding:0;">
								<a href="/wp-admin/admin.php?page=pluginbuddy_backupbuddy-scheduling&amp;edit=1">Edit this schedule</a>
							</div>
					</td>
					<td style="white-space: nowrap;">daily</td>
					<td style="white-space: nowrap;">86400</td>
					<td>Mar 30, 2013 6:27PM</td>
					<td>Mar 30, 2013 6:27PM</td>
					<td>Run Now</td>
				</tr>
				<tr class="entry-row alternate">
					<th scope="row" class="check-column"><input type="checkbox" name="schedules[]" class="entries" value="1"></th>
					<td>soldpress_photo_sync
						<div class="row-actions" style="margin:0; padding:0;">
								<a href="/wp-admin/admin.php?page=pluginbuddy_backupbuddy-scheduling&amp;edit=1">Edit this schedule</a>
							</div>
					</td>
					<td style="white-space: nowrap;">hourly</td>
					<td style="white-space: nowrap;">3600</td>
					<td>Mar 30, 2013 6:27PM</td>
					<td>Mar 30, 2013 6:27PM</td>
					<td>Run Now</td>
				</tr>
			</tbody>
		</table>
		<h3 class="title">Advance</h3>
		<form method="post" id="sync_connection">     
			<?php submit_button('Manual Sync', 'secondary', 'sync', false); ?> 
			<?php submit_button('Clear Listings', 'secondary', 'delete', false); ?> 
		</form>
	<div>
	<?php } ?>
	
	<?php if( $active_tab == 'debug_options' ) {  ?>
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
	<?php } ?>

	<img src="<?php echo plugins_url( 'images/soldpress.jpg' , __FILE__ );?>" >
	<br>
	&copy; 2013 Sanskript Solution, Inc.</div>

		
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