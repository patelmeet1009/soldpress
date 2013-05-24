 
<?php
	
	function get_host() {
		if ($host = $_SERVER['HTTP_X_FORWARDED_HOST'])
		{
			$elements = explode(',', $host);

			$host = trim(end($elements));
		}
		else
		{
			if (!$host = $_SERVER['HTTP_HOST'])
			{
				if (!$host = $_SERVER['SERVER_NAME'])
				{
					$host = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
				}
			}
		}

		// Remove port number from host
		$host = preg_replace('/:\d+$/', '', $host);

		return trim($host);
	}
	
	//function CheckLic(){
	
		$domain = get_host();		
		$licensekey = get_option('sc-license');
		$prod_id = "SOLDPRESS-BETA";
		if (substr($domain, 0, 4) == "www.") { 
			$domain = substr($domain, 4);
		}
		$userip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
		$validdir = dirname(__FILE__);
		$validdomain = $domain;

		$key_info['key'] = $licensekey;
		$key_info['domain'] = $validdomain;
		$key_info['validip'] = $userip;
		$key_info['validdir'] = $validdir;
		$key_info['product'] = $prod_id;

		$content = json_encode($key_info);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://www.sanskript.com/svc/twistoid/1/api/license");
		curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-type: application/json"));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($result, true);
		
		if($json['Valid']){
			update_option('sc-license-type',$json['Status']);
			//$json['Status']
			//$json['Status']
			//$json['Status']
		} else 
		{
			update_option('sc-license-type','Invalid Key');
		}
	//}

 ?>