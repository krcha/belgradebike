<?php
/**
 * Customization of theme layout
 *
 * @package coup
 */

/**
 * Section
 */
$wp_customize->add_section( 'theme_settings', array(
    'title'    => esc_html__( 'Theme Options', 'coup-shop' ),
    'priority' => 120,
) );

/**
 * Settings
 */

// Blog layout
$wp_customize->add_setting( 'blog_layout_setting', array(
    'default'           => 'standard-layout',
    'sanitize_callback' => 'coup_sanitize_blog_layout',
) );

$wp_customize->add_control( 'blog_layout_setting', array(
    'label'    => esc_html__( 'Blog layout', 'coup-shop' ),
    'description'=> esc_html__('Standard keeps posts neat and tidy on a strict grid. Shuffle lets posts breathe with random added space between them.', 'coup-shop'),
    'priority' => 3,
    'section'  => 'theme_settings',
    'type'     => 'radio',
    'choices'  => array(
    	'standard-layout' => esc_html__( 'Standard', 'coup-shop' ),
        'shuffle-layout'  => esc_html__( 'Shuffle', 'coup-shop' ),
    ),
) );

// Divider
$wp_customize->add_setting( 'coup_divider1', array(
    'sanitize_callback' => 'veni_sanitize_text',
) );

// Divider
$wp_customize->add_control( new WP_Customize_Divider_Control(
    $wp_customize,
    'coup_divider1',
        array(
            'section'  => 'theme_settings',
            'priority' => 4
        )
) );

// Shop layout
$wp_customize->add_setting( 'shop_layout_setting', array(
    'default'           => 'layout-4',
    'sanitize_callback' => 'coup_sanitize_shop_layout',
) );

$wp_customize->add_control( 'shop_layout_setting', array(
    'label'    => esc_html__( 'Shop layout', 'coup-shop' ),
    'priority' => 5,
    'section'  => 'theme_settings',
    'type'     => 'radio',
    'choices'  => array(
        'layout-5' => esc_html__( '5 Columns', 'coup-shop' ),
        'layout-4' => esc_html__( '4 Columns', 'coup-shop' ),
        'layout-3' => esc_html__( '3 Columns', 'coup-shop' ),
        'layout-2' => esc_html__( '2 Columns', 'coup-shop' ),
    ),
) );

// Display Product Tag as Brand
$wp_customize->add_setting( 'display_brend_setting', array(
    'default'           => 'true',
    'sanitize_callback' => 'coup_sanitize_checkbox',
) );

$wp_customize->add_control( 'display_brend_setting', array(
    'settings' => 'display_brend_setting',
    'label'    => esc_html__( 'Show a product’s tag above its title', 'coup-shop' ),
    'priority' => 6,
    'section'  => 'theme_settings',
    'type'     => 'checkbox',
    'std'      => 1
) );


// Divider
$wp_customize->add_setting( 'coup_divider0', array(
    'sanitize_callback' => 'veni_sanitize_text',
) );

// Divider
$wp_customize->add_control( new WP_Customize_Divider_Control(
    $wp_customize,
    'coup_divider0',
        array(
            'section'  => 'theme_settings',
            'priority' => 8
        )
) );

// Enable Slider
$wp_customize->add_setting( 'front_page_slider_enable', array(
    'default'           => 0,
    'sanitize_callback' => 'coup_sanitize_select'
) );

$wp_customize->add_control( 'front_page_slider_enable', array(
    'settings' => 'front_page_slider_enable',
    'label'    => esc_html__( 'Slider', 'coup-shop' ),
    'description'=> esc_html__('You can display up to 10 featured posts in the slider', 'coup-shop'),
    'section'  => 'theme_settings',
    'priority' => 10,
    'type'     => 'radio',
    'choices'  => array(
        'hide-slider'  => esc_html__( 'Hide slider', 'coup-shop' ),
        'small-slider' => esc_html__( 'Small Slider', 'coup-shop' ),
        'medium-slider' => esc_html__( 'Medium Slider', 'coup-shop' ),
        'fullwidth-slider' => esc_html__( 'Fullwidth Slider', 'coup-shop' ),
    ),
) );


// Add text to header image/video section
$wp_customize->add_setting( 'header_section_text', array(
    'default'           => '',
    'sanitize_callback' => 'wp_kses_post'
) );

$wp_customize->add_control( 'header_section_text', array(
    'settings' => 'header_section_text',
    'label'    => esc_html__( 'Header Text', 'coup' ),
    'description'=> esc_html__( 'Text to show over header image/video section.', 'coup' ),
    'section'  => 'header_image',
    'priority' => 15,
    'type'     => 'textarea',
) );
