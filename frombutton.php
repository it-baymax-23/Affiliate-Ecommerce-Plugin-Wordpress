<?php   

/**

	* 	Plugin Name: The Affiliate Dream Plugin

	* 	Description: The Affiliate Dream Plugin Button WP Plugin

	* 	Plugin URI: http://www.tadplugin.com/

	* 	Version: 1.0 

	* 	Author: Hussain Murtaza

	* 	Author URI: mailto:trendpetsproducts@gmail.com

	* 	License: GPLv2

**/  

  

// delete_option('FromButton_plugin_options');  

 

if(realpath(__FILE__) === realpath($_SERVER["SCRIPT_FILENAME"]))

	exit("Do not access this file directly."); 

else{ 

	$plugin_name 	= 'The Affiliate Dream Plugin';

	$plugin_version = '1.0'; 

	require_once(dirname(__FILE__).'/classes/FromButton.class.php');  

	$FromButton = new FromButton($plugin_name, $plugin_version); 
	
	function FromButtonInstall(){  
	update_option('Frombutton_from_style_id', '0');
	update_option('Frombutton_from_text_style', 'From');
	update_option('Frombutton_from_color_style', '#FFF');
	update_option('Frombutton_from_background_style', '#0091C1');
	update_option('Frombutton_from_size_style', '190');
	update_option('Frombutton_from_height_style', '35');
	update_option('Frombutton_font_size_style', '17');
	update_option('frontend_default_button_style', '0');

	} 
	register_activation_hook( __FILE__, 'FromButtonInstall' ); 

}
