<?php  


function sp_copywrite() {
    echo '<p><div class="alert alert-error">Warning. This A Beta Version And Not To Be Used In Production. (c) 2013 Sanskript Solutions </div></p>';
}
add_action('wp_footer', 'sp_copywrite');

function soldpress_scripts() {
	wp_enqueue_script('jquery', false, array(), false, true);
	wp_enqueue_script(
		'bootstrap',
		 plugins_url( 'lib/bootstrap/js/bootstrap.min.js' , __FILE__ ), 
		array('jquery'), 
        '2.3.1', 
        true);
		
	wp_enqueue_script(
		'jquery.cycle2',
		 plugins_url( 'lib/jquery.cycle2/jquery.cycle2.min.js' , __FILE__ ),
		array('jquery'), 
        '2', 
        true);
}

add_action( 'wp_enqueue_scripts', 'soldpress_scripts' ); 

function soldpress_styles()  
{ 

  wp_register_style( 'bootstrap-style', 
     plugins_url( 'lib/bootstrap/css/bootstrap.min.css' , __FILE__ ), 
    array(), 
    '2.3.1', 
    'all' );
	
  wp_enqueue_style( 'bootstrap-style' );
  
  wp_register_style( 'soldpress-style', 
    plugins_url( 'style/soldpress.css' , __FILE__ ), 
    array(), 
    '0.9.5', 
    'all' );
	
   wp_enqueue_style( 'soldpress-style' );
}
add_action('wp_enqueue_scripts', 'soldpress_styles');

?>
