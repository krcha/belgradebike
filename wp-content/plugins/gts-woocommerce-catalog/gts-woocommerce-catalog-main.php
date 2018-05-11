<?php
/*
Plugin Name: WooCommerce Catalogue Mode by GTS
Plugin URI: http://www.gtsoftltd.com
Description: This plugin can turn your WooCommerce shop in Catalogue View mode. In this mode, all add to cart buttons will be disappeared and visitors can see products with or without price tag.
Version: 2.0
Author: Global Trend Soft.
Author URI: http://www.gtsoftltd.com
License: Envato License
*/

//Adding some variables
define('WOO_CAT_DIR', plugin_dir_path(__FILE__) );
define('WOO_CAT_URL', plugin_dir_url(__FILE__) );

//Grabbing Settings
$gtsWooCatData = get_option('woocat_opt');

//Mode Value
$catalogModeDisable = 0;

if( isset( $gtsWooCatData['disable_catalog'] ) && $gtsWooCatData['disable_catalog'] != "" ){
	$catalogModeDisable = $gtsWooCatData['disable_catalog'];
}

//Removing Shopping Functionality by remove action

function gts_remove_woocommerce_loop_buttons()
{
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
 
	// Remove add to cart button from the product details page
	remove_action( 'woocommerce_before_add_to_cart_form', 'woocommerce_template_single_product_add_to_cart', 10, 2);
	
	remove_action( 'woo_nav_after', 'wootique_cart_button', 10);
	remove_action( 'woo_nav_after', 'wootique_checkout_button', 20);
 
}

if( $catalogModeDisable != 1 ){
	add_action('init','gts_remove_woocommerce_loop_buttons');
	add_filter('woocommerce_get_price_html', 'gts_alternate_button_for_query');
}

//Applying Filter on Prince HTML

function gts_alternate_button_for_query( $content )
{
    //Grabbing some user defined options, if set
	//Grabbing Settings
	$gtsWooCatData = get_option('woocat_opt');
	
	//Tag Value
	$priceTagValue = 0;

	if( isset( $gtsWooCatData['hide_price'] ) && $gtsWooCatData['hide_price'] != "" ){
		$priceTagValue = $gtsWooCatData['hide_price'];
	}

	//Button Value
	$buttonEnabled = "";
	$buttonText = "";
	$buttonRadius = "0";

	if( isset( $gtsWooCatData['custom_btn']['enabled'] ) && $gtsWooCatData['custom_btn']['enabled'] != "" ){
		$buttonEnabled = $gtsWooCatData['custom_btn']['enabled'];
	}

	if( isset( $gtsWooCatData['custom_btn']['btn_text'] ) && $gtsWooCatData['custom_btn']['btn_text'] != "" ){
		$buttonText = $gtsWooCatData['custom_btn']['btn_text'];
	}

	if( isset( $gtsWooCatData['custom_btn']['btn_radius'] ) && $gtsWooCatData['custom_btn']['btn_radius'] != "" ){
		$buttonRadius = $gtsWooCatData['custom_btn']['btn_radius'];
	}
	
	//Button URL Value
	//Button Value
	$urlEnabled = "";
	$urlText = "";
	$urlTarget = 'target="_self"';

	if( isset( $gtsWooCatData['btn_url_check']['enabled'] ) && $gtsWooCatData['btn_url_check']['enabled'] != "" ){
		$urlEnabled = $gtsWooCatData['btn_url_check']['enabled'];
	}

	if( isset( $gtsWooCatData['btn_url_check']['btn_url'] ) && $gtsWooCatData['btn_url_check']['btn_url'] != "" ){
		$urlText = $gtsWooCatData['btn_url_check']['btn_url'];
	}
	
	if( isset( $gtsWooCatData['btn_url_check']['link_new_val'] ) && $gtsWooCatData['btn_url_check']['link_new_val'] == "on" ){
		$urlTarget = 'target="_blank"';
	}
	
	$urlTargetFinal = "";
	$urlFinal = "";
	if( $urlEnabled == "on" ){
		$urlTargetFinal = $urlTarget;
		$urlFinal = $urlText;
	}
	
	
	//Setting Button Text
	$buttonLabelText = "";
	
	if( isset($buttonText) && $buttonText != "" && $buttonText != NULL )
	{
		$buttonLabelText = $buttonText;
	}
	else
	{
		$buttonLabelText = "Product Info";
	}
	
	//Preparing Output Block
	$actual_content = "";
	$returnData = "";
	
	if( !isset( $priceTagValue ) || $priceTagValue == 0 ) { 
		$actual_content = $content."<br>"; 
	}
	
	if( $buttonEnabled == "on" ){
		$returnData = $actual_content.'
				 <p class="product-enquiry-button-custom">
					<a class="product-enquiry-click click-link-btn" href="'.$urlFinal.'" '.$urlTargetFinal.'>'
						.$buttonLabelText.
					'</a>
				 </p>';
	}
	else{
		$returnData = $actual_content;
	}
				 
	return $returnData;
	

}

//Adding necessary files

require_once('gts-woocommerce-catalog-assets.php');
require_once('admin-page/custom-settings.php');
require_once('gts-woocommerce-catalog-inclusion.php');