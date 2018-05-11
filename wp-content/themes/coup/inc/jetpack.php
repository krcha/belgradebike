<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package coup
 */


/**
 * Jetpack setup function.
 *
 * See: https://github.com/woocommerce/storefront/blob/master/inc/jetpack/class-storefront-jetpack.php
 */

function coup_jetpack_infinite_scroll_args( $args ) {
    if ( 'product' === $args['post_type'] ) {
		$args['offset'] = $args['posts_per_page'] * $args['paged'];
	}
    return $args;
}
add_filter( 'infinite_scroll_query_args', 'coup_jetpack_infinite_scroll_args', 100 );

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 */
function coup_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', array(
		'wrapper'        => false,
		'container'      => 'post-load',
		'render'         => 'coup_infinite_scroll_render',
		'footer_widgets' => array('sidebar-2','sidebar-3'),
		'footer'         => 'page',
		'type'           => 'scroll'
	) );

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );

	// Add theme support for portfolio custom post type
	add_theme_support( 'jetpack-portfolio' );

	// Add support for Content Options.
	add_theme_support( 'jetpack-content-options', array(
		'blog-display' => 'excerpt',
		'post-details' => array(
			'stylesheet' => 'coup-style',
			'date'       => '.entry-date',
			'categories' => '.cat-links',
			'tags'       => '.tags-links'
		),
	) );
}
add_action( 'after_setup_theme', 'coup_jetpack_setup' );

/**
 * Change compression quality in Photon
 */

function coup_custom_photon_compression( $args ) {
    $args['quality'] = 95;
    return $args;
}
add_filter('jetpack_photon_pre_args', 'coup_custom_photon_compression' );

/**
 * Custom render function for Infinite Scroll.
 */
function coup_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) {
		    get_template_part( 'template-parts/content', 'search' );
		} elseif ( get_post_type() == 'jetpack-portfolio' ) {
			get_template_part( 'template-parts/content', 'portfolio' );
		} elseif ( coup_is_product_archive() ) {
			wc_get_template_part( 'content', 'product' );
		} else {
		    get_template_part( 'template-parts/content', get_post_format() );
		}
	}
}

function coup_fix_duplicate_products( $args ) {
    if ( 'product' === $args['post_type'] ) {
        $args['offset'] = $args['posts_per_page'] * $args['paged'];
    }
     return $args;
}
add_filter( 'infinite_scroll_query_args', 'coup_fix_duplicate_products', 100 );

/**
 * Filter Jetpack's Related Post thumbnail size.
 *
 * @param  $size (array) - Current width and height of thumbnail.
 * @return $size (array) - New width and height of thumbnail.
*/
function coup_jetpack_relatedposts_filter_thumbnail_size( $size ) {
	$size = array(
		'width'  => 235,
		'height' => ''
	);
	return $size;
}
add_filter( 'jetpack_relatedposts_filter_thumbnail_size', 'coup_jetpack_relatedposts_filter_thumbnail_size' );

/**
 * Change width of gallery widget
 */
function coup_jetpackcom_custom_gallery_content_width(){
    return 360;
}
add_filter( 'gallery_widget_content_width', 'coup_jetpackcom_custom_gallery_content_width' );


/**
 * Change infinite scroll button text
 *
 * see: https://gist.github.com/kopepasah/9481454
 */
function coup_filter_jetpack_infinite_scroll_js_settings( $settings ) {
	$settings['text'] = __( 'Load More', 'coup-shop' );
	return $settings;
}
add_filter( 'infinite_scroll_js_settings', 'coup_filter_jetpack_infinite_scroll_js_settings' );


/**
 * Remove jetpack related posts from its place
 * it is placed inside template-parts/content-single.php via d-_shortcode
 *
 * @link https://jetpack.com/support/related-posts/customize-related-posts/#delete
 */
function coup_jetpackme_remove_rp() {
    if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
        $jprp = Jetpack_RelatedPosts::init();
        $callback = array( $jprp, 'filter_add_target_to_dom' );
        remove_filter( 'the_content', $callback, 40 );
    }
}
add_filter( 'wp', 'coup_jetpackme_remove_rp', 20 );


/**
 * Enable related posts for jetpack portfolio posts
 *
 * @link https://jetpack.com/support/related-posts/customize-related-posts/#related-posts-custom-post-types
 */
function coup_allow_my_post_types($allowed_post_types) {
    $allowed_post_types[] = 'jetpack-portfolio';
    return $allowed_post_types;
}
add_filter( 'rest_api_allowed_post_types', 'coup_allow_my_post_types' );

