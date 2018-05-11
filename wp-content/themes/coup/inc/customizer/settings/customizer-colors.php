<?php
/**
 * Customizer Custom Colors
 *
 * Here you can define your own CSS rules
 *
 * @package  coup
 */


/**
 *
 * Settings
 *
 */

/* GENERAL COLORS */

// Main theme color
$wp_customize->add_setting( 'coup_main_color', array(
    'default'           => '#eee',
    'sanitize_callback' => 'coup_sanitize_color'
));

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'coup_main_color',
        array(
            'label'    => esc_html__( 'Theme Color', 'coup-shop' ),
            'section'  => 'coup_colors_section',
            'priority' => 0
        ) )
);

// Body BG color
$wp_customize->add_setting( 'coup_body_bg_color', array(
    'default'           => '#fff',
    'sanitize_callback' => 'coup_sanitize_color'
));

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'coup_body_bg_color',
        array(
            'label'    => esc_html__( 'Background Color', 'coup-shop' ),
            'section'  => 'coup_colors_section',
            'priority' => 0
        ) )
);

// Headings color
$wp_customize->add_setting( 'coup_heading_color', array(
    'default'           => '#000',
    'sanitize_callback' => 'coup_sanitize_color'
));

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'coup_heading_color',
        array(
            'label'    => esc_html__( 'Headings Color', 'coup-shop' ),
            'section'  => 'coup_colors_section',
            'priority' => 1
        ) )
);

// Navigation color
$wp_customize->add_setting( 'coup_navigation_color', array(
    'default'           => '#000',
    'sanitize_callback' => 'coup_sanitize_color'
));

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'coup_navigation_color',
        array(
            'label'    => esc_html__( 'Navigation Color', 'coup-shop' ),
            'section'  => 'coup_colors_section',
            'priority' => 2
        ) )
);

$featured_slider = get_theme_mod( 'front_page_slider_enable', 'hide-slider' );
if ($featured_slider == 'fullwidth-slider') {
    // Fullwidth slider Navigation color
    $wp_customize->add_setting( 'coup_fullwidth_navigation_color', array(
        'default'           => '#000',
        'sanitize_callback' => 'coup_sanitize_color'
    ));

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'coup_fullwidth_navigation_color',
            array(
                'label'    => esc_html__( 'Fullwidth Slider Navigation Color', 'coup-shop' ),
                'section'  => 'coup_colors_section',
                'priority' => 3
            ) )
    );
}

// Paragraph color
$wp_customize->add_setting( 'coup_paragraphs_color', array(
    'default'           => '#000',
    'sanitize_callback' => 'coup_sanitize_color'
));

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'coup_paragraphs_color',
        array(
            'label'    => esc_html__( 'Paragraph / Text Color', 'coup-shop' ),
            'section'  => 'coup_colors_section',
            'priority' => 4
        ) )
);

// Meta Link color
$wp_customize->add_setting( 'coup_meta_link_color', array(
    'default'           => '#000',
    'sanitize_callback' => 'coup_sanitize_color'
));

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'coup_meta_link_color',
        array(
            'label'    => esc_html__( 'Link Color', 'coup-shop' ),
            'section'  => 'coup_colors_section',
            'priority' => 5
        ) )
);

// Selection color
$wp_customize->add_setting( 'coup_selection_color', array(
    'default'           => '#f9ce4e',
    'sanitize_callback' => 'coup_sanitize_color'
));

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'coup_selection_color',
        array(
            'label'    => esc_html__( 'Selection Color', 'coup-shop' ),
            'section'  => 'coup_colors_section',
            'priority' => 6
        ) )
);
