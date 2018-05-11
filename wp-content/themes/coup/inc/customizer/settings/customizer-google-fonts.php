<?php
/**
 * Customize Google Fonts
 *
 * @package coup
 */

/* --- Section --- */

// Front Page Slider Section
$wp_customize->add_section( 'google_fonts_section', array(
	'title'       => esc_html__( 'Font Settings', 'coup-shop' ),
	'description' => esc_html__( 'Choose fonts for your content', 'coup-shop' ),
	'priority'    => 200
) );

/* --- Settings --- */
$wp_customize->add_setting( 'headings_font_family', array(
	'default'           => 'default',
	'sanitize_callback' => 'coup_sanitize_select'
) );

$wp_customize->add_control( 'headings_font_family', array(
	'type'     => 'select',
	'label'    => esc_html__( 'Headings Font Family', 'coup-shop' ),
	'section'  => 'google_fonts_section',
	'priority' => 0,
	'choices'  => coup_list_google_fonts()
) );

/* font weight */

$wp_customize->add_setting( 'headings_font_weight', array(
	'default'           => 'normal',
	'sanitize_callback' => 'coup_sanitize_select'
) );

$wp_customize->add_control( 'headings_font_weight', array(
	'type'     => 'select',
	'label'    => esc_html__( 'Headings Font Weight', 'coup-shop' ),
	'section'  => 'google_fonts_section',
	'priority' => 1,
	'choices'  => array(
		'default' => 'Default'
	)
) );

// Divider
$wp_customize->add_setting( 'coup_second_cta_divider0', array(
    'sanitize_callback' => 'coup_sanitize_text',
) );

// Divider
$wp_customize->add_control( new WP_Customize_Divider_Control(
    $wp_customize,
    'coup_second_cta_divider0',
        array(
            'section'  => 'google_fonts_section',
            'priority' => 2
        )
) );

// Paragraphs font family
$wp_customize->add_setting( 'paragraphs_font_family', array(
	'default'           => 'default',
	'sanitize_callback' => 'coup_sanitize_select'
) );

$wp_customize->add_control( 'paragraphs_font_family', array(
	'type'     => 'select',
	'label'    => esc_html__( 'Paragraphs Font Family', 'coup-shop' ),
	'section'  => 'google_fonts_section',
	'priority' => 3,
	'choices'  => coup_list_google_fonts()
) );

/* font weight */

$wp_customize->add_setting( 'paragraphs_font_weight', array(
	'default'           => 'normal',
	'sanitize_callback' => 'coup_sanitize_select'
) );

$wp_customize->add_control( 'paragraphs_font_weight', array(
	'type'     => 'select',
	'label'    => esc_html__( 'Paragraph and content Font Weight', 'coup-shop' ),
	'section'  => 'google_fonts_section',
	'priority' => 4,
	'choices'  => array(
		'default' => 'Default'
	)
) );

// Divider
$wp_customize->add_setting( 'coup_second_cta_divider1', array(
    'sanitize_callback' => 'coup_sanitize_text',
) );

// Divider
$wp_customize->add_control( new WP_Customize_Divider_Control(
    $wp_customize,
    'coup_second_cta_divider1',
        array(
            'section'  => 'google_fonts_section',
            'priority' => 5
        )
) );

// Menu font family
$wp_customize->add_setting( 'navigation_font_family', array(
	'default'           => 'default',
	'sanitize_callback' => 'coup_sanitize_select'
) );

$wp_customize->add_control( 'navigation_font_family', array(
	'type'     => 'select',
	'label'    => esc_html__( 'Navigation Font Family', 'coup-shop' ),
	'section'  => 'google_fonts_section',
	'priority' => 6,
	'choices'  => coup_list_google_fonts()
) );

/* font weight */

$wp_customize->add_setting( 'navigation_font_weight', array(
	'default'           => 'normal',
	'sanitize_callback' => 'coup_sanitize_select'
) );

$wp_customize->add_control( 'navigation_font_weight', array(
	'type'     => 'select',
	'label'    => esc_html__( 'Navigation Font Weight', 'coup-shop' ),
	'section'  => 'google_fonts_section',
	'priority' => 7,
	'choices'  => array(
		'default' => 'Default'
	)
) );

