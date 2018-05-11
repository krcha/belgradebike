<?php
/**
 * WooCommerce specific functions
 *
 * @package  coup
 */

/**
 * Redefine woocommerce_output_related_products()
 */
function coup_jk_related_products_args( $args ) {
	$args['posts_per_page'] = 3; // 4 related products
	$args['columns'] = 3; // arranged in 2 columns
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'coup_jk_related_products_args' );


//Display 12 products on archive pages
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );

/**
 * Woocommerce onsale customize text
 */
function coup_sale_flash( $text, $post, $_product ) {
    return '<span class="onsale">' . esc_html__( 'Sale', 'coup-shop' ). '</span>';
}
add_filter( 'woocommerce_sale_flash', 'coup_sale_flash', 10, 3 );

// Remove the result count from WooCommerce
remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_result_count', 20 );

// on archive move sale flash under img

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 22);


//remove sorting on archive
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );


// reorder elements on single
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
add_action( 'woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 6 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15 );


// replace read more buttons for out of stock items

if (!function_exists('woocommerce_template_loop_add_to_cart')) {

	function woocommerce_template_loop_add_to_cart( $args = array() ) {

		global $product;

		if (!$product->is_in_stock()) {
			?>
			<p class="out-of-stock"><?php echo esc_html__('Out of Stock', 'coup-shop');?></p>
			<?php

		} else {

			$defaults = array(
				'quantity' => 1,
				'class' => implode( ' ', array_filter( array(
					'button',
					'product_type_' . $product->get_type(),
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : ''
				) ) )
			);

			$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

			wc_get_template( 'loop/add-to-cart.php', $args );
		}

	}
}

// Product Single: Remove default sharing display
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

// Cart page cross-sells move

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_table', 'woocommerce_cross_sell_display' );


// change title on woocommerce search page
add_filter( 'woocommerce_page_title', 'coup_change_wc_page_title' );
function coup_change_wc_page_title( $title ) {
	if ( is_search() ) {
		$title = '<div class="big-text">' . __( 'Search', 'coup-shop' ) . '</div><span>' . get_search_query() . '</span>' ;

		$title .= '<div class="results-count">' . coup_search_results_count() . '</div>';

	}

	return $title;

}

$display_brand    = get_theme_mod( 'display_brend_setting', 1 );

/**
 * Display product tags
 */
function coup_display_product_tags() {
    global $post;

    $terms = get_the_terms( $post->id, 'product_tag' );

    if ( $terms ) :

        $product_tags = wp_list_pluck( $terms, 'name' );

        $ptags = implode( ', ', $product_tags );

        printf( '<span class="product-tag">%s</span>', $ptags );

    endif;
}

/**
 * Display product tags
 */
function coup_single_display_product_tags() {

	global $product;
	echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="product-tag">' . ' ', '</span>' );

}
// add product tag before title of product

if ( $display_brand ) {
	add_action( 'woocommerce_shop_loop_item_title', 'coup_display_product_tags', 5 );
	add_action( 'woocommerce_single_product_summary', 'coup_single_display_product_tags', 4 );
}

/**
 * Display first gallery image next to thumbnail
 */
function coup_display_gallery_image() {

	global $product;

	// Display first image of product gallery
	$attachment_ids = $product->get_gallery_image_ids();

	if ( ! empty( $attachment_ids ) ) {
		$image_link = wp_get_attachment_url( $attachment_ids[0] );
		printf( '<img src="%1s" alt="%2s">', $image_link, esc_html__( 'Product Image', 'coup-shop' ) );
	}
}
/**
 * Add action to display first gallery thumbnail image
 */
add_action( 'woocommerce_before_shop_loop_item_title', 'coup_display_gallery_image', 11 );


/**
* Checks if the current page is a product archive
* @return boolean
*/
function coup_is_product_archive() {
	if ( coup_is_woocommerce_activated() ) {
		if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}


/**
 * Jetpack infinite scroll duplicates posts where orderby is anything other than modified or date
 * This filter offsets the products returned by however many are displayed per page
 *
 * @link https://github.com/Automattic/jetpack/issues/1135
 * @param  array $args infinite scroll args.
 * @return array       infinite scroll args.
 */
function coup_woocommerce_jetpack_duplicate_products( $args ) {
	if ( ( isset( $args['post_type'] ) && 'product' === $args['post_type'] ) || ( isset( $args['taxonomy'] ) && 'product_cat' === $args['taxonomy'] ) ) {
		$args['offset'] = $args['posts_per_page'] * $args['paged'];
	}
 	return $args;
}
add_filter( 'infinite_scroll_query_args', 'coup_woocommerce_jetpack_duplicate_products', 100 );

// Disable Customizer control for Woocommerce shop page column count
function coup_customizer_edits( $wp_customize ) {
	$wp_customize->remove_control('woocommerce_catalog_columns');

	$wp_customize->get_setting( 'woocommerce_thumbnail_cropping' )->default = 'uncropped';
}

add_action( 'customize_register', 'coup_customizer_edits' );
