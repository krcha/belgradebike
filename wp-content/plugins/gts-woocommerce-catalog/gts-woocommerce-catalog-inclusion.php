<?php
//Grabbing Settings
$gtsWooCatData = get_option('woocat_opt');


//Adding custom js in the WP_HEAD
function qc_woo_catalog_header() 
{
	//Custom Style Options
	$gtsWooCatData = get_option('woocat_opt');
	
	$btnRadiusValue = 0;
	
	if( isset( $gtsWooCatData['custom_btn']['btn_radius'] ) && $gtsWooCatData['custom_btn']['btn_radius'] != "" ){
		$btnRadiusValue = $gtsWooCatData['custom_btn']['btn_radius'];
	}
	
	$HideFromSidebar = 0;
	
	if( isset( $gtsWooCatData['sidebar_btn_hide'] ) &&  $gtsWooCatData['sidebar_btn_hide'] != "" ){
		$HideFromSidebar = $gtsWooCatData['sidebar_btn_hide'];
	}
	
	$HideFromFullSite = 0;
	
	if( isset( $gtsWooCatData['full_btn_hide'] ) &&  $gtsWooCatData['full_btn_hide'] != "" ){
		$HideFromFullSite = $gtsWooCatData['full_btn_hide'];
	}
	
	//Background Color
	$btnBgClr = "";
	if( isset( $gtsWooCatData['custom_btn_clr']['btn_bg_clr'] ) && $gtsWooCatData['custom_btn_clr']['btn_bg_clr'] != "" ){
		$btnBgClr = $gtsWooCatData['custom_btn_clr']['btn_bg_clr'];
	}

	//Background Color on Hover
	$btnBgClrHover = "";
	if( isset( $gtsWooCatData['custom_btn_clr']['btn_bg_hover'] ) && $gtsWooCatData['custom_btn_clr']['btn_bg_hover'] != "" ){
		$btnBgClrHover = $gtsWooCatData['custom_btn_clr']['btn_bg_hover'];
	}

	//Text Color
	$btnTxtClr = "";
	if( isset( $gtsWooCatData['custom_btn_clr']['btn_text_clr'] ) && $gtsWooCatData['custom_btn_clr']['btn_text_clr'] != "" ){
		$btnTxtClr = $gtsWooCatData['custom_btn_clr']['btn_text_clr'];
	}

	//Text Color on Hover
	$btnTxtClrHover = "";
	if( isset( $gtsWooCatData['custom_btn_clr']['btn_text_clr_hover'] ) && $gtsWooCatData['custom_btn_clr']['btn_text_clr_hover'] != "" ){
		$btnTxtClrHover = $gtsWooCatData['custom_btn_clr']['btn_text_clr_hover'];
	}

	//Border Color
	$btnBorderClr = "";
	if( isset( $gtsWooCatData['custom_btn_clr']['btn_border_clr'] ) && $gtsWooCatData['custom_btn_clr']['btn_border_clr'] != "" ){
		$btnBorderClr = $gtsWooCatData['custom_btn_clr']['btn_border_clr'];
	}

	//Border Color on Hover
	$btnBorderClrHover = "";
	if( isset( $gtsWooCatData['custom_btn_clr']['btn_border_clr_hover'] ) && $gtsWooCatData['custom_btn_clr']['btn_border_clr_hover'] != "" ){
		$btnBorderClrHover = $gtsWooCatData['custom_btn_clr']['btn_border_clr_hover'];
	}

	//Disable Box Shadow
	$btnBoxShadow = false;
	if( isset( $gtsWooCatData['custom_btn_clr']['btn_box_shadow_val'] ) ){
		$btnBoxShadow = $gtsWooCatData['custom_btn_clr']['btn_box_shadow_val'];
	}

	//Disable Text Shadow
	$btnTextShadow = false;
	if( isset( $gtsWooCatData['custom_btn_clr']['btn_txt_shadow_val'] ) ){
		$btnTextShadow = $gtsWooCatData['custom_btn_clr']['btn_txt_shadow_val'];
	}

	//Check Button Color Status
	$checkColorEnabled = false;
	if( isset( $gtsWooCatData['custom_btn_clr']['enabled'] ) ){
		$checkColorEnabled = $gtsWooCatData['custom_btn_clr']['enabled'];
	}

?>
	
	<style type="text/css">
		<?php if( $checkColorEnabled == 'on' ) : ?>
		/*Color Conditions*/
		.product-enquiry-click.click-link-btn {
		   border-radius: <?php echo $btnRadiusValue; ?>px;
		   -moz-border-radius: <?php echo $btnRadiusValue; ?>px;
		   -webkit-border-radius: <?php echo $btnRadiusValue; ?>px;		   
		   <?php if( !empty( $btnBgClr ) && $btnBgClr != "" && $btnBgClr != "#" ) : ?>
		   	background: <?php echo $btnBgClr; ?> !important;
		   <?php endif; ?>
		   <?php if( !empty( $btnTxtClr ) && $btnTxtClr != "" && $btnTxtClr != "#" ) : ?>
		   	color: <?php echo $btnTxtClr; ?> !important;
		   <?php endif; ?>
		   <?php if( !empty( $btnBorderClr ) && $btnBorderClr != "" && $btnBorderClr != "#" ) : ?>
		   	border-color: <?php echo $btnBorderClr; ?> !important;
		   <?php endif; ?>
		   <?php if( $btnTextShadow == 1 ) : ?>
		   	text-shadow: none !important; 
		   <?php endif; ?>
		   <?php if( $btnBoxShadow == 1 ) : ?>
		   	box-shadow: none !important; 
		   <?php endif; ?>
		}

		.product-enquiry-click.click-link-btn:hover {	   
		   <?php if( !empty( $btnBgClrHover ) && $btnBgClrHover != "" && $btnBgClrHover != "#" ) : ?>
		   	background: <?php echo $btnBgClrHover; ?> !important;
		   <?php endif; ?>
		   <?php if( !empty( $btnTxtClrHover ) && $btnTxtClrHover != "" && $btnTxtClrHover != "#" ) : ?>
		   	color: <?php echo $btnTxtClrHover; ?> !important;
		   <?php endif; ?>
		   <?php if( !empty( $btnBorderClrHover ) && $btnBorderClrHover != "" && $btnBorderClrHover != "#" ) : ?>
		   	border-color: <?php echo $btnBorderClrHover; ?> !important;
		   <?php endif; ?>
		}
		<?php endif; ?>
		
		/*Button Hide Conditions*/
		<?php if( $HideFromSidebar == 1 ) : ?>
		.widget .product-enquiry-button-custom, #sidebar .product-enquiry-button-custom, .sidebar  .product-enquiry-button-custom{
			display: none;
		}
		<?php endif; ?>
		<?php if( $HideFromFullSite == 1 ) : ?>
		.product-enquiry-button-custom{
			display: none;
		}
		<?php endif; ?>
	</style>
	
<?php
}

add_action('wp_head', 'qc_woo_catalog_header');