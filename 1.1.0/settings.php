<?php

add_action( 'admin_menu', 'soldpress_admin_menu' );

function soldpress_admin_menu() {
	add_options_page( 'SoldPress Plugin Options', 'SoldPress', 'manage_options', 'soldpress', 'soldpress_account_options' );
	add_action( 'admin_init', 'soldpress_register_settings' );
}

function soldpress_register_settings() {
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
<style>

.well {
  min-height: 20px;
  padding: 19px;
  margin-bottom: 20px;
  background-color: #f0f0f0;
  border: 1px solid #e3e3e3;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05
}

.center {
text-align: center;
margin-left: auto;
margin-right: auto;
margin-bottom: auto;
margin-top: auto;
}		  
		  

</style>
<h2>SoldPress Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'sc-settings-group' ); ?>
    <table class="form-table">
		<tr valign="top">
				<th scope="row">Url</th>
					<td>
						<select name="sc-url" class="" id="sc-language">
							<option value="http://data.crea.ca/Login.svc/Login" <?php selected( 'http://data.crea.ca/Login.svc/Login', get_option( 'sc-url' ) ); ?>>Production</option>
							<option value="http://sample.data.crea.ca/Login.svc/Login" <?php selected( 'http://sample.data.crea.ca/Login.svc/Login', get_option( 'sc-url' ) ); ?>>Development</option>
						</select>
					</td>
				</tr>
        <tr valign="top">
        <th scope="row">Username</th>
        <td><input type="text" class="regular-text" name="sc-username" value="<?php echo get_option('sc-username','CXLHfDVrziCfvwgCuL8nUahC'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Password</th>
        <td><input type="password" class="regular-text" name="sc-password" value="<?php echo get_option('sc-password','mFqMsCSPdnb5WO1gpEEtDCHH'); ?>" /></td>
        </tr>      
        
 		<tr valign="top">
        <th scope="row">Template Location</th>
        <td><input type="text" class="regular-text" name="sc-template" value="<?php echo get_option('sc-template','wp-content/plugins/soldpress/template/'); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>  
</form>

<form method="post" id="test_connection">  
				<h3 class="title">Test Connection</h3>
				<p>Once you've saved your settings, click the link below to test your connection.</p>
				<?php submit_button('Test Connection', 'secondary', 'test_connection', false); ?> 
		</form>	
<br><br>
<div class="well center">	
			<img src="<?php echo plugins_url( 'images/soldpress.jpg' , __FILE__ );?>" >
			<p>Looking for an advanced solution. Get the <a href="http://soldpress.sanskript.com" target="_blank">SoldPress Premium Edition</a> .</p>
</div>
<br><br>
<div>
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