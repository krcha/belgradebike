<?php
/**
 * Post metaboxes configuration
 *
 * @package  coup
 */


$prefix     = 'coup_';
$meta_boxes = array(
	/* Front Page Meta Boxes */
	array(
		'id'       => 'slide_text',
		'title'    => esc_html__( 'Slide Text And Button', 'coup-shop' ),
		'pages'    => array( 'slide' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Slide text ( content )', 'coup-shop' ),
				'desc' => esc_html__( 'This text will be displayed under the slide headline / title', 'coup-shop' ),
				'id'   => $prefix . 'slide_desc',
				'type' => 'wptextarea',
				'std'  => '',
				'options' => array(
					'rows' => '6',
					'cols' => '12'
				)
			),
			array(
				'name' => esc_html__( 'Headline Font Size', 'coup-shop' ),
				'desc' => esc_html__( 'Enter headline font size in px', 'coup-shop' ),
				'id'   => $prefix . 'slide_headline_size',
				'type' => 'number',
				'std'  => '50'
			),
			array(
				'name' => esc_html__( 'Headline Url', 'coup-shop' ),
				'desc' => esc_html__( 'Paste your headline url here ( e.g. http://www.mylink.com )', 'coup-shop' ),
				'id'   => $prefix . 'slide_link',
				'type' => 'text',
				'std'  => ''
			),
			array(
				'name' => esc_html__( 'Headline Color', 'coup-shop' ),
				'desc' => esc_html__( 'Select Headline color', 'coup-shop' ),
				'id'   => $prefix . 'headline_color',
				'type' => 'colorpicker',
				'std'  => '#000'
			),
			array(
				'name' => esc_html__( 'Headline Hover Color', 'coup-shop' ),
				'desc' => esc_html__( 'Select Headline Hover Color', 'coup-shop' ),
				'id'   => $prefix . 'headline_hover_color',
				'type' => 'colorpicker',
				'std'  => '#fff'
			),
			array(
				'name' => esc_html__( 'Text Color', 'coup-shop' ),
				'desc' => esc_html__( 'Select Text color', 'coup-shop' ),
				'id'   => $prefix . 'text_color',
				'type' => 'colorpicker',
				'std'  => '#000'
			),
			array(
				'name'    => esc_html__( 'Text alignment', 'coup-shop' ),
				'desc'    => esc_html__( 'Choose where to display slider text', 'coup-shop' ),
				'id'      => $prefix . 'slider_text_alignment',
				'type'    => 'select',
				'options' => array(
					'Center' => 'center-txt',
					'Left'   => 'left-txt',
					'Right'  => 'right-txt'
				)
			)
		)
	)
);
