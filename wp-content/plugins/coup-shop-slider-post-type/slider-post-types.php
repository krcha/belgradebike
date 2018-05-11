<?php
/**
 * Register Slider Custom post type
 *
 * slider-post-types.php
 *
 * TK Register custom post types and taxonomies
 *
 * 1. Custom Post Types
 * 2. Custom Taxonomies
 *
 * @package coup
 */

/**
 * ----------------------------------
 * 1. CUSTOM POST TYPES
 * ----------------------------------
 */
if ( !function_exists( 'tk_add_custom_post_types' ) ) {
    function tk_add_custom_post_types() {

        // Slides post type
        $labels = array(
            'name'               => __( 'Slides', 'tkposttypes' ),
            'singular_name'      => __( 'Slides', 'tkposttypes' ),
            'add_new'            => __( 'Add New', 'tkposttypes' ),
            'add_new_item'       => __( 'Add New Slide', 'tkposttypes' ),
            'edit_item'          => __( 'Edit Slide', 'tkposttypes' ),
            'new_item'           => __( 'New Slide', 'tkposttypes' ),
            'all_items'          => __( 'All Slides', 'tkposttypes' ),
            'view_item'          => __( 'View this Slide', 'tkposttypes' ),
            'search_items'       => __( 'Search Slides', 'tkposttypes' ),
            'not_found'          => __( 'No Slides', 'tkposttypes' ),
            'not_found_in_trash' => __( 'No Slides in Trash', 'tkposttypes' ),
            'parent_item_colon'  => '',
            'menu_name'          => __( 'Slider', 'tkposttypes' ),
        ); // end $labels
        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'query_var'           => true,
            'capability_type'     => 'post',
            'rewrite'             => array( 'slug' => 'slide' ),
            'hierarchical'        => false,
            'menu_position'       => null,
            'has_archive'         => false,
            'menu_icon'           => 'dashicons-format-gallery',
            'supports'            => array( 'title', 'thumbnail', 'comments' ),
            'taxonomies'          => array( 'ct_slider' ),
        ); // end $args
        register_post_type( 'slide', $args );

        // Remove rewrite rules and then recreate rewrite rules
        flush_rewrite_rules();
    }
    add_action( 'init', 'tk_add_custom_post_types' );
}


/**
 * ----------------------------------
 * 2. CUSTOM TAXONOMIES
 * ----------------------------------
 */
if ( ! function_exists( 'tk_add_custom_taxonomies' ) ) {
    function tk_add_custom_taxonomies() {

        // Slider Categories taxonomy
        $labels = array( 'name' => _x( 'Slider Categories', 'taxonomy general name', 'tkposttypes' ),
            'singular_name'     => _x( 'Slider Categories', 'taxonomy singular name', 'tkposttypes' ),
            'search_items'      => __( 'Search Slider Categories', 'tkposttypes' ),
            'all_items'         => __( 'All Slider Categories', 'tkposttypes' ),
            'parent_item'       => __( 'Parent Slider Category', 'tkposttypes' ),
            'parent_item_colon' => __( 'Parent Slider Category', 'tkposttypes' ),
            'edit_item'         => __( 'Edit Slider Category', 'tkposttypes' ),
            'update_item'       => __( 'Update Slider Category', 'tkposttypes' ),
            'add_new_item'      => __( 'Add New Slider Category', 'tkposttypes' ),
            'new_item_name'     => __( 'New Slider Category', 'tkposttypes' ),
            'menu_name'         => __( 'Slider Categories', 'tkposttypes' ),
            'popular_items'     => null,
        );

        register_taxonomy( 'ct_slider', 'slide', array(
            'hierarchical'  => true,
            'show_tagcloud' => false,
            'labels'        => $labels,
            'rewrite'       => array(
                'slug'         => 'slide-category',
                'with_front'   => false,
                'hierarchical' => true
            )
        ) );
    }
    add_action( 'init', 'tk_add_custom_taxonomies', 0 );
}
