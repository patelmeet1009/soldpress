<?php

include('logger.php');
require_once dirname(__FILE__).'/lib/phrets.php';

class soldpress_adapter{

	private $loginURL = 'http://sample.data.crea.ca/Login.svc/Login';
	private $userId = 'CXLHfDVrziCfvwgCuL8nUahC';
	private $pass = 'mFqMsCSPdnb5WO1gpEEtDCHH';
	private $templateLocation = "wp-content/plugins/soldpress/template/";
	private $service;
	private $log;
	
	public function soldpress_adapter() 
	{ 
		$this->log = new Logging();
		$wp_upload_dir = wp_upload_dir();
		$this->log->lfile($wp_upload_dir['basedir']. '/soldpress/soldpress-log.txt');
		
		$this->service= new phRETS();
		$this->service->SetParam('catch_last_response', true);
		$this->service->SetParam('compression_enabled', true);
		$this->service->SetParam('disable_follow_location', true);
		$this->service->SetParam('offset_support', true);
		
		$cookie_file = 'soldpress';
		@touch('cookie_file');
		if (is_writable('cookie_file')) {
			$this->service->SetParam('cookie_file', 'soldpress');
		}
		
		$this->service->AddHeader('RETS-Version', 'RETS/1.7.2');
		$this->service->AddHeader('Accept', '/');	
		
		$this->loginURL = get_option("sc-url","http://sample.data.crea.ca/Login.svc/Login");
		$this->userId = get_option("sc-username","CXLHfDVrziCfvwgCuL8nUahC");
		$this->pass= get_option("sc-password","mFqMsCSPdnb5WO1gpEEtDCHH");
		$this->templateLocation = get_option("sc-template","wp-content/plugins/soldpress/template/");
	}
	
	public function connect() 
	{ 
		$connect = $this->service->Connect($this->loginURL, $this->userId, $this->pass);

		if ($connect === true) 
		{
			$this->displaytrace('Connection Successful');
		}
		else 
		{
			$this->displayLog('Connection FAILED');
			if ($error = $this->service->Error()) 
			{
				$this->displayLog('ERROR type ['.$error['type'].'] code ['.$error['code'].'] text ['.$error['text'].']');
			}
			return false;
		}
		return true;
	}
	
	public function disconnect() 
	{ 
		$this->service->Disconnect();
	}	
	
	public function logserverinfo()
	{	
		$this->DisplayHeader('Server Info');
		echo "<br>";
		$this->displaylog('Login: ' . $this->loginURL);
		echo "<br>";
		$this->displaylog('UserId: ' . $this->userId);
		echo "<br>";
		$this->displaylog('Server Details: ' . implode($this->service->GetServerInformation()));
		echo "<br>";
		$this->displaylog('RETS version: ' . $this->service->GetServerVersion());
		echo "<br>";
		$this->displaylog('Firewall: ' . $this->firewalltest());
		echo "<br>";

	}
	
	public function logtypeinfo()
	{	
		$this->displaylog(var_export($this->service->GetMetadataTypes(), true));
		$this->displaylog(var_export($this->service->GetMetadataResources(), true));
		
		$this->displaylog(var_dump($this->service->GetMetadataClasses("Property")));
		$this->displaylog(var_dump($this->service->GetMetadataClasses("Office")));
		$this->displaylog(var_dump($this->service->GetMetadataClasses("Agent")));
		
		$this->displaylog(var_dump($this->service->GetMetadataTable("Property", "Property")));
		$this->displaylog(var_dump($this->service->GetMetadataTable("Office", "Office")));
		$this->displaylog(var_dump($this->service->GetMetadataTable("Agent", "Agent")));
		
		$this->displaylog(var_dump($this->service->GetAllLookupValues("Property")));
		$this->displaylog(var_dump($this->service->GetAllLookupValues("Office")));
	    $this->displaylog(var_dump($this->service->GetAllLookupValues("Agent")));
		
		$this->displaylog(var_dump($this->service->GetMetadataObjects("Property")));
		$this->displaylog(var_dump($this->service->GetMetadataObjects("Office")));
		$this->displaylog(var_dump($this->service->GetMetadataObjects("Agent")));
	
	}
	
	public function sync_residentialproperty($crit)
	{	

		$this->WriteLog('Sync Start');
		global $wpdb;
		$wpdb->query("set wait_timeout = 1200"); //Thank You (johnorourke) http://stackoverflow.com/questions/14782494/keep-losing-the-database-in-wordpress		
		$syncenabled = get_option("sc-sync-enabled",false);
		if($syncenabled != true){
			$this->WriteLog('Sync Disabled');
			return;
		}
		
		update_option( 'sc-status', true );
		update_option( 'sc-soldpress_listing_sync-start',time() ); 
		update_option( 'sc-soldpress_listing_sync-end','' ); 
	
		$culture = get_option("sc-language","en-CA");

		$this->WriteLog('service->Search' . $crit);
	
		//Get As Disconnect Array So We Don't Worry Abour Releasing The Adapter
		$properties = $this->service->Search("Property","Property",$crit,array("Limit" => '100',"Culture" => $culture));	
		$this->WriteLog('Retrieved Results');
		$total = count($properties);
		$this->WriteLog("Retrieved Results Total" .$total );
		
		//Get Disconnect Array of Current Posts
		$posts_array = $wpdb->get_results("select ID,post_name from $wpdb->posts where post_type = 'sp_property'");
		
		//Reset Data
		$count = 0;
		$user_id = get_current_user_id();
		//Loop Data
		foreach ($properties as &$rets) {
			
			//If sample data set we are only going to insert Vancouver Listing.
		
			if($this->loginURL == "http://sample.data.crea.ca/Login.svc/Login"){
				if($rets['City'] != "Vancouver"){
					continue;
				}
			}
			
			$count = $count + 1;
			
			$ListingKey = $rets['ListingKey'];
			$this->WriteLog('$ListingKey' . $ListingKey);
			$post = ''; //Want To Mat use unset
			foreach($posts_array as $struct) {			
				if ($ListingKey == $struct->post_name) {
					$post = $struct;
					break;
				}
			}
	
			$postdate  = DateTime::createFromFormat("d/m/Y H:i:s A", $rets['ModificationTimestamp']);	
			$this->WriteLog($postdate->format('Y-m-d') . " " . $postdate->getTimestamp());
//it's false if an error occurs) but you should also definitely check DateTime::getLastErrors();
			$title = $rets['UnparsedAddress'] .' (' . $rets['ListingId'] .')';
			$content = "";
			
			if($post != '') 
			{				
					
					$post->post_title = $title;
					$post->post_content  = "";
					$post->post_name = $ListingKey;
					$post->post_date = $postdate->format('Y-m-d H:i:s');

					wp_update_post($post);
					$post_id = $post->ID;
					$this->WriteLog('Update Post' . $ListingKey . '-' . $post_id . ' Record -' .$count . ' of ' . $total);	
					
					//$this->WriteLog('Delete PostMeta' . $ListingKey . '-' . $post_id);				
					$deleteQuery = $wpdb->prepare("DELETE FROM $wpdb->postmeta WHERE post_id = %d AND meta_key like %s",$post_id,'dfd_%'); //Delte Only ListingData
					$wpdb->query($deleteQuery);

					//$this->WriteLog('Insert PostMeta' . $ListingKey . '-' . $post_id);
					$meta_values = array();
					
					//For Speed Optimization we Only Update Records That Have Values This Cuts down on the number of quires we will send to the server
					foreach($rets as $key => &$value){
						if($value != ''){
							$meta_values[] = $wpdb->prepare('(%s, %s, %s)', $post_id, 'dfd_'.$key, $value);
						}
					}							
					$values = implode(', ', $meta_values);
					$wpdb->query("INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES $values");		
			}
			else
			{				
				
				
				$post = array(
					  'post_title'    => $title ,
					  'post_content'  => $content,
					  'post_status'   => 'publish',
					  'post_author'   => $user_id,
					  'post_type'   => 'sp_property',
					  'post_name' => $ListingKey,
					  'post_date' => $postdate->format('Y-m-d H:i:s')
				);
			
				$post_id = wp_insert_post( $post, true);

				if (is_wp_error($post_id)) {
						$this->WriteLog(' Insert Post - Error' . $ListingKey . '-' . $post_id . ' Record -' .$count . ' of '. 'User:' . $user_id);
						$errors = $post_id->get_error_messages();
						foreach ($errors as $error) {
							$this->WriteLog( var_dump($error)); 
						}
				}
				else
				{
					$this->WriteLog('Insert Post' . $ListingKey . '-' . $post_id . ' Record -' .$count . ' of ' . $total);	
									
					$meta_values = array();
					foreach($rets as $key => &$value){
						$meta_values[] = $wpdb->prepare('(%s, %s, %s)', $post_id,'dfd_'. $key, $value);
					}							
					$values = implode(', ', $meta_values);				
					$wpdb->query("INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES $values");
				}								
			}

			update_post_meta($post_id,'sc-sync-meta-end', time());			
		}	
		

		$this->WriteLog('End Sync');		
		
		update_option( 'sc-soldpress_listing_sync-end',time() ); 
		update_option( 'sc-status', false ); 
		
		return true;
	}
	
	public function sync_pictures()
	{
		
		global $wpdb;
		$wpdb->query("set wait_timeout = 1200");
		$this->WriteLog('Begin Picture Sync');	
		
		update_option( 'sc-soldpress_photo_sync-start-status', true ); 
		update_option( 'sc-soldpress_photo_sync-start',time() );
		update_option( 'sc-soldpress_photo_sync-end','' );
		//TODO:// we should be able to speed this up by only joining to the key
		$posts_array = $wpdb->get_results("select ID,post_name from $wpdb->posts where post_type = 'sp_property'");
		
		foreach($posts_array as $listing) 
		{		
				$post_id = $listing->ID;
				//Check and see if there if property data needs to be synced
				$meta = get_post_meta( $post_id ,'sc-sync-picture');
			
				$listingKey = $listing->post_name;
				if($listingKey){ //If Someone manually adds a listing the key is wrong
					if($meta)
					{		
						//$this->WriteLog('Photo Record is Synced' .$meta . 'listingkey' .$listingKey );
					}else
					{
						
						$this->WriteLog('Meta' .$meta . 'listingkey' .$listingKey );
						$this->WriteLog('Begin Picture Sync' . $post_id );
						$this->sync_propertyobject($listingKey, 'Photo',$post_id);
						update_post_meta($post_id,'sc-sync-picture', true);	
					
					}				
				}
				
				$metaagent = get_post_meta( $post_id ,'sc-sync-picture-agent',true);
				$metaagent = false;
				if(!$metaagent)
				{
					//List Agent
					
					$agentKey = get_post_meta( $post_id ,'dfd_ListAgentKey',true);	
					//$this->WriteLog('Begin Agent Picture Sync' . $post_id . 'AgentKey' . $agentKey);					
					$this->sync_agentobject($agentKey, 'ThumbnailPhoto',$post_id,'agent');
					
					//Co Agent
					$coagentKey = get_post_meta( $post_id ,'dfd_CoListAgentKey',true);
					if($coagentKey != "")
					{					
					//	$this->WriteLog('Begin CoAgent Picture Sync' . $post_id . 'CoAgentKey' . $coagentKey);					
						$this->sync_agentobject($coagentKey, 'ThumbnailPhoto',$post_id,'coagent');
						update_post_meta($post_id,'sc-sync-picture-agent', true,'coagent');
					}

				}
				
				$metaoffice = get_post_meta( $post_id ,'sc-sync-picture-office',true);
				$metaoffice = false;
				if(!$metaoffice)
				{
					//List Agent
					
					$officeKey = get_post_meta( $post_id ,'dfd_ListOfficeKey',true);	
				//	$this->WriteLog('Begin Office Picture Sync' . $post_id . 'AgentKey' . $officeKey);					
					$this->sync_listingobject($officeKey, 'ThumbnailPhoto',$post_id);
					
				}
		}
		
		update_option( 'sc-soldpress_photo_sync-end',time() );
		update_option( 'sc-soldpress_photo_sync-start-status', false );
		$this->WriteLog('End Picture Sync');	
	}
	
	public function sync_propertyobject($id, $type, $post_id)
	{	
		$args = array(
	   'post_type' => 'attachment',
	   'numberposts' => -1,
	   'post_status' => null,
	   'post_parent' => $post_id
		);

		$attachments = get_posts( $args );
		
		 if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
			   wp_delete_attachment( $attachment->ID, true );
			  }
		 }
	
		$record = $this->service->GetObject("Property", $type, $id);
		$isAttached = false;
		foreach($record as &$image) 
		{
			
			$filename = $image["Content-ID"] .'-' . $type .'-'.$image["Object-ID"]. '.jpg';			
			$wp_upload_dir = wp_upload_dir();
			$filePath = $wp_upload_dir['basedir']. '/soldpress/'.$filename;		  
			file_put_contents($filePath,$image["Data"]); //We Change This In Settings			
			$wp_filetype = wp_check_filetype(basename($filename), null );
			$wp_upload_dir = wp_upload_dir();
				  $attachment = array(
					 'guid' => $wp_upload_dir['url'] . '/soldpress/' . basename( $filename ), 
					 'post_mime_type' => $wp_filetype['type'],
					 'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
					 'post_content' => '',
					 'post_status' => 'inherit'
				  );
				  
			$attach_id = wp_insert_attachment( $attachment, '/soldpress/'. $filename, $post_id );
			require_once(ABSPATH . 'wp-admin/includes/image.php');				
			//Attach The First Object	
			if(!$isAttached)
			{			
				$isAttached = true;
				$attach_data = wp_generate_attachment_metadata( $attach_id, '/soldpress/' . $filename );
				wp_update_attachment_metadata( $attach_id, $attach_data );
			}
 		}	
	}	
	
	public function sync_agentobject($id, $type, $post_id,$metatype)
	{	
		$record = $this->service->GetObject("Agent", $type, $id);
		foreach($record as &$image) 
		{	
			$filename = $id .'-agent-' . $type . '.jpg';				
			$wp_upload_dir = wp_upload_dir();
			$filePath = $wp_upload_dir['basedir']. '/soldpress/'.$filename;		
			file_put_contents($filePath,$image["Data"]); //We Change This In Settings
			update_post_meta($post_id,'sc-sync-picture-'.$metatype.'-file', $filename);		
 		}	
		
		return true;
	}
	
	public function sync_listingobject($id, $type, $post_id)
	{	
		$record = $this->service->GetObject("Office", $type, $id);
		foreach($record as &$image) 
		{	
			$filename = $id .'-listing-' . $type . '.jpg';				
			$wp_upload_dir = wp_upload_dir();
			$filePath = $wp_upload_dir['basedir']. '/soldpress/'.$filename;		
			file_put_contents($filePath,$image["Data"]); //We Change This In Settings
			update_post_meta($post_id,'sc-sync-picture-office-file', $filename);					
 		}	
		
		return true;
	}
	
	public function searchresidentialproperty($crit, $template, $culture)
	{	
		$render = 'Listing not found.';

		if($culture =='')
		{
			$culture = "en-CA";
		}

		$results = $this->service->SearchQuery("Property","Property",$crit,array("Limit" => 1,"Culture" => $culture));	
		
		while ($rets = $this->service->FetchRow($results )) {

			if($template == ''){
				foreach($rets as $key => &$val) {
					if($val != NULL) {
						$render .= $key . ":" . $val . "<br>" ;
					}
 				}
			}
			else
			{
				$render=file_get_contents($this->templateLocation .$template);
				eval("\$render = \"$render\";");
			
			}
		}

		$this->service->FreeResult($results);

		return $render; 
	}
	
	public function getpropertyobject($id, $type)
	{
		$record = $this->service->GetObject("Property", $type, $id);
		
		//We won't log this due to data size potential (could be a large image)
		//$this->DisplayLog(var_dump($record));		
		
		//$this->debug(false);
	}		
	
	public function debug($logResponse = true)
	{	
		if ($last_request = $this->service->LastRequest()) 
		{
			$this->displaylog('Reply Code '.$last_request['ReplyCode'].' ['.$last_request['ReplyText'].']');
		}
		$this->displaylog('LastRequestURL: '.$this->service->LastRequestURL().PHP_EOL);
		
		if($logResponse)
		{
			$this->displaylog($this->service->GetLastServerResponse());
		}
	}	
	
	private function WriteLog($text) 
	{
		//update_option( 'sc-sync-status',$text ); 
		$this->log->lwrite($text.PHP_EOL);
	}
			
	private function displaylog($text) 
	{
		echo $text ."<br>";
	}
	
	private function displaytrace($text) 
	{
		echo "<!--";
		echo $text . "--->";
	}

	function displayheader($text) 
	{
		echo "<div><h1>";
		echo $text ."</h1>";
	}
	
	function displayfooter() 
	{
		//echo "<br><p><a href='http://sanskript.com'>Powered by Sanskript SoldCity Wordpress Plugin<a></p></div>";
	}	
	
	private function firewalltestconn($hostname, $port = 443) {
	
		$fp = @fsockopen($hostname, $port, $errno, $errstr, 5);

		if (!$fp) {
			echo "Firewall Test: {$hostname}:{$port} FAILED<br>\n";
			return false;
		}
		else {
			@fclose($fp);
			echo "Firewall Test: {$hostname}:{$port} GOOD<br>\n";
			return true;
		}

	}

	function firewalltest(){
	//We are testing against crea and maintaing the integretiy of the phrets file.
	//This function is copied from phrest
		$google = $this->firewalltestconn("google.com", 80);
		$crt80 =  $this->firewalltestconn("data.crea.ca", 80);
		$flexmls80 =  $this->firewalltestconn("sample.data.crea.ca", 80);

		if (!$google && !$crt80 && !$flexmls80 ) {
			echo "Firewall Result: All tests failed.  Possible causes:";
			echo "<ol>";
			echo "<li>Firewall is blocking your outbound connections</li>";
			echo "<li>You aren't connected to the internet</li>";
			echo "</ol>";
			return false;
		}

		if ($google && $crt80  && $flexmls80) {
			echo "Firewall Result: All tests passed.";
			return true;
		}

		if (!$google || !$crt80 || !$flexmls80) {
			echo "Firewall Result: At least one port 80 test failed.  ";
			echo "Likely cause: One of the test servers might be down.";
			return true;
		}

		echo "Firewall Results: Unable to guess the issue.  See individual test results above.";
		return false;
	}
	
}

?>