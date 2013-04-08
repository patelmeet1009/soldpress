<?php
class soldpress_Shortcodes {

	static function listing($atts, $content = null, $code = "") {	

		global $wp_query;

		$template = $atts["template"];
	
		$rets = new adapter();

		$ListingKey = $wp_query->query["listingkey"];	
	
    	if($ListingKey == '')
		{
			$ListingKey = $atts["listingkey"];

			if($ListingKey == '')
			{
				return "You Need To Specify A Listing Key.</p>";
			}
        }

		if($rets->Connect())
		{
			return $rets->SearchResidentialProperty("ID=$ListingKey",$template);		
		}
				
		return "";
	}	
}
		add_shortcode("soldpress-listing", array("soldpress_Shortcodes", "listing"));

?>