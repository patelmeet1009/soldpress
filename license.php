 
<?php


// Our Intention here is not create a sophistcard licsening model.
// We a build a number of extention that will help enchance your delivery to end client.
// The plugin still does excatly what it is inteneded for.
		$domain = "www.sanskript.com";
 
		//WP Function Like: get_option('stpopup-license');, Joomla! Function Like: $this->params->get('license');, OR  $_POST['key'];.
		//$licensekey = $_POST['key'];
		$licensekey = get_option('sp-license');
		//Must same with product registered stored on DataBase
		$prod_id = "SOLDPRESS-BETA";

		if (substr($domain, 0, 4) == "www.") { $domain = substr($domain, 4);}
		$userip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
		$validdir = dirname(__FILE__);
		$website = $domain;
		$validdomain = $domain;

		$key_info['key'] = $licensekey;
		$key_info['domain'] = $validdomain;
		$key_info['validip'] = $userip;
		$key_info['validdir'] = $validdir;
		$key_info['product'] = $prod_id;

		$serverurl = "http://www.sanskript.com/"; // Your Blog URL Where WLS Installed
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $serverurl."wp-content/plugins/wp-licensing/auth/verify.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $key_info);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

	$result = json_decode($result, true);
	
	if($result['valid'] == "true"){
		// key is valid so run it
		echo "<p>Your license key is valid,<br>Thank you"; // Place your protected code function here
		
		//If Beta Enable The Beta Flag and Debug Flag
		set_option('sp-license-valid',"asdfhjasldfalsdfj29349023jasldfj");
	} else {
		// key is not valid so stop it
		echo '<div class="updated"><p>Your license key is Invalid! Test of the license server</p></div>';
	}

 ?>