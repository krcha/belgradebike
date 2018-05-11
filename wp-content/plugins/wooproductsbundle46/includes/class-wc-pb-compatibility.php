<?php
/**
 * Functions related to 3rd party extensions compatibility.
 *
 * @class 	WC_PB_Compatibility
 * @since  4.6.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WC_PB_Compatibility {

	private $addons_prefix 	= '';
	private $nyp_prefix 	= '';

	function __construct() {

		// Support for Product Addons
		add_action( 'woocommerce_bundled_product_add_to_cart', array( $this, 'addons_support' ), 10, 2 );
		add_filter( 'product_addons_field_prefix', array( $this, 'addons_cart_prefix' ), 10, 2 );

		// Support for NYP
		add_action( 'woocommerce_bundled_product_add_to_cart', array( $this, 'nyp_price_input_support' ), 9, 2 );
		add_filter( 'nyp_field_prefix', array( $this, 'nyp_cart_prefix' ), 10, 2 );

		// Validate add to cart NYP and Addons
		add_filter( 'woocommerce_bundled_item_add_to_cart_validation', array( $this, 'validate_bundled_item_nyp_and_addons' ), 10, 4 );

		// Add addons identifier to bundled item stamp
		add_filter( 'woocommerce_bundled_item_cart_item_stamp', array( $this, 'bundled_item_addons_stamp' ), 10, 2 );

		// Add-ons cart item data is already stored in the stamp array, so we can grab it from there instead of allowing Addons to re-add it
		// Not doing so results in issues with file upload validation
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_bundled_item_addons_cart_data' ), 10, 1 );

		// Points and Rewards support
		add_filter( 'woocommerce_points_earned_for_cart_item', array( $this, 'points_earned_for_bundled_cart_item' ), 10, 3 );
		add_filter( 'woocommerce_points_earned_for_order_item', array( $this, 'points_earned_for_bundled_order_item' ), 10, 5 );

		// Change earn points message for per-product-priced bundles
		add_filter( 'wc_points_rewards_single_product_message', array( $this, 'points_rewards_bundle_message' ), 10, 2 );

		// Remove PnR message from variations
		if ( class_exists( 'WC_Points_Rewards_Product' ) ) {
			add_action( 'woocommerce_before_init_bundled_item', array( $this, 'points_rewards_remove_price_html_messages' ) );
			add_action( 'woocommerce_after_init_bundled_item', array( $this, 'points_rewards_restore_price_html_messages' ) );
		}

	}

	/**
	 * Filter option_wc_points_rewards_single_product_message in order to force 'WC_Points_Rewards_Product::render_variation_message' to display nothing.
	 * @param  WC_Bundled_Item  $bundled_item
	 * @return void
	 */
	function points_rewards_remove_price_html_messages( $bundled_item ) {
		add_filter( 'option_wc_points_rewards_single_product_message', array( $this, 'return_empty_message' ) );
	}

	/**
	 * Restore option_wc_points_rewards_single_product_message. Forced in order to force 'WC_Points_Rewards_Product::render_variation_message' to display nothing.
	 * @param  WC_Bundled_Item  $bundled_item
	 * @return void
	 */
	function points_rewards_restore_price_html_messages( $bundled_item ) {
		remove_filter( 'option_wc_points_rewards_single_product_message', array( $this, 'return_empty_message' ) );
	}

	/**
	 * @see points_rewards_remove_price_html_messages
	 * @param  string  $message
	 * @return void
	 */
	function return_empty_message( $message ) {
		return false;
	}

	/**
	 * Points and Rewards single product message for per-product priced Bundles.
	 * @param  string                    $message
	 * @param  WC_Points_Rewards_Product $points_n_rewards
	 * @return string
	 */
	function points_rewards_bundle_message( $message, $points_n_rewards ) {

		global $product;

		if ( $product->product_type == 'bundle' ) {

			if ( ! $product->per_product_pricing_active )
				return $message;

			// Will calculate points based on min_bundle_price
			$bundle_points = WC_Points_Rewards_Product::get_points_earned_for_product_purchase( $product );

			$message = $points_n_rewards->create_at_least_message_to_product_summary( $bundle_points );

		}

		return $message;
	}

	/**
	 * Return zero points for bundled cart items if container item has product level points.
	 *
	 * @param  int        $points
	 * @param  string     $item_key
	 * @param  array      $item
	 * @param  WC_Order   $order
	 * @return int
	 */
	function points_earned_for_bundled_order_item( $points, $product, $item_key, $item, $order ) {

		if ( isset( $item[ 'bundled_by' ] ) ) {

			// find container item
			foreach ( $order->get_items() as $order_item ) {

				$is_parent = ( isset( $order_item[ 'bundle_cart_key' ] ) && $item[ 'bundled_by' ] == $order_item[ 'bundle_cart_key' ] ) ? true : false;

				if ( $is_parent ) {

					$parent_item 		= $order_item;
					$bundle_product_id 	= $parent_item[ 'product_id' ];

					// check if earned points are set at product-level
					$bundle_points = get_post_meta( $bundle_product_id, '_wc_points_earned', true );

					$per_product_priced_bundle = isset( $parent_item[ 'per_product_pricing' ] ) ? $parent_item[ 'per_product_pricing' ] : get_post_meta( $bundle_product_id, '_per_product_pricing_active', true );

					if ( ! empty( $bundle_points ) || $per_product_priced_bundle !== 'yes' )
						$points = 0;
					else
						$points = WC_Points_Rewards_Manager::calculate_points( $product->get_price() );

					break;
				}

			}

		}

		return $points;
	}

	/**
	 * Return zero points for bundled cart items if container item has product level points.
	 *
	 * @param  int     $points
	 * @param  string  $cart_item_key
	 * @param  array   $cart_item_values
	 * @return int
	 */
	function points_earned_for_bundled_cart_item( $points, $cart_item_key, $cart_item_values ) {

		global $woocommerce;

		if ( isset( $cart_item_values[ 'bundled_by' ] ) ) {

			$cart_contents = $woocommerce->cart->get_cart();

			$bundle_cart_id 	= $cart_item_values[ 'bundled_by' ];
			$bundle 			= $cart_contents[ $bundle_cart_id ][ 'data' ];

			// check if earned points are set at product-level
			$bundle_points = WC_Points_Rewards_Product::get_product_points( $bundle );

			$per_product_priced_bundle = $bundle->per_product_pricing_active;

			$has_bundle_points = is_numeric( $bundle_points ) ? true : false;

			if ( $has_bundle_points || $per_product_priced_bundle == false  )
				$points = 0;
			else
				$points = WC_Points_Rewards_Manager::calculate_points( $cart_item_values[ 'data' ]->get_price() );

		}

		return $points;
	}

	/**
	 * Runs before adding a bundled item to the cart.
	 * @param  int                $product_id
	 * @param  int                $quantity
	 * @param  int                $variation_id
	 * @param  array              $variations
	 * @param  array              $bundled_item_cart_data
	 * @return void
	 */
	function after_bundled_add_to_cart( $product_id, $quantity, $variation_id, $variations, $bundled_item_cart_data ) {

		// Reset addons and nyp prefix
		$this->addons_prefix = $this->nyp_prefix = '';
	}

	/**
	 * Runs after adding a bundled item to the cart.
	 * @param  int                $product_id
	 * @param  int                $quantity
	 * @param  int                $variation_id
	 * @param  array              $variations
	 * @param  array              $bundled_item_cart_data
	 * @return void
	 */
	function before_bundled_add_to_cart( $product_id, $quantity, $variation_id, $variations, $bundled_item_cart_data ) {

		// Set addons and nyp prefixes
		$this->addons_prefix = $this->nyp_prefix = $bundled_item_cart_data[ 'bundled_item_id' ];
	}

	/**
	 * Add-ons cart item data is already stored in the composite_data array, so we can grab it from there instead of allowing Addons to re-add it.
	*  Not doing so results in issues with file upload validation.
	*  Note that 'Product_Addon_Cart::add_cart_item_data' has previously been unhooked from 'woocommerce_add_cart_item_data'.
	 * @param  array $cart_item_data
	 * @return array
	 */
	function add_bundled_item_addons_cart_data( $cart_item_data ) {

		global $Product_Addon_Cart;

		if ( ! empty( $Product_Addon_Cart ) && isset( $cart_item_data[ 'bundled_item_id' ] ) && isset( $cart_item_data[ 'stamp' ][ $cart_item_data[ 'bundled_item_id' ] ][ 'addons' ] ) )
			$cart_item_data[ 'addons' ] = $cart_item_data[ 'stamp' ][ $cart_item_data[ 'bundled_item_id' ] ][ 'addons' ];

		return $cart_item_data;
	}

	/**
	 * Runs after filtering bundled item cart data.
	 * @param  int                $product_id
	 * @param  int                $quantity
	 * @param  int                $variation_id
	 * @param  array              $variation
	 * @param  array              $cart_item_data
	 * @param  boolean            $add
	 * @return void
	 */
	function after_add_bundled_item_cart_data( $product_id, $quantity, $variation_id, $variation, $cart_item_data, $add ) {

		global $Product_Addon_Cart;

		if ( ! empty( $Product_Addon_Cart ) && isset( $cart_item_data[ 'bundled_item_id' ] ) && isset( $cart_item_data[ 'stamp' ][ $cart_item_data[ 'bundled_item_id' ] ][ 'addons' ] ) )
			add_filter( 'woocommerce_add_cart_item_data', array( $Product_Addon_Cart, 'add_cart_item_data' ), 10, 2 );
	}

	/**
	 * Runs before filtering bundled item cart data.
	 * @param  int                $product_id
	 * @param  int                $quantity
	 * @param  int                $variation_id
	 * @param  array              $variation
	 * @param  array              $cart_item_data
	 * @param  boolean            $add
	 * @return void
	 */
	function before_add_bundled_item_cart_data( $product_id, $quantity, $variation_id, $variation, $cart_item_data, $add ) {

		global $Product_Addon_Cart;

		if ( ! empty( $Product_Addon_Cart ) && isset( $cart_item_data[ 'bundled_item_id' ] ) && isset( $cart_item_data[ 'stamp' ][ $cart_item_data[ 'bundled_item_id' ] ][ 'addons' ] ) )
			remove_filter( 'woocommerce_add_cart_item_data', array( $Product_Addon_Cart, 'add_cart_item_data' ), 10, 2 );
	}

	/**
	 * Add addons identifier to bundled item stamp, in order to generate new cart ids for bundles with different addons configurations.
	 * @param  array  $bundled_item_stamp
	 * @param  string $bundled_item_id
	 * @return array
	 */
	function bundled_item_addons_stamp( $bundled_item_stamp, $bundled_item_id ) {

		global $Product_Addon_Cart;

		// Store bundled item addons add-ons config in stamp to avoid generating the same bundle cart id
		if ( ! empty( $Product_Addon_Cart ) ) {

			$addon_data = array();

			// Set addons prefix
			$this->addons_prefix = $bundled_item_id;

			$bundled_product_id = $bundled_item_stamp[ 'product_id' ];

			$addon_data = $Product_Addon_Cart->add_cart_item_data( $addon_data, $bundled_product_id );

			// Reset addons prefix
			$this->addons_prefix = '';

			if ( ! empty( $addon_data[ 'addons' ] ) )
				$bundled_item_stamp[ 'addons' ] = $addon_data[ 'addons' ];
		}

		return $bundled_item_stamp;
	}
	/**
	 * Validate bundled item NYP and Addons
	 * @param  bool   $add
	 * @param  int    $product_id
	 * @param  int    $quantity
	 * @return bool
	 */
	function validate_bundled_item_nyp_and_addons( $add, $bundled_item_id, $product_id, $quantity ) {

		// Validate add-ons
		global $Product_Addon_Cart;

		if ( ! empty( $Product_Addon_Cart ) ) {

			$this->addons_prefix = $bundled_item_id;

			if ( ! $Product_Addon_Cart->validate_add_cart_item( true, $product_id, $quantity ) )
				return false;

			$this->addons_prefix = '';
		}

		// Validate nyp

		if ( get_post_meta( $product_id, '_per_product_pricing_active', true ) == 'yes' && function_exists( 'WC_Name_Your_Price' ) ) {

			$this->nyp_prefix = $bundled_item_id;

			if ( ! WC_Name_Your_Price()->cart->validate_add_cart_item( true, $product_id, $quantity ) )
				return false;

			$this->nyp_prefix = '';
		}

		return $add;
	}

	/**
	 * Set the addons fields prefix value.
	 * @param string $prefix
	 */
	function set_addons_prefix( $prefix ) {

		$this->addons_prefix = $prefix;
	}

	/**
	 * Set the nyp fields prefix value.
	 * @param string $prefix
	 */
	function set_nyp_prefix( $prefix ) {

		$this->nyp_prefix = $prefix;
	}

	/**
	 * Support for bundled item addons.
	 * @param  int      $product_id    the product id
	 * @param  string   $item_id       the bundled item id
	 * @return void
	 */
	function addons_support( $product_id, $item_id ) {

		global $Product_Addon_Display;

		if ( ! empty( $Product_Addon_Display ) )
			$Product_Addon_Display->display( $product_id, $item_id . '-' );

	}

	/**
	 * Sets a unique prefix for unique add-ons. The prefix is set and re-set globally before validating and adding to cart.
	 * @param  string   $prefix         unique prefix
	 * @param  int      $product_id     the product id
	 * @return string                   a unique prefix
	 */
	function addons_cart_prefix( $prefix, $product_id ) {

		if ( ! empty( $this->addons_prefix ) )
			return $this->addons_prefix . '-';

		return $prefix;
	}

	/**
	 * Support for bundled item NYP.
	 * @param  int      $product_id     the product id
	 * @param  string   $item_id        the bundled item id
	 * @return void
	 */
	function nyp_price_input_support( $product_id, $item_id ) {

		global $product;

		if ( $product->product_type == 'bundle' && $product->per_product_pricing_active == false )
			return;

		if ( function_exists( 'WC_Name_Your_Price' ) ) {

			// Get product type
			$terms 			= get_the_terms( $product_id, 'product_type' );
			$product_type 	= ! empty( $terms ) && isset( current( $terms )->name ) ? sanitize_title( current( $terms )->name ) : 'simple';

			if ( $product_type == 'simple' )
				WC_Name_Your_Price()->display->display_price_input( $product_id, '-' . $item_id );
		}

	}

	/**
	 * Sets a unique prefix for unique NYP products. The prefix is set and re-set globally before validating and adding to cart.
	 * @param  string   $prefix         unique prefix
	 * @param  int      $product_id     the product id
	 * @return string                   a unique prefix
	 */
	function nyp_cart_prefix( $prefix, $product_id ) {

		if ( ! empty( $this->nyp_prefix ) )
			return '-' . $this->nyp_prefix;

		return $prefix;
	}

	/**
	 * Tells if a product is a Name Your Price product, provided that the extension is installed.
	 * @param  mixed    $product_id   product or id to check
	 * @return boolean                true if NYP exists and product is a NYP
	 */
	function is_nyp( $product_id ) {

		if ( ! class_exists( 'WC_Name_Your_Price_Helpers' ) )
			return false;

		if ( WC_Name_Your_Price_Helpers::is_nyp( $product_id ) )
			return true;

		return false;
	}

	/**
	 * Tells if a product is a subscription, provided that Subs is installed.
	 * @param  mixed    $product_id   product or id to check
	 * @return boolean                true if Subs exists and product is a Sub
	 */
	function is_subscription( $product_id ) {

		if ( ! class_exists( 'WC_Subscriptions' ) )
			return false;

		$is_subscription = false;

		if ( is_object( $product_id ) )
			$product_id = $product_id->id;

		$post_type = get_post_type( $product_id );

		if ( in_array( $post_type, array( 'product' ) ) ) {

			$product = WC_Subscriptions::get_product( $product_id );

			if ( $product->is_type( array( 'subscription' ) ) )
				$is_subscription = true;

		}

		return apply_filters( 'woocommerce_is_subscription', $is_subscription, $product_id );
	}

	/**
	 * Tells if an order item is a subscription, provided that Subs is installed.
	 * @param  mixed      $order   order to check
	 * @param  WC_Prder   $order   item to check
	 * @return boolean             true if Subs exists and item is a Sub
	 */
	function is_item_subscription( $order, $item ) {

		if ( ! class_exists( 'WC_Subscriptions_Order' ) )
			return false;

		return WC_Subscriptions_Order::is_item_subscription( $order, $item );
	}

	/**
	 * Checks if a product has any required addons.
	 * @param  int       $product_id   id of product to check
	 * @return boolean                 result
	 */
	function has_required_addons( $product_id ) {

		if ( ! function_exists( 'get_product_addons' ) )
			return false;

		$addons = get_product_addons( $product_id );

		if ( $addons && ! empty( $addons ) ) {
			foreach ( $addons as $addon ) {
				if ( '1' == $addon[ 'required' ] ) {
					return true;
				}
			}
		}

		return false;
	}

}
