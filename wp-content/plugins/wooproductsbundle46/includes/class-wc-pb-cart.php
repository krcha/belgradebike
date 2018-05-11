<?php
/**
 * Product Bundle cart functions and filters.
 *
 * @class 	WC_PB_Cart
 * @version 4.6.1
 * @since   4.5.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WC_PB_Cart {

	/**
	 * Setup cart class
	 */
	function __construct() {

		global $woocommerce;

		// Validate bundle add-to-cart
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'woo_bundles_validation' ), 10, 6 );

		// Add bundle-specific cart item data
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'woo_bundles_add_cart_item_data' ), 10, 2 );

		// Add bundled items to the cart
		add_action( 'woocommerce_add_to_cart', array( $this, 'woo_bundles_add_bundle_to_cart' ), 10, 6 );

		// Modify cart items for bundled pricing strategy
		add_filter( 'woocommerce_add_cart_item', array( $this, 'woo_bundles_add_cart_item_filter' ), 10, 2 );

		// Load bundle data from session into the cart
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'woo_bundles_get_cart_data_from_session' ), 10, 2 );

		// Add 'included with' text in cart
		add_filter( 'woocommerce_get_item_data',  array( $this, 'woo_bundles_get_item_data' ), 10, 2 );

		// Sync quantities of bundled items with bundle quantity
		add_filter( 'woocommerce_cart_item_quantity', array( $this, 'woo_bundles_cart_item_quantity' ), 10, 2 );
		add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'woo_bundles_cart_item_remove_link' ), 10, 2 );

		// Sync quantities of bundled items with bundle quantity
		add_action( 'woocommerce_after_cart_item_quantity_update', array( $this, 'woo_bundles_update_quantity_in_cart' ), 1, 2 );
		add_action( 'woocommerce_before_cart_item_quantity_zero', array( $this, 'woo_bundles_update_quantity_in_cart' ), 1 );

		// Put back cart item data to allow re-ordering of bundles
		add_filter( 'woocommerce_order_again_cart_item_data', array( $this, 'woo_bundles_order_again' ), 10, 3 );

		// Filter cart widget items
		add_filter( 'woocommerce_widget_cart_item_visible', array( $this, 'woo_bundles_cart_widget_filter' ), 10, 3 );

		// Filter cart item count
		add_filter( 'woocommerce_cart_contents_count',  array( $this, 'woo_bundles_cart_contents_count' ) );

		// Filter cart item price
		if ( version_compare( $woocommerce->version, '2.0.22' ) > 0 ) {
			add_filter( 'woocommerce_cart_item_price', array( $this, 'woo_bundles_cart_item_price_html' ), 10, 3 );
		} else {
			add_filter( 'woocommerce_cart_item_price_html', array( $this, 'woo_bundles_cart_item_price_html' ), 10, 3 );
		}

		// Modify cart items subtotals according to the pricing strategy used (static / per-product)
		add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'woo_bundles_item_subtotal' ), 10, 3 );
		add_filter( 'woocommerce_checkout_item_subtotal', array( $this, 'woo_bundles_item_subtotal' ), 10, 3 );

		// Debug
		// add_action( 'woocommerce_before_cart_contents', array($this, 'woo_bundles_before_cart') );
	}

	/**
	 * Validates add-to-cart for bundles.
	 * Basically ensures that stock for all bundled products exists before attempting to add them to cart.
	 * @param  boolean  $add                core validation add to cart flag
	 * @param  int      $product_id         the product id
	 * @param  int      $product_quantity   quantity
	 * @param  mixed    $variation_id       variation id
	 * @param  array    $variations         variation data
	 * @param  array    $cart_item_data     cart item data
	 * @return boolean                      modified add to cart validation flag
	 */
	function woo_bundles_validation( $add, $product_id, $product_quantity, $variation_id = '', $variations = array(), $cart_item_data = array() ) {

		global $woocommerce;

		// Get product type
		$terms 			= get_the_terms( $product_id, 'product_type' );
		$product_type 	= ! empty( $terms ) && isset( current( $terms )->name ) ? sanitize_title( current( $terms )->name ) : 'simple';

		// prevent bundled items from getting validated - they will be added by the container item
		if ( isset( $cart_item_data[ 'is_bundled' ] ) && isset( $_GET[ 'order_again' ] ) )
			return false;

		if ( $product_type == 'bundle' ) {

			$product = get_product( $product_id );

			if ( ! $product )
				return false;

			// check request and prepare variation stock check data
			$stock_check_data = array();

			// grab bundled items
			$bundled_items = $product->get_bundled_items();

			if ( ! $bundled_items )
				return $add;

			foreach ( $bundled_items as $bundled_item_id => $bundled_item ) {

				$id 					= $bundled_item->product_id;
				$bundled_product_type 	= $bundled_item->product->product_type;

				$item_quantity 	= $bundled_item->quantity;
				$quantity		= $item_quantity * $product_quantity;

				if ( $bundled_product_type == 'variable' ) {

					$variation_id = isset( $cart_item_data[ 'stamp' ][ $bundled_item_id ][ 'variation_id' ] ) && isset( $_GET[ 'order_again' ] ) ? $cart_item_data[ 'stamp' ][ $bundled_item_id ][ 'variation_id' ] : $_REQUEST[ 'bundle_variation_id' ][ $bundled_item_id ] ;

					if ( isset( $variation_id ) && is_numeric( $variation_id ) && $variation_id > 1 ) {

						$stock_check_data[ $id ][ 'type' ] = 'variable';

						$variation_stock = get_post_meta( $variation_id, '_stock', true );

						if ( get_post_meta( $variation_id, '_price', true ) === '' ) {
							wc_bundles_add_notice( sprintf( __( 'Sorry, the selected variation of &quot;%s&quot; cannot be purchased.', 'woocommerce-product-bundles' ), get_the_title( $id ) ), 'error' );
							return false;
						}

						if ( ! isset( $stock_check_data[ $id ][ 'variations' ] ) )
							$stock_check_data[$id][ 'variations' ] = array();

						if ( ! isset( $stock_check_data[ $id ][ 'managed_quantities' ] ) )
							$stock_check_data[ $id ][ 'managed_quantities' ] = array();

						if ( ! in_array( $variation_id, $stock_check_data[ $id ][ 'variations' ] ) )
							$stock_check_data[ $id ][ 'variations' ][] = $variation_id;

						// If stock is managed on a variation level
						if ( isset( $variation_stock ) && $variation_stock !== '' ) {

							// If a stock-managed variation is added to the cart multiple times,
							// its stock must be checked for the sum of all quantities
							if ( isset( $stock_check_data[ $id ][ 'managed_quantities' ][ $variation_id ] ) )
								$stock_check_data[ $id ][ 'managed_quantities' ][ $variation_id ] += $quantity;
							else
								$stock_check_data[ $id ][ 'managed_quantities' ][ $variation_id ] = $quantity;

						} else {

							// Non-stock-managed variations of the same item
							// must be stock-checked together
							if ( isset( $stock_check_data[ $id ][ 'quantity' ] ) )
								$stock_check_data[ $id ][ 'quantity' ] += $quantity;
							else {
								$stock_check_data[ $id ][ 'quantity' ] = $quantity;
							}
						}

					} else {
    					wc_bundles_add_notice( __( 'Please choose product options&hellip;', 'woocommerce' ), 'error' );
						return false;
					}

					// Verify all attributes for the variable product were set - TODO: verify with filters

					$attributes = ( array ) maybe_unserialize( get_post_meta( $id, '_product_attributes', true ) );
		    		$variations = array();
		    		$all_set 	= true;

		    		$variation_data = array();

					$custom_fields = get_post_meta( $variation_id );

					// Get the variation attributes from meta
					foreach ( $custom_fields as $name => $value ) {

						if ( ! strstr( $name, 'attribute_' ) )
							continue;

						$variation_data[ $name ] = sanitize_title( $value[0] );
					}


					// Verify all attributes
					foreach ( $attributes as $attribute ) {
					    if ( ! $attribute['is_variation'] )
					    	continue;

					    $taxonomy = 'attribute_' . sanitize_title( $attribute['name'] );

						if ( ! empty( $_REQUEST[ 'bundle_' . $taxonomy ][ $bundled_item_id ] ) ) {

					        // Get value from post data
					        // Don't use woocommerce_clean as it destroys sanitized characters
					        $value = sanitize_title( trim( stripslashes( $_REQUEST[ 'bundle_' . $taxonomy ][ $bundled_item_id ] ) ) );

					        // Get valid value from variation
					        $valid_value = $variation_data[ $taxonomy ];

					        // Allow if valid
					        if ( $valid_value == '' || $valid_value == $value ) {
					            continue;
					        }

						} elseif ( isset( $cart_item_data[ 'stamp' ][ $bundled_item_id ][ 'attributes' ] ) && isset( $cart_item_data[ 'stamp' ][ $bundled_item_id ][ 'variation_id' ] )  && isset( $_GET[ 'order_again' ] ) ) {

							$value = sanitize_title( trim( stripslashes( $cart_item_data[ 'stamp' ][ $bundled_item_id ][ 'attributes' ][ esc_html( $attribute[ 'name' ] ) ] ) ) ); // $taxonomy in WC 2.1

					        $valid_value = $variation_data[ $taxonomy ];

					        if ( $valid_value == '' || $valid_value == $value ) {
					            continue;
					        }
						}

					    $all_set = false;
					}

					if ( ! $all_set ) {
						wc_bundles_add_notice( __( 'Please choose product options&hellip;', 'woocommerce' ), 'error' );
						return false;
					}


				} elseif ( $bundled_product_type == 'simple' || $bundled_product_type == 'subscription' ) {

					$stock_check_data[ $id ][ 'type' ] = 'simple';

					if ( isset( $stock_check_data[ $id ][ 'quantity' ] ) )
						$stock_check_data[ $id ][ 'quantity' ] += $quantity;
					else {
						$stock_check_data[ $id ][ 'quantity' ] = $quantity;
					}
				}

				if ( ! apply_filters( 'woocommerce_bundled_item_add_to_cart_validation', true, $bundled_item_id, $id, $quantity ) )
					return false;

			}


			// Check stock for bundled items one by one
			// If out of stock, don't proceed

			foreach ( $stock_check_data as $item_id => $data ) {

				if ( $data['type'] == 'variable' ) {

					foreach( $data['variations'] as $variation_id ) {

						if ( array_key_exists( $variation_id, $data[ 'managed_quantities' ] ) )
							$quantity = $data[ 'managed_quantities' ][ $variation_id ];
						else
							$quantity = $data[ 'quantity' ];

						if ( ! $this->bundled_add_to_cart( $product_id, $item_id, $quantity, $variation_id, array(), array(), false ) )
							return false;

					}

				} elseif ( $data[ 'type' ] == 'simple' ) {

					// if out of stock, don't proceed
					if ( ! $this->bundled_add_to_cart( $product_id, $item_id, $data[ 'quantity' ], '', array(), array(), false ) )
						return false;
				}

			}

		}

		return $add;
	}

	/**
	 * Adds bundle specific cart-item data.
	 * The 'stamp' var is a unique identifier for that particular bundle configuration.
	 * @param  array    $cart_item_data    the cart item data
	 * @param  int      $product_id	       the product id
	 * @return array                       modified cart item data
	 */
	function woo_bundles_add_cart_item_data( $cart_item_data, $product_id ) {

		global $woocommerce_bundles;

		// Get product type
		$terms 			= get_the_terms( $product_id, 'product_type' );
		$product_type 	= ! empty( $terms ) && isset( current( $terms )->name ) ? sanitize_title( current( $terms )->name ) : 'simple';

		if ( $product_type == 'bundle' ) {

			$product = get_product( $product_id );

			if ( ! $product )
				return false;

			// grab bundled items
			$bundled_items = $product->get_bundled_items();

			if ( empty( $bundled_items ) )
				return $add;

			// Create a unique stamp id with the bundled items' configuration
			$stamp = array();

			foreach ( $bundled_items as $bundled_item_id => $bundled_item ) {

				$id 					= $bundled_item->product_id;
				$bundled_product_type 	= $bundled_item->product->product_type;

				$stamp[ $bundled_item_id ][ 'product_id' ] 	= $id;
				$stamp[ $bundled_item_id ][ 'type' ] 		= $bundled_product_type;
				$stamp[ $bundled_item_id ][ 'quantity' ]	= $bundled_item->quantity;
				$stamp[ $bundled_item_id ][ 'discount' ]	= $bundled_item->discount;

				// Store variable product options in stamp to avoid generating the same bundle cart id
				if ( $bundled_product_type == 'variable' ) {

					if ( isset( $cart_item_data[ 'stamp' ][ $bundled_item_id ][ 'attributes' ] ) && isset( $_GET[ 'order_again' ] ) ) {

						$stamp[ $bundled_item_id ][ 'attributes' ] 	= $cart_item_data[ 'stamp' ][ $bundled_item_id ][ 'attributes' ];
						$stamp[ $bundled_item_id ][ 'variation_id' ] = $cart_item_data[ 'stamp' ][ $bundled_item_id ][ 'variation_id' ];

						continue;
					}

					$attr_stamp 	= array();
					$attributes 	= ( array ) maybe_unserialize( get_post_meta( $id, '_product_attributes', true ) );

					foreach ( $attributes as $attribute ) {

						if ( ! $attribute[ 'is_variation' ] )
							continue;

						$taxonomy 	= 'attribute_' . sanitize_title( $attribute[ 'name' ] );

						// has already been checked for validity in function 'woo_bundles_validation'
						$value 		= sanitize_title( trim( stripslashes( $_REQUEST[ 'bundle_' . $taxonomy ][ $bundled_item_id ] ) ) );

						if ( $attribute[ 'is_taxonomy' ] ) {
							if ( $woocommerce_bundles->helpers->is_wc_21() )
								$attr_stamp[ $taxonomy ] = $value;
							else
								$attr_stamp[ esc_html( $attribute['name'] ) ] = $value;

						} else {
						    // For custom attributes, get the name from the slug
						    $options = array_map( 'trim', explode( wc_bundles_delimiter(), $attribute[ 'value' ] ) );
						    foreach ( $options as $option ) {
						    	if ( sanitize_title( $option ) == $value ) {
						    		$value = $option;
						    		break;
						    	}
						    }
							if ( $woocommerce_bundles->helpers->is_wc_21() )
								$attr_stamp[ $taxonomy ] = $value;
							else
								$attr_stamp[ esc_html( $attribute['name'] ) ] = $value;
						}

					}

					$stamp[ $bundled_item_id ][ 'attributes' ] 	= $attr_stamp;
					$stamp[ $bundled_item_id ][ 'variation_id' ] = $_REQUEST[ 'bundle_variation_id' ][ $bundled_item_id ];
				}

				$stamp[ $bundled_item_id ] = apply_filters( 'woocommerce_bundled_item_cart_item_stamp', $stamp[ $bundled_item_id ], $bundled_item_id );

			}

			$cart_item_data[ 'stamp' ] = $stamp;

			// Prepare additional data for later use
			$cart_item_data[ 'bundled_items' ] = array();

			return $cart_item_data;

		} else {

			return $cart_item_data;
		}

	}

	/**
	 * Adds bundled items to the cart.
	 * The 'bundled by' var is added to each item to identify between bundled and non-bundled instances of products.
	 * Important: Recursively calling the core add_to_cart function can lead to issus with the contained action hook: https://core.trac.wordpress.org/ticket/17817.
	 * @param  string   $item_cart_key      the cart item key
	 * @param  int      $bundle_id          the product id
	 * @param  int      $bundle_quantity    the product quantity
	 * @param  int      $variation_id       the variation id
	 * @param  array    $variation          variation data array
	 * @param  array    $cart_item_data     cart item data array
	 * @return void
	 */
	function woo_bundles_add_bundle_to_cart( $bundle_cart_key, $bundle_id, $bundle_quantity, $variation_id, $variation, $cart_item_data ) {

		global $woocommerce, $woocommerce_bundles;

		if ( isset( $cart_item_data[ 'stamp' ] ) && ! isset( $cart_item_data[ 'bundled_by' ] ) ) {

			// this id is unique, so that bundled and non-bundled versions of the same product will be added separately to the cart.
			$bundled_item_cart_data = array( 'bundled_item_id' => '', 'bundled_by' => $bundle_cart_key, 'stamp' => $cart_item_data[ 'stamp' ], 'dynamic_pricing_allowed' => 'no' );

			// the bundle
			$bundle = $woocommerce->cart->cart_contents[ $bundle_cart_key ][ 'data' ];

			// Now add all items - yay
			foreach ( $cart_item_data[ 'stamp' ] as $bundled_item_id => $bundled_item_stamp ) {

				// identifier needed for fetching post meta
				$bundled_item_cart_data[ 'bundled_item_id' ] = $bundled_item_id;

				$item_quantity 	= $bundled_item_stamp[ 'quantity' ];
				$quantity		= $item_quantity * $bundle_quantity ;

				$product_id = $bundled_item_stamp[ 'product_id' ];

				$bundled_product_type = $bundled_item_stamp[ 'type' ];

				if ( $bundled_product_type == 'simple' || $bundled_product_type == 'subscription' ) {

					$variation_id 	= '';
					$variations		= array();

				} elseif ( $bundled_product_type == 'variable' ) {

					$variation_id 	= $bundled_item_stamp[ 'variation_id' ];
					$variations		= $bundled_item_stamp[ 'attributes' ];
				}

				$woocommerce_bundles->compatibility->before_bundled_add_to_cart( $product_id, $quantity, $variation_id, $variations, $bundled_item_cart_data );

				// Add to cart
				$bundled_item_cart_key = $this->bundled_add_to_cart( $bundle_id, $product_id, $quantity, $variation_id, $variations, $bundled_item_cart_data, true );

				if ( ! in_array( $bundled_item_cart_key, $woocommerce->cart->cart_contents[ $bundle_cart_key ][ 'bundled_items' ] ) )
					$woocommerce->cart->cart_contents[ $bundle_cart_key ][ 'bundled_items' ][] = $bundled_item_cart_key;

				$woocommerce_bundles->compatibility->after_bundled_add_to_cart( $product_id, $quantity, $variation_id, $variations, $bundled_item_cart_data );

			}

		}

	}

	/**
	 * Add a bundled product to the cart. Must be done without updating session data, recalculating totals or calling 'woocommerce_add_to_cart' recursively.
	 * For the recursivity issue, see: https://core.trac.wordpress.org/ticket/17817.
	 *
	 * When the add parameter is true, it will actually add the item to the cart and return its cart id, otherwise it will only output error messages and return true/false. Used by @see woo_bundles_validation.
	 *
	 * @param int          $bundle_id         contains the id of the parent bundle
	 * @param int          $product_id        contains the id of the product to add to the cart
	 * @param int          $quantity          contains the quantity of the item to add
	 * @param int          $variation_id
	 * @param array        $variation         attribute values
	 * @param array        $cart_item_data    extra cart item data passed into the bundled item
	 * @param boolean      $add               true when actually adding the item to the cart, false when probing
	 * @return bool
	 */
	public function bundled_add_to_cart( $bundle_id, $product_id, $quantity = 1, $variation_id = '', $variation = '', $cart_item_data, $add = false ) {

		global $woocommerce, $woocommerce_bundles;

		if ( $quantity <= 0 ) {
			return false;
		}

		// Load cart item data when adding to cart
		if ( $add ) {

			$woocommerce_bundles->compatibility->before_add_bundled_item_cart_data( $product_id, $quantity, $variation_id, $variation, $cart_item_data, $add );

			$cart_item_data = ( array ) apply_filters( 'woocommerce_add_cart_item_data', $cart_item_data, $product_id, $variation_id );

			$woocommerce_bundles->compatibility->after_add_bundled_item_cart_data( $product_id, $quantity, $variation_id, $variation, $cart_item_data, $add );
		}

		// Generate a ID based on product ID, variation ID, variation data, and other cart item data
		$cart_id  = $woocommerce->cart->generate_cart_id( $product_id, $variation_id, $variation, $cart_item_data );

		// See if this product and its options is already in the cart
		$cart_item_key = $woocommerce->cart->find_product_in_cart( $cart_id );

		// Ensure we don't add a variation to the cart directly by variation ID
		if ( 'product_variation' == get_post_type( $product_id ) ) {
			$variation_id = $product_id;
			$product_id   = wp_get_post_parent_id( $variation_id );
		}

		// Get the product
		$product_data = get_product( $variation_id ? $variation_id : $product_id );

		if ( ! $product_data )
			return false;

		// is_sold_individually
		if ( $product_data->sold_individually == 'yes' && $quantity > 1 ) {
			if ( ! $add )
				wc_bundles_add_notice( sprintf( __( '&quot;%s&quot; cannot be added to the cart &mdash; only 1 &quot;%s&quot; may be purchased.', 'woocommerce-product-bundles' ), $product_data->get_title() ), 'error' );
			return false;
		}

		// Check product is_purchasable
		if ( ! $product_data->is_purchasable() ) {
			if ( ! $add )
				wc_bundles_add_notice( sprintf( __( '&quot;%s&quot; cannot be added to the cart because &quot;%s&quot; cannot be purchased.', 'woocommerce-product-bundles' ), get_the_title( $bundle_id ), $product_data->get_title() ), 'error' );
			return false;
		}

		// Stock check - only check if we're managing stock and backorders are not allowed
		if ( ! $product_data->is_in_stock() ) {

			if ( ! $add )
				wc_bundles_add_notice( sprintf( __( '&quot;%s&quot; cannot be added to the cart because &quot;%s&quot; is out of stock.', 'woocommerce-product-bundles' ), get_the_title( $bundle_id ), $product_data->get_title() ), 'error' );

			return false;
		} elseif ( ! $product_data->has_enough_stock( $quantity ) ) {

			if ( ! $add )
				wc_bundles_add_notice( sprintf(__( '&quot;%s&quot; cannot be added to the cart because there is not enough stock of &quot;%s&quot; (%s remaining).', 'woocommerce-product-bundles' ), get_the_title( $bundle_id ), $product_data->get_title(), $product_data->get_stock_quantity() ), 'error' );

			return false;
		}

		// Stock check - this time accounting for whats already in-cart
		$product_qty_in_cart = $woocommerce->cart->get_cart_item_quantities();

		/* ------------------------------------------------------------------------------------------------------------------------------------------------- */
		/* When the 'add' flag is true, quantities in cart have already been adjusted by woo_bundles_update_quantity_in_cart after adding the parent item.
		/* In this case, $product_qty_in_cart must be adjusted.
		/* ------------------------------------------------------------------------------------------------------------------------------------------------- */

		$quantity_counted_in_cart = $add ? $quantity : 0;

		if ( $product_data->managing_stock() ) {

			// Variations
			if ( $variation_id && $product_data->variation_has_stock ) {

				if ( isset( $product_qty_in_cart[ $variation_id ] ) && ! $product_data->has_enough_stock( $product_qty_in_cart[ $variation_id ] - $quantity_counted_in_cart + $quantity ) ) {

					if ( ! $add )
						wc_bundles_add_notice( sprintf(
							'<a href="%s" class="button wc-forward">%s</a> %s',
							$woocommerce->cart->get_cart_url(),
							__( 'View Cart', 'woocommerce' ),
							sprintf( __( '&quot;%s&quot; cannot be added to the cart because there is not enough stock of &quot;%s&quot; &mdash; we have %s in stock and you already have %s in your cart.', 'woocommerce-product-bundles' ), get_the_title( $bundle_id ), $product_data->get_title(), $product_data->get_stock_quantity(), $product_qty_in_cart[ $variation_id ] )
						), 'error' );
					return false;
				}

			// Products
			} else {

				if ( isset( $product_qty_in_cart[ $product_id ] ) && ! $product_data->has_enough_stock( $product_qty_in_cart[ $product_id ] - $quantity_counted_in_cart + $quantity ) ) {
					if ( ! $add )
						wc_bundles_add_notice( sprintf(
							'<a href="%s" class="button wc-forward">%s</a> %s',
							$woocommerce->cart->get_cart_url(),
							__( 'View Cart', 'woocommerce' ),
							sprintf( __( '&quot;%s&quot; cannot be added to the cart because there is not enough stock of &quot;%s&quot; &mdash; we have %s in stock and you already have %s in your cart.', 'woocommerce-product-bundles' ), get_the_title( $bundle_id ), $product_data->get_title(), $product_data->get_stock_quantity(), $product_qty_in_cart[ $product_id ] )
						), 'error' );
					return false;
				}

			}

		}

		if ( ! $add )
			return true;

		// If cart_item_key is set, the item is already in the cart and its quantity will be handled by woo_bundles_update_quantity_in_cart.
		if ( ! $cart_item_key ) {

			$cart_item_key = $cart_id;

			// Add item after merging with $cart_item_data - allow plugins and woo_bundles_add_cart_item_filter to modify cart item
			$woocommerce->cart->cart_contents[ $cart_item_key ] = apply_filters( 'woocommerce_add_cart_item', array_merge( $cart_item_data, array(
				'product_id'	=> $product_id,
				'variation_id'	=> $variation_id,
				'variation' 	=> $variation,
				'quantity' 		=> $quantity,
				'data'			=> $product_data
			) ), $cart_item_key );

		}

		return $cart_item_key;
	}

	/**
	 * When a bundle is static-priced, the price of all bundled items is set to 0.
	 * When the shipping mode is set to "bundled", all bundled items are marked as virtual when they are added to the cart.
	 * Otherwise, the container itself is a virtual product in the first place.
	 * @param  array    $cart_data   cart item data
	 * @param  string   $id          cart item key
	 * @return array                 modified cart item data
	 */
	function woo_bundles_add_cart_item_filter( $cart_data, $id ) {

		global $woocommerce;

		$cart_contents = $woocommerce->cart->get_cart();

		if ( isset( $cart_data[ 'bundled_by' ] ) ) {

			$bundle_cart_id = $cart_data[ 'bundled_by' ];

			$per_product_pricing = ( $cart_contents[ $bundle_cart_id ][ 'data' ]->per_product_pricing_active == true ) ? true : false;
			$per_product_shipping = ( $cart_contents[ $bundle_cart_id ][ 'data' ]->per_product_shipping_active == true ) ? true : false;

			if ( $per_product_pricing == false ) {

				$cart_data[ 'data' ]->price 					= 0;
				$cart_data[ 'data' ]->subscription_sign_up_fee 	= 0;

			} else {

				$discount = $cart_data[ 'stamp' ][ $cart_data[ 'bundled_item_id' ] ][ 'discount' ];

				if ( ! empty( $discount ) ) {

					$bundled_item_id 	= $cart_data[ 'bundled_item_id' ];
					$bundled_item 		= $cart_contents[ $bundle_cart_id ][ 'data' ]->bundled_items[ $bundled_item_id ];

					$cart_data[ 'data' ]->price = $bundled_item->get_price( $cart_data[ 'data' ]->price, $cart_data[ 'data' ] );
				}
			}

			if ( $per_product_shipping == false )
				$cart_data[ 'data' ]->virtual = 'yes';
		}

		return $cart_data;
	}

	/**
	 * Reload all bundle-related session data in the cart.
	 * @param  array    $cart_item              cart item data
	 * @param  array    $item_session_values    item session data
	 * @return array                            modified cart item data
	 */
	function woo_bundles_get_cart_data_from_session( $cart_item, $item_session_values ) {

		global $woocommerce;

		$cart_contents = ! empty( $woocommerce->cart ) ? $woocommerce->cart->get_cart() : '';

		if ( isset( $item_session_values[ 'bundled_items' ] ) && ! empty( $item_session_values[ 'bundled_items' ] ) )
			$cart_item[ 'bundled_items' ] = $item_session_values[ 'bundled_items' ];

		if ( isset( $item_session_values[ 'stamp' ] ) ) {
			$cart_item[ 'stamp' ] = $item_session_values[ 'stamp' ];
		}

		if ( isset( $item_session_values[ 'bundled_by' ] ) ) {

			// load 'bundled_by' field
			$cart_item[ 'bundled_by' ] = $item_session_values[ 'bundled_by' ];

			// load product bundle post meta identifier
			$cart_item[ 'bundled_item_id' ] = $item_session_values[ 'bundled_item_id' ];

			// load dynamic pricing permission
			$cart_item[ 'dynamic_pricing_allowed' ] = $item_session_values[ 'dynamic_pricing_allowed' ];

			// now modify item depending on bundle pricing & shipping options
			$bundle_cart_id = $cart_item[ 'bundled_by' ];

			$per_product_pricing = ( ! empty( $cart_contents ) && $cart_contents[ $bundle_cart_id ][ 'data' ]->per_product_pricing_active == true ) ? true : false;
			$per_product_shipping = ( ! empty( $cart_contents ) && $cart_contents[ $bundle_cart_id ][ 'data' ]->per_product_shipping_active == true ) ? true : false;

			if ( $per_product_pricing == false ) {

				$cart_item[ 'data' ]->price 					= 0;
				$cart_item[ 'data' ]->subscription_sign_up_fee 	= 0;

			} else {

				$discount = $cart_item[ 'stamp' ][ $cart_item[ 'bundled_item_id' ] ][ 'discount' ];

				if ( ! empty( $discount ) ) {

					$bundled_item_id 	= $cart_item[ 'bundled_item_id' ];
					$bundled_item 		= $cart_contents[ $bundle_cart_id ][ 'data' ]->bundled_items[ $bundled_item_id ];

					$cart_item[ 'data' ]->price = $bundled_item->get_price( $cart_item[ 'data' ]->price, $cart_item[ 'data' ] );
				}
			}

			if ( $per_product_shipping == false )
				$cart_item[ 'data' ]->virtual = 'yes';
		}

		return $cart_item;
	}

	/**
	 * Add "included with" bundle metadata.
	 * @param  array    $data       metadata array
	 * @param  array    $cart_item  cart item data
	 * @return array                modified metadata array
	 */
	function woo_bundles_get_item_data( $data, $cart_item ) {

		global $woocommerce;

		if ( isset( $cart_item[ 'bundled_by' ] ) && isset( $cart_item[ 'stamp' ] ) ) {
			// not really necessary since we know its going to be there
			$product_key = $woocommerce->cart->find_product_in_cart( $cart_item[ 'bundled_by' ] );

			if ( ! empty( $product_key ) ) {

				$product_name = get_post( $woocommerce->cart->cart_contents[ $product_key ][ 'product_id' ] )->post_title;
				$data[] = array(
						'name'    => __( 'Included with', 'woocommerce-product-bundles' ),
						'display' => __( $product_name )
				);
			}
		}

		return $data;
	}

	/**
	 * Bundled items can't be removed individually from the cart - this hides the remove buttons.
	 * @param  string    $link           remove URL
	 * @param  string    $cart_item_key  the cart item key
	 * @return string                    modified remove link
	 */
	function woo_bundles_cart_item_remove_link( $link, $cart_item_key ) {

		global $woocommerce;

		if ( isset( $woocommerce->cart->cart_contents[ $cart_item_key ][ 'bundled_by' ] ) )
			return '';

		return $link;
	}

	/**
	 * Bundled item quantities can't be changed individually. When adjusting quantity for the container item, the bundled products must follow.
	 * @param  int      $quantity       quantity of cart item
	 * @param  string   $cart_item_key  cart item key
	 * @return int                      modified quantity
	 */
	function woo_bundles_cart_item_quantity( $quantity, $cart_item_key ) {

		global $woocommerce;

		if ( isset( $woocommerce->cart->cart_contents[ $cart_item_key ][ 'stamp' ] ) ) {

			if ( isset( $woocommerce->cart->cart_contents[ $cart_item_key ][ 'bundled_by' ] ) )
				return $woocommerce->cart->cart_contents[ $cart_item_key ][ 'quantity' ];
		}

		return $quantity;
	}

	/**
	 * Keep quantities between bundled products and container items in sync.
	 * @param  string   $cart_item_key  the cart item key
	 * @param  integer  $quantity       the item quantity
	 * @return void
	 */
	function woo_bundles_update_quantity_in_cart( $cart_item_key, $quantity = 0 ) {

		global $woocommerce;

		if ( isset( $woocommerce->cart->cart_contents[ $cart_item_key ] ) && ! empty( $woocommerce->cart->cart_contents[ $cart_item_key ] ) ) {

			if ( $quantity == 0 || $quantity < 0 )
				$quantity = 0;
			else
				$quantity = $woocommerce->cart->cart_contents[ $cart_item_key ][ 'quantity' ];

			if ( isset( $woocommerce->cart->cart_contents[ $cart_item_key ][ 'stamp' ] ) && ! empty( $woocommerce->cart->cart_contents[ $cart_item_key ][ 'stamp' ] ) && ! isset( $woocommerce->cart->cart_contents[ $cart_item_key ][ 'bundled_by' ] ) ) {

				// unique bundle stamp added to all bundled items & the grouping item
				$stamp = $woocommerce->cart->cart_contents[ $cart_item_key ][ 'stamp' ];

				// change the quantity of all bundled items that belong to the same bundle config
				foreach ( $woocommerce->cart->cart_contents as $key => $value ) {

					if ( isset( $value[ 'bundled_by' ] ) && isset( $value[ 'stamp' ] ) && $cart_item_key == $value[ 'bundled_by' ] && $stamp == $value[ 'stamp' ] ) {

						if ( $value[ 'data' ]->is_sold_individually() && $quantity > 0 ) {
							$woocommerce->cart->set_quantity( $key, 1, false );
						} else {
							$bundle_quantity = $value[ 'stamp' ][ $value[ 'bundled_item_id' ] ][ 'quantity' ];
							$woocommerce->cart->set_quantity( $key, $quantity * $bundle_quantity, false );
						}
					}
				}

			}

		}

	}

	/**
	 * Reinialize cart item data for re-ordering purchased orders.
	 * @param  array    $cart_item_data     cart item data
	 * @param  array    $order_item         order item data
	 * @param  WC_Order $order              the order
	 * @return array                        modified cart item data
	 */
	function woo_bundles_order_again( $cart_item_data, $order_item, $order ) {

		if ( isset( $order_item[ 'bundled_by' ] ) && isset( $order_item[ 'stamp' ] ) )
			$cart_item_data[ 'is_bundled' ] = 'yes';

		if ( ! isset( $order_item[ 'bundled_by' ] ) && isset( $order_item[ 'stamp' ] ) )
			$cart_item_data[ 'stamp' ] = maybe_unserialize( $order_item[ 'stamp' ] );

		return $cart_item_data;
	}

	/**
	 * Do not show container items or bundled items, depending on the chosen pricing method.
	 * @param  boolean  $show           show/hide flag
	 * @param  array    $cart_item      cart item data
	 * @param  string   $cart_item_key  cart item key
	 * @return boolean                  modified show/hide flag
	 */
	function woo_bundles_cart_widget_filter( $show, $cart_item, $cart_item_key ) {

		global $woocommerce;

		if ( isset( $cart_item[ 'bundled_by' ] ) ) {

			// not really necessary since we know its going to be there
			$bundle_key = $woocommerce->cart->find_product_in_cart( $cart_item[ 'bundled_by' ] );

			if ( ! empty( $bundle_key ) ) {

				$product_id = $woocommerce->cart->cart_contents[ $bundle_key ][ 'product_id' ];

				if ( get_post_meta( $product_id, '_per_product_pricing_active', true ) == 'no' )
					return false;
			}
		}

		if ( !isset( $cart_item['bundled_by'] ) && isset( $cart_item['stamp'] ) ) {

			if ( get_post_meta( $cart_item['product_id'], '_per_product_pricing_active', true ) == 'yes' )
					return false;
		}

		return $show;

	}

	/**
	 * Filters the reported number of cart items depending on pricing strategy: per-item price: container is subtracted, static price: items are subtracted.
	 * @param  int  $count  item counnt
	 * @return int          modified item count
	 */
	function woo_bundles_cart_contents_count( $count ) {

		global $woocommerce;

		$cart = $woocommerce->cart->get_cart();

		$subtract = 0;

		foreach ( $cart as $key => $value ) {

			if ( isset( $value[ 'bundled_by' ] ) ) {

				$bundle_cart_id = $value[ 'bundled_by' ];
				$bundle_product_id = $cart[ $bundle_cart_id ][ 'product_id' ];

				$per_product_shipping = ( get_post_meta( $bundle_product_id, '_per_product_shipping_active', true ) == 'yes' ) ? true : false;

				if ( ! $per_product_shipping ) {
					$subtract += $value[ 'quantity' ];
				}
			}

			if ( isset( $value[ 'stamp' ] ) && ! isset( $value[ 'bundled_by' ] ) ) {

				$bundle_product_id = $value[ 'product_id' ];

				$per_product_shipping = ( get_post_meta( $bundle_product_id, '_per_product_shipping_active', true ) == 'yes' ) ? true : false;

				if ( $per_product_shipping ) {
					$subtract += $value[ 'quantity' ];
				}
			}
		}

		return $count - $subtract;

	}

	/**
	 * Modify the front-end price of bundled items and container items depending on the bundles's pricing strategy.
	 * @param  double   $price          the item price
	 * @param  array    $values         the cart item data
	 * @param  string   $cart_item_key  the cart item key
	 * @return string                   modified subtotal string.
	 */
	function woo_bundles_cart_item_price_html( $price, $values, $cart_item_key ) {

		global $woocommerce;

		if ( isset( $values[ 'bundled_by' ] ) ) {

			$bundle_cart_key = $values[ 'bundled_by' ];

			if ( $woocommerce->cart->cart_contents[ $bundle_cart_key ][ 'data' ]->per_product_pricing_active == false && $values[ 'data' ]->price == 0 )
				return '';
		}

		if ( isset( $values[ 'bundled_items' ] ) ) {

			if ( $values[ 'data' ]->per_product_pricing_active == true ) {

				$bundled_items_price 			= 0;
				$bundled_items_recurring_price 	= 0;
				$bundle_price 					= get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $values[ 'data' ]->get_price_excluding_tax( $values[ 'quantity' ] ) : $values[ 'data' ]->get_price_including_tax( $values[ 'quantity' ] );

				foreach ( $values[ 'bundled_items' ] as $bundled_item_key ) {

					$item_values 	= $woocommerce->cart->cart_contents[ $bundled_item_key ];
					$item_id 		= $item_values[ 'bundled_item_id' ];
					$product 		= $item_values[ 'data' ];

					$bundled_item_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $product->get_price_excluding_tax( $item_values[ 'quantity' ] ) : $product->get_price_including_tax( $item_values[ 'quantity' ] );

					/*------------------------------------------------------------------------------------------------------------------------------------------*/
					/*	If a bundled item is a sub, then grab recurring / sign-up fees and trial info in order to show the subtotal in sub price string format
					/*------------------------------------------------------------------------------------------------------------------------------------------*/

					if ( $values[ 'data' ]->bundled_items[ $item_id ]->is_sub() ) {

						$bundled_items_recurring_price = $bundled_item_price;

						$bundled_item_sign_up_fee 	= get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $product->get_sign_up_fee_excluding_tax( $item_values[ 'quantity' ] ) : $product->get_sign_up_fee_including_tax( $item_values[ 'quantity' ] );
						$bundled_item_trial_length 	= $product->subscription_trial_length;

						$bundled_item_price = $bundled_item_sign_up_fee;

						if ( $bundled_item_trial_length == 0 )
							$bundled_item_price += $bundled_items_recurring_price;

					}

					$bundled_items_price += $bundled_item_price;

				}

				$price = $bundle_price + $bundled_items_price;
				return wc_bundles_price( $price );

			}

		}

		return $price;
	}

	/**
	 * Modify the front-end subtotal of bundled items and container items depending on the bundles's pricing strategy.
	 * @param  string   $subtotal       the item subtotal
	 * @param  array    $values         the item data
	 * @param  string   $cart_item_key  the cart item key
	 * @return string                   modified subtotal string.
	 */
	function woo_bundles_item_subtotal( $subtotal, $values, $cart_item_key ) {

		global $woocommerce;

		if ( isset( $values[ 'bundled_by' ] ) ) {

			$bundle_cart_key = $values[ 'bundled_by' ];

			if ( $woocommerce->cart->cart_contents[ $bundle_cart_key ][ 'data' ]->per_product_pricing_active == false && $values[ 'data' ]->price == 0 )
				return '';
			else
				return '<small>' . __( 'Subtotal', 'woocommerce-product-bundles' ) . ': ' . $subtotal . '</small>';
		}

		if ( isset( $values[ 'bundled_items' ] ) ) {

			$bundled_items_price 			= 0;
			$bundled_items_recurring_price 	= 0;
			$bundle_price 					= get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $values[ 'data' ]->get_price_excluding_tax( $values[ 'quantity' ] ) : $values[ 'data' ]->get_price_including_tax( $values[ 'quantity' ] );

			foreach ( $values[ 'bundled_items' ] as $bundled_item_key ) {

				$item_values 	= $woocommerce->cart->cart_contents[ $bundled_item_key ];
				$item_id 		= $item_values[ 'bundled_item_id' ];
				$product 		= $item_values[ 'data' ];

				$bundled_item_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $product->get_price_excluding_tax( $item_values[ 'quantity' ] ) : $product->get_price_including_tax( $item_values[ 'quantity' ] );

				/*------------------------------------------------------------------------------------------------------------------------------------------*/
				/*	If a bundled item is a sub, then grab recurring / sign-up fees and trial info in order to show the subtotal in sub price string format
				/*------------------------------------------------------------------------------------------------------------------------------------------*/

				if ( $values[ 'data' ]->bundled_items[ $item_id ]->is_sub() ) {

					$bundled_items_recurring_price = $bundled_item_price;

					$bundled_item_sign_up_fee 	= get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $product->get_sign_up_fee_excluding_tax( $item_values[ 'quantity' ] ) : $product->get_sign_up_fee_including_tax( $item_values[ 'quantity' ] );
					$bundled_item_trial_length 	= $product->subscription_trial_length;

					$bundled_item_price = $bundled_item_sign_up_fee;

					if ( $bundled_item_trial_length == 0 )
						$bundled_item_price += $bundled_items_recurring_price;

				}

				$bundled_items_price += $bundled_item_price;

			}

			$subtotal = $bundle_price + $bundled_items_price;

			if ( $bundled_items_recurring_price > 0 ) {

				return $values[ 'data' ]->get_subscription_price_html( $this->format_product_subtotal( $values[ 'data' ], $subtotal ), $this->format_product_subtotal( $values[ 'data' ], $bundled_items_recurring_price ) );

			} else {

				return $this->format_product_subtotal( $values[ 'data' ], $subtotal );
			}
		}

		return $subtotal;
	}

	/**
	 * Outputs a formatted subtotal ( @see woo_bundles_item_subtotal() ).
	 * @param  WC_Product   $product    the product
	 * @param  string       $subtotal   formatted subtotal
	 * @return string                   modified formatted subtotal
	 */
	function format_product_subtotal( $product, $subtotal ) {

		global $woocommerce;

		$cart = $woocommerce->cart;

		$taxable = $product->is_taxable();

		// Taxable
		if ( $taxable ) {

			if ( $cart->tax_display_cart == 'excl' ) {

				$product_subtotal = wc_bundles_price( $subtotal );

				if ( $cart->prices_include_tax && $cart->tax_total > 0 )
					$product_subtotal .= ' <small class="tax_label">' . $woocommerce->countries->ex_tax_or_vat() . '</small>';

			} else {

				$product_subtotal = wc_bundles_price( $subtotal );

				if ( ! $cart->prices_include_tax && $cart->tax_total > 0 )
					$product_subtotal .= ' <small class="tax_label">' . $woocommerce->countries->inc_tax_or_vat() . '</small>';
			}

		// Non-taxable
		} else {
			$product_subtotal = wc_bundles_price( $subtotal );
		}

		return $product_subtotal;
	}

	// Debugging only
	function woo_bundles_before_cart() {

		global $woocommerce;

		$cart = $woocommerce->cart->get_cart();

		echo '<br/>';
		echo '<br/>';

		echo 'Cart Contents Total: ' . $woocommerce->cart->cart_contents_total . '<br/>';
		echo 'Cart Tax Total: ' . $woocommerce->cart->tax_total . '<br/>';
		echo 'Cart Total: ' . $woocommerce->cart->get_cart_total() . '<br/>';

		foreach ( $cart as $key => $data ) {
			echo '<br/>Cart Item - '.$key.' (' . count($data) . ' items):<br/>';

			echo 'Price: ' . $data['data']->get_price();
			echo '<br/>';

			foreach ( $data as $datakey => $value ) {
				print_r ( $datakey ); if ( is_numeric( $value ) || is_string( $value ) ) echo ': ' . $value; echo ' | ';
			}
		}
	}

}
