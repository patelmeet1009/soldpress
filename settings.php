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
        <td><input type="text" class="regular-text" name="sc-username" value="<?php echo get_option('sc-username'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Password</th>
        <td><input type="password" class="regular-text" name="sc-password" value="<?php echo get_option('sc-password'); ?>" /></td>
        </tr>      
        <tr valign="top">
        <th scope="row">Url</th>
        <td><input type="text" class="regular-text" name="sc-url" value="<?php echo get_option('sc-url'); ?>" /></td>
        </tr>
 		<tr valign="top">
        <th scope="row">Template Location</th>
        <td><input type="text" class="regular-text" name="sc-template" value="<?php echo get_option('sc-template'); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>  
</form>

<form method="post" id="test_connection">    
	<?php submit_button('Test Connection', 'secondary', 'test_connection', false); ?> 
</form>
<br><br>
<div>
<img src="<?php echo plugins_url( 'images/soldpress.jpg' , __FILE__ );?>" >
<br>
&copy; 2013 Sanskript Solution, Inc.</div>
<?php 

//$date = new DateTime();
//echo $date->getTimestamp();
	
	if (isset($_POST["test_connection"])) {  
	   		
		$adapter= new soldpress_adapter();
		if($adapter->connect())
		{
			return $adapter-> logserverinfo();		
		}
	} 
}

?>