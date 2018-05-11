<?php

//Enquing necessary js and css files
function gts_custom_script_init()
{
	
	wp_enqueue_script('jquery');
	
	//Enquing CSS Files
	wp_enqueue_style('grab-gts-custom-catalog-css', WOO_CAT_URL .'assets/css/catalog-style.css');
	
}
	
add_action('wp_enqueue_scripts', 'gts_custom_script_init');
