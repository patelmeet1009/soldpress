<?php
class soldpress_shortcodes {

	static function listing($atts, $content = null, $code = "") {	

		global $wp_query;

		$template = $atts["template"];
		$culture = $atts["culture "];

		$adapter= new soldpress_adapter();

		$ListingKey = $wp_query->query["listingkey"];	
	
    	if($ListingKey == '')
		{
			$ListingKey = $atts["listingkey"];

			if($ListingKey == '')
			{
				return "You Need To Specify A Listing Key.</p>";
			}
        }

		if($adapter->Connect())
		{
			return $adapter->searchresidentialproperty("ID=$ListingKey",$template,$culture);		
		}
				
		return "";
	}	
}
		add_shortcode("soldpress-listing", array("soldpress_Shortcodes", "listing"));

?>