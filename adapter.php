<?php

require_once dirname(__FILE__).'/lib/phrets.php';

class soldpress_adapter{

	private $loginURL = 'http://sample.data.crea.ca/Login.svc/Login';
	private $userId = 'CXLHfDVrziCfvwgCuL8nUahC';
	private $pass = 'mFqMsCSPdnb5WO1gpEEtDCHH';
	private $templateLocation = "wp-content/plugins/soldpress/template/";
	private $service;
		
	public function soldpress_adapter() 
	{ 
		$this->service= new phRETS();
		$this->service->SetParam('catch_last_response', true);
		$this->service->SetParam('compression_enabled', true);
		$this->service->SetParam('disable_follow_location', true);

		$cookie_file = 'soldpress';
		@touch(cookie_file);
		if (is_writable(cookie_file)) {
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
	
	public function disconnect() 
	{ 
		$this->service->Disconnect();
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