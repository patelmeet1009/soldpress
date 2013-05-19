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
	
	<h2><img src="<?php echo plugins_url( '/images/soldpress-m.png' , __FILE__ ); ?>"" width="32px" height="32px">SoldPress Settings</h2>

	<h2 class="nav-tab-wrapper">  
		<a href="?page=soldpress&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">General Options</a>  
		<a href="?page=soldpress&tab=sync_options" class="nav-tab <?php echo $active_tab == 'sync_options' ? 'nav-tab-active' : ''; ?>">Sync Options</a> 
		<a href="?page=soldpress&tab=layout_options" class="nav-tab <?php echo $active_tab == 'layout_options' ? 'nav-tab-active' : ''; ?>">Layout</a>  
		<a href="?page=soldpress&tab=about_options" class="nav-tab <?php echo $active_tab == 'about_options' ? 'nav-tab-active' : ''; ?>">About</a>  	
		<a href="?page=soldpress&tab=debug_options" class="nav-tab <?php echo $active_tab == 'debug_options' ? 'nav-tab-active' : ''; ?>">Debug</a>  
		</h2>  
	<?php  

	 if( $active_tab == 'display_options' ) {  ?>
			
			<form method="post" action="options.php">
				<?php settings_fields( 'sc-settings-credentials' ); ?>
				<h3 class="title">Credtentials</h3>
				<table class="form-table">
				   	<tr valign="top">
					<th scope="row">Url</th>
						<td>
							<select name="sc-language" class="" id="sc-language">
								<option value="http://data.crea.ca/Login.svc/Login" <?php selected( 'http://data.crea.ca/Login.svc/Login', get_option( 'sc-url' ) ); ?>Production</option>
								<option value="http://sample.data.crea.ca/Login.svc/Login" <?php selected( 'http://sample.data.crea.ca/Login.svc/Login', get_option( 'sc-url' ) ); ?>>Development</option>
							</select>
						</td>
					</tr>					
					<tr valign="top">
					<th scope="row">Username</th>
					<td><input type="text" class="regular-text" name="sc-username" value="<?php echo get_option('sc-username'); ?>" /></td>
					</tr>
					<tr valign="top">
					<th scope="row">Password</th>
					<td><input type="password" class="regular-text" name="sc-password" value="<?php echo get_option('sc-password'); ?>" /></td>
					</tr>      						
				</table>
				<h3 class="title">General</h3>
				<table class="form-table">
					<!-- <tr valign="top">
					<th scope="row">Template Location</th>
					<td><input type="text" class="regular-text" name="sc-template" value="<?php //echo get_option('sc-template','wp-content/plugins/soldpress/template/'); ?>" /></td>
					</tr>-->
					<tr valign="top">
					<th scope="row">Language</th>
					<td>
						<select name="sc-language" class="" id="sc-language">
							<option value="en-CA" <?php selected( 'en-CA', get_option( 'sc-language' ) ); ?>>en-CA</option>
							<option value="en-FR" <?php selected( 'en-FR', get_option( 'sc-language' ) ); ?>>en-FR</option>
						</select>
					</tr>
					<tr valign="top">
					<th scope="row">Debug Mode</th>
					<td><input name="sc-debug" id ="sc-sync-enabled" value="1" type="checkbox" <?php checked( '1', get_option( 'sc-debug' ) ); ?>  /></td>
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
					<td><?php echo get_option('sc-lastupdate' )->format('Y-m-d H:i:s'); ?></td>
					</tr>
				</table>
				<table>
				
				<?php submit_button(); ?>  
			</form>
		<h3 class="title">Schedule</h3>
		
		<table class="widefat">
			<thead>
				<tr class="thead">
					<th>Job</th>
					<th>Time</th>
					<th>Schedule</th>
					<th>Interval</th>
					<th>Last Start</th>
					<th>Last End</th>
				</tr>
			</thead>
			<tfoot>
				<tr class="thead">
					
					<th>Job</th>
					<th>Time</th>
					<th>Schedule</th>
					<th>Interval</th>
					<th>Last Run</th>
					<th>Options</th>
				</tr>
			</tfoot>
			<tbody>
				<?php $time_slots = _get_cron_array();		
					$tr_class = "";
						foreach ($time_slots as $key => $jobs) {	
							foreach ($jobs as $job => $value) {
								if($job == 'soldpress_photo_sync' || $job == 'soldpress_listing_sync'){
									echo '<tr>';
									//echo '<th scope="row" class="check-column"><input type="checkbox" name="schedules[]" class="entries" value="1"></th>';
									echo '<td><strong>'.$job.'</strong><div class="row-actions" style="margin:0; padding:0;"><a href="/wp-admin/options-general.php?page=soldpress&tab=sync_options&spa=runevt&job='.$job.'">Run Now</a></div></td>';								
									echo '<td>'.date("r", $key).'</td>';							
									$schedule = $value[key($value)];
									echo '<td>'.(isset($schedule["schedule"]) ? $schedule["schedule"] : "").'</td>';
									echo '<td class="aright">'.(isset($schedule["interval"]) ? $schedule["interval"] : "").'</td>';							
									echo '<td class="aright">'. Date('r',get_option('sc-'.$job.'-start' )) ;
									echo '</td>';
									echo '<td class="aright">'. Date('r',get_option('sc-'.$job.'-end' )) ;
									echo '</td>';
									echo '</tr>';
									if ($tr_class == ""){
										$tr_class = "entry-row alternate ";
									}
									else{
										$tr_class = "entry-row";
									}
								}
							}
						}
				?>
			</tbody>
		</table>		
	<?php } ?>
	<?php if( $active_tab == 'layout_options' ) {  ?>
	
				<h3 class="title">Agent</h3>
				<table class="form-table">
					<th scope="row">Display Listing Agent</th>
						<td>
							<input name="sc-layout-agentlisting" id ="sc-layout-agentlisting" value="1" type="checkbox" <?php checked( '1', get_option( 'sc-layout-agentlisting' ) ); ?>  />
						</td>
					</tr>
				</table>
				<h3 class="title">Map</h3>
				<table class="form-table">
					<th scope="row">Display Arieal Map</th>
						<td>
							<input name="sc-layout-ariealmap" id ="sc-layout-ariealmap" value="1" type="checkbox" <?php checked( '1', get_option( 'sc-layout-ariealmap' ) ); ?>  />
						</td>
					</tr>
					<th scope="row">Display StreetView Map</th>
						<td>
							<input name="sc-layout-streetviewmap" id ="sc-layout-streetviewmap" value="1" type="checkbox" <?php checked( '1', get_option( 'sc-layout-streetviewmap' ) ); ?>  />
						</td>
					</tr>
				</table>
				<h3 class="title">Color</h3>
				<table class="form-table">
					<th scope="row">Primary Color</th>
						<td>
							<input name="sc-layout-primarycolor" id ="sc-sync-enabled" value="1" type="checkbox" <?php checked( '1', get_option( 'sc-debug' ) ); ?>  />
						</td>
					</tr>
					<th scope="row">Secondary Color</th>
						<td>
							<input name="sc-layout-secondarycolor" id ="sc-sync-enabled" value="1" type="checkbox" <?php checked( '1', get_option( 'sc-debug' ) ); ?>  />
						</td>
					</tr>
				</table>
				<h3 class="title">Relator(tm) Analyics</h3>
				<table class="form-table">
					<th scope="row">Click Analtics</th>
						<td>
							<input name="sc-layout-anyalticclick" id ="sc-sync-enabled" value="1" type="checkbox" <?php checked( '1', get_option( 'sc-debug' ) ); ?>  />
						</td>
					</tr>
					<th scope="row">View Analtics</th>
						<td>
							<input name="sc-layout-anyalticview" id ="sc-sync-enabled" value="1" type="checkbox" <?php checked( '1', get_option( 'sc-debug' ) ); ?>  />
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>  	
	<?php } ?>
	<?php if( $active_tab == 'about_options' ) {  ?>
		A product of Sanskript Solution, Inc.
		
		Support
		
		Crea 
				
	<?php } ?>
	<?php if( $active_tab == 'debug_options' ) {  ?>
	<h3 class="title">Log File</h3>
	<a target="_blank" href="wp-content/uploads/soldpress/soldpress-log.txt">debug log</a>
	<h3 class="title">Debug</h3>
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
			<h3 class="title">Advance</h3>
					<form method="post" id="sync_connection">     
						<?php submit_button('Manual Sync', 'secondary', 'sync', false); ?> 
						<?php submit_button('Clear Listings', 'secondary', 'delete', false); ?> 
						<?php submit_button('Delete Log', 'secondary', 'deletelog', false); ?> 
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
	<?php } ?>
		<p>
			<div>&copy; 2013 Sanskript Solution, Inc.</div>
		</p>
	
	<?php 
		//Process Get Actions First
		if (isset($_GET["spa"])) {
			$sp_action = $_GET["spa"];
			if ($sp_action != '') {
                    switch ($sp_action) {
                        case "unsevt":
							$job = $_GET['job'];
							wp_clear_scheduled_hook($job);
                            wp_redirect(remove_query_arg(array('job', 'spa'), stripslashes($_SERVER['REQUEST_URI'])));
                            exit();
                            break;
                        case "runevt":
                            $job = $_GET['job'];
                            do_action($job);
                            wp_redirect(remove_query_arg(array('job', 'spa'), stripslashes($_SERVER['REQUEST_URI'])));
                            exit();
                            break;
						 case "testevt":
							$adapter= new soldpress_adapter();
							if($adapter->connect())
							{
								return $adapter-> logserverinfo();		
							}
							exit();
							break;
					}
			}
							
		}
		//Process Data Post Actions
		if (isset($_POST["test_connection"])) {  
				
			$adapter= new soldpress_adapter();
			if($adapter->connect())
			{
				return $adapter-> logserverinfo();		
			}
		} 
		
		if (isset($_POST["sync"])) {  
		
			do_action('soldpress_listing_sync');
		}
		
		if (isset($_POST["deletelog"])) {  	
				$wp_upload_dir = wp_upload_dir();
				unlink($wp_upload_dir['basedir']. '/soldpress/soldpress-log.txt');
		}
		
		if (isset($_POST["delete"])) {  
			$mycustomposts = get_posts( array( 'post_type' => 'sp_property', 'numberposts' => 500) );
				foreach( $mycustomposts as $mypost ) {
					echo $mypost->ID;
				// Delete's each post.
					wp_delete_post( $mypost->ID, true);
				// Set to False if you want to send them to Trash.
			}
		}
	}

?>