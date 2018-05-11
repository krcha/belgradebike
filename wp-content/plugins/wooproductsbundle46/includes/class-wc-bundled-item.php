<?php

/**
 * Bundled Item Container.
 *
 * The bunded item class is a container that initializes and holds all pricing, availability and variation/attribute-related data of a bundled item.
 *
 * @class 	WC_Bundled_Item
 * @version 4.6.1
 * @since  4.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class WC_Bundled_Item {

	var $item_id;
	var $item_data;

	var $product_id;
	var $product;

	var $bundle_id;

	var $quantity;
	var $discount;
	var $sign_up_discount;

	var $per_product_pricing;

	var $title;
	var $description;
	var $visibility;

	var $selection_overrides;
	var $allowed_variations;

	var $sold_individually;
	var $purchasable;
	var $on_sale;
	var $nyp;

	var $stock_status;

	var $min_price;
	var $max_price;
	var $min_regular_price;
	var $max_regular_price;

	var $min_recurring_price;
	var $max_recurring_price;
	var $min_regular_recurring_price;
	var $max_regular_recurring_price;

	var $min_price_incl_tax;
	var $min_price_excl_tax;

	var $product_attributes;
	var $selected_product_attributes;

	var $product_variations;

	function __construct( $bundled_item_id, $parent ) {

		$this->item_id 		= $bundled_item_id;
		$this->product_id 	= $parent->bundle_data[ $bundled_item_id ][ 'product_id' ];
		$this->bundle_id 	= $parent->id;

		$this->item_data = $parent->bundle_data[ $bundled_item_id ];

		$this->purchasable = true;

		do_action( 'woocommerce_before_init_bundled_item', $this );

		$bundled_product = get_product( $this->product_id );

		// if not present, item cannot be purchased
		if ( ! $bundled_product || ! $bundled_product->is_purchasable() )
			$this->purchasable = false;
		else
			$this->product = $bundled_product;

		if ( $this->purchasable ) {

			$this->title 				= ! empty( $this->item_data[ 'override_title' ] ) && $this->item_data[ 'override_title' ] == 'yes' ? $this->item_data[ 'product_title' ] : $bundled_product->post->post_title;
			$this->description 			= ! empty( $this->item_data[ 'override_description' ] ) && $this->item_data[ 'override_description' ] == 'yes' ? $this->item_data[ 'product_description' ] : $bundled_product->post->post_excerpt;
			$this->visibility 			= ! empty( $this->item_data[ 'visibility' ] ) && $this->item_data[ 'visibility' ] == 'hidden' ? 'hidden' : 'visible';
			$this->hide_thumbnail 		= ! empty( $this->item_data[ 'hide_thumbnail' ] ) && $this->item_data[ 'hide_thumbnail' ] == 'yes' ? 'yes' : 'no';
			$this->quantity 			= ! empty( $this->item_data[ 'bundle_quantity' ] ) ? ( int ) $this->item_data[ 'bundle_quantity' ] : 1;
			$this->discount 			= ! empty( $this->item_data[ 'bundle_discount' ] ) ? ( double ) $this->item_data[ 'bundle_discount' ] : 0.0;
			$this->sign_up_discount 	= ! empty( $this->item_data[ 'bundle_sign_up_discount' ] ) ? ( double ) $this->item_data[ 'bundle_sign_up_discount' ] : 0.0;
			$this->selection_overrides	= ! empty( $this->item_data[ 'override_defaults' ] ) && $this->item_data[ 'override_defaults' ] == 'yes' ? $this->item_data[ 'bundle_defaults' ] : '';
			$this->allowed_variations 	= ! empty( $this->item_data[ 'filter_variations' ] ) && $this->item_data[ 'filter_variations' ] == 'yes' ? $this->item_data[ 'allowed_variations' ] : '';
			$this->per_product_pricing 	= $parent->per_product_pricing_active;
			$this->sold_individually 	= false;
			$this->on_sale 				= false;
			$this->nyp 					= false;

			$this->init();
		}

		do_action( 'woocommerce_after_init_bundled_item', $this );
	}

	/**
	 * Initializes a bundled item for access by the container: Calculates min and max prices, checks availability info, etc.
	 * @return void
	 */
	function init() {

		global $woocommerce_bundles;

		$product_id 		= $this->product_id;
		$bundled_product 	= $this->product;

		$this->add_price_filters();

		/*-----------------------------------------------------------------------------------*/
		/*	Simple Subs
		/*-----------------------------------------------------------------------------------*/

		if ( $bundled_product->product_type == 'subscription' ) {

			if ( $bundled_product->is_sold_individually() )
				$this->sold_individually = true;

			if ( ! $bundled_product->is_in_stock() || ! $bundled_product->has_enough_stock( $this->quantity ) )
				$this->stock_status = 'out-of-stock';

			if ( $bundled_product->is_on_backorder() && $bundled_product->backorders_require_notification() )
				$this->stock_status = 'available-on-backorder';

			//	Sign up price

			$regular_signup_fee = $bundled_product->get_sign_up_fee();
			$signup_fee			= $this->get_sign_up_fee( $regular_signup_fee, $bundled_product );

			$this->min_regular_price 	= $this->max_regular_price 	= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $regular_signup_fee );
			$this->min_price 			= $this->max_price 			= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $signup_fee );

			if ( $regular_signup_fee > $signup_fee )
				$this->on_sale = true;

			if ( $woocommerce_bundles->helpers->is_wc_21() ) {

				if ( $woocommerce_bundles->helpers->wc_option_tax_display_shop == 'excl' ) {
					$this->min_price_excl_tax = $this->min_price;
					$this->min_price_incl_tax = $bundled_product->get_price_including_tax( $this->quantity, $this->min_price ) / $this->quantity;
				} else {
					$this->min_price_incl_tax = $this->min_price;
					$this->min_price_excl_tax = $bundled_product->get_price_excluding_tax( $this->quantity, $this->min_price ) / $this->quantity;
				}
			}

			//	Recurring price

			$regular_recurring_fee 	= $this->get_regular_price( $bundled_product->regular_price, $bundled_product );
			$recurring_fee			= $this->get_price( $bundled_product->price, $bundled_product );

			$this->min_regular_recurring_price 	= $this->max_regular_recurring_price 	= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $regular_recurring_fee );
			$this->min_recurring_price 			= $this->max_recurring_price 			= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $recurring_fee );

			if ( $regular_recurring_fee > $recurring_fee )
				$this->on_sale = true;

			// if there is NO free trial, we need to include the recurring price in the initial amount

			$trial_length = WC_Subscriptions_Product::get_trial_length( $bundled_product );

			if ( $trial_length == 0 ) {
				$this->min_regular_price 	= $this->max_regular_price 	= $this->min_regular_price + $this->min_regular_recurring_price;
				$this->min_price 			= $this->max_price 			= $this->min_price + $this->min_recurring_price;
			}

		/*-----------------------------------------------------------------------------------*/
		/*	Simple Products
		/*-----------------------------------------------------------------------------------*/

		} elseif ( $bundled_product->product_type == 'simple' ) {

			if ( $bundled_product->is_sold_individually() )
				$this->sold_individually = true;

			if ( ! $bundled_product->is_in_stock() || ! $bundled_product->has_enough_stock( $this->quantity ) )
				$this->stock_status = 'out-of-stock';

			if ( $bundled_product->is_on_backorder() && $bundled_product->backorders_require_notification() )
				$this->stock_status = 'available-on-backorder';

			$regular_price 	= $this->get_regular_price( $bundled_product->regular_price, $bundled_product );
			$price			= $this->get_price( $bundled_product->price, $bundled_product );

			// Name your price support
			if ( $woocommerce_bundles->compatibility->is_nyp( $product_id ) ) {

				$bundled_product->regular_price = $bundled_product->price = WC_Name_Your_Price_Helpers::get_minimum_price( $product_id ) ? WC_Name_Your_Price_Helpers::get_minimum_price( $product_id ) : 0;
				$this->nyp = true;
			}

			$this->min_regular_price 	= $this->max_regular_price 	= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $regular_price );
			$this->min_price 			= $this->max_price 			= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_product, $price );

			if ( $regular_price > $price )
				$this->on_sale = true;

			if ( $woocommerce_bundles->helpers->is_wc_21() ) {

				if ( $woocommerce_bundles->helpers->wc_option_tax_display_shop == 'excl' ) {
					$this->min_price_excl_tax = $this->min_price;
					$this->min_price_incl_tax = $bundled_product->get_price_including_tax( $this->quantity, $this->min_price ) / $this->quantity;
				} else {
					$this->min_price_incl_tax = $this->min_price;
					$this->min_price_excl_tax = $bundled_product->get_price_excluding_tax( $this->quantity, $this->min_price ) / $this->quantity;
				}
			}

		/*-----------------------------------------------------------------------------------*/
		/*	Variable Products
		/*-----------------------------------------------------------------------------------*/

		} elseif ( $bundled_product->product_type == 'variable' ) {

			$min_variation_regular_price 	= '';
			$min_variation_price 			= '';
			$max_variation_regular_price 	= '';
			$max_variation_price 			= '';

			$min_variation_price_incl_tax 	= '';
			$min_variation_price_excl_tax 	= '';

			// check stock status of variations - if all of them are out of stock, the product cannot be purchased
			$variation_in_stock_exists 		= false;
			$all_variations_on_backorder 	= true;

			// sold individually status
			if ( $bundled_product->is_sold_individually() == 'yes' )
				$this->sold_individually = true;

			// Process variations
			foreach ( $bundled_product->get_children() as $child_id ) {

				// Do not continue if variation is filtered
				if ( ! empty( $this->allowed_variations ) ) {
					if ( is_array( $this->allowed_variations ) && ! in_array( $child_id, $this->allowed_variations )  )
						continue;
				}

				// Use this to improve performance instead of grabbing it like so: $variation = $bundled_product->get_child( $child_id )
				$variation = $woocommerce_bundles->helpers->get_variation( $child_id, $bundled_product );

				if ( ! empty( $variation->variation_id ) ) {

					// check stock status of variation - if one of them is in stock, the product can be purchased
					if ( ! $variation_in_stock_exists && $variation->is_in_stock() && $variation->has_enough_stock( $this->quantity ) )
						$variation_in_stock_exists = true;

					if ( $all_variations_on_backorder && ( ! $variation->is_on_backorder() || ! $bundled_product->backorders_require_notification() ) )
						$all_variations_on_backorder = false;

					// Price info

					// Calculate min / max variation price only if filters are present.
					// Otherwise, its much faster to grab those from the parent product.

					if ( $this->per_product_pricing && ! empty( $this->allowed_variations ) ) {

						$variation_regular_price 	= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $variation, $this->get_regular_price( $variation->regular_price, $variation ) );
						$variation_price 			= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $variation, $this->get_price( $variation->price, $variation ) );

						// on sale status
						if ( $variation_regular_price > $variation_price )
							$this->on_sale = true;

						// calculate prices incl and excl tax
						if ( $woocommerce_bundles->helpers->is_wc_21() && $woocommerce_bundles->helpers->wc_option_calculate_taxes == 'yes' ) {

							if ( $woocommerce_bundles->helpers->wc_option_tax_display_shop == 'excl' ) {

								$variation_price_excl_tax = $variation_price;
								$variation_price_incl_tax = $variation->get_price_including_tax( 1, $this->get_price( $variation_price, $variation ) );

							} else {

								$variation_price_incl_tax = $variation_price;
								$variation_price_excl_tax = $variation->get_price_excluding_tax( 1, $this->get_price( $variation_price, $variation ) );
							}

						}

						// lowest price
						if ( ! is_numeric( $min_variation_regular_price ) || $variation_regular_price < $min_variation_regular_price )
							$min_variation_regular_price = $variation_regular_price;
						if ( ! is_numeric( $min_variation_price ) || $variation_price < $min_variation_price )
							$min_variation_price = $variation_price;

						// highest price
						if ( ! is_numeric( $max_variation_regular_price ) || $variation_regular_price > $max_variation_regular_price )
							$max_variation_regular_price = $variation_regular_price;
						if ( ! is_numeric( $max_variation_price ) || $variation_price > $max_variation_price )
							$max_variation_price = $variation_price;

						// taxed
						if ( $woocommerce_bundles->helpers->is_wc_21() && $woocommerce_bundles->helpers->wc_option_calculate_taxes == 'yes' ) {

							if ( ! is_numeric( $min_variation_price_incl_tax ) || $variation_price_incl_tax < $min_variation_price_incl_tax )
								$min_variation_price_incl_tax = $variation_price_incl_tax;

							if ( ! is_numeric( $min_variation_price_excl_tax ) || $variation_price_excl_tax < $min_variation_price_excl_tax )
								$min_variation_price_excl_tax = $variation_price_excl_tax;

						}

					}

				}
			}

			if ( $variation_in_stock_exists == false ) {
				$this->stock_status = 'out-of-stock';
			}

			if ( $all_variations_on_backorder ) {
				$this->stock_status = 'available-on-backorder';
			}

			// When no variation filters are present, its much faster to grab the min/max variation price data from the parent product meta.
			if ( empty( $this->allowed_variations ) ) {

				if ( ! empty( $this->discount ) ) {

					// Grab the min/max regular price variation since discounts are calculated on top of the regular price only.
					$min_variation_id 	= get_post_meta( $this->product_id, '_min_regular_price_variation_id', true );
					$min_variation 		= $woocommerce_bundles->helpers->get_variation( $min_variation_id, $bundled_product );

					$min_variation_regular_price 	= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $min_variation, $this->get_regular_price( $min_variation->regular_price, $min_variation ) );
					$min_variation_price 			= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $min_variation, $this->get_price( $min_variation->price, $min_variation ) );


					$max_variation_id 	= get_post_meta( $this->product_id, '_max_regular_price_variation_id', true );
					$max_variation 		= $woocommerce_bundles->helpers->get_variation( $max_variation_id, $bundled_product );

					$max_variation_regular_price 	= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $max_variation, $this->get_regular_price( $max_variation->regular_price, $max_variation ) );
					$max_variation_price 			= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $max_variation, $this->get_price( $max_variation->price, $max_variation ) );

					$this->on_sale = $max_variation_regular_price > 0 || $max_variation_price > 0;

				} else {

					// Grab the min/max price variation since there is no discount.
					$min_variation_id 	= get_post_meta( $this->product_id, '_min_price_variation_id', true );
					$min_variation 		= $woocommerce_bundles->helpers->get_variation( $min_variation_id, $bundled_product );

					$min_variation_regular_price 	= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $min_variation, $this->get_regular_price( $min_variation->regular_price, $min_variation ) );
					$min_variation_price 			= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $min_variation, $this->get_price( $min_variation->price, $min_variation ) );


					$max_variation_id 	= get_post_meta( $this->product_id, '_max_price_variation_id', true );
					$max_variation 		= $woocommerce_bundles->helpers->get_variation( $max_variation_id, $bundled_product );

					$max_variation_regular_price 	= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $max_variation, $this->get_regular_price( $max_variation->regular_price, $max_variation ) );
					$max_variation_price 			= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $max_variation, $this->get_price( $max_variation->price, $max_variation ) );

					// sale status can be obtained from the parent since all variations are active
					$this->on_sale = $bundled_product->is_on_sale();
				}

			}

			$this->min_price 			= ( $min_variation_regular_price < $min_variation_price ) ? $min_variation_regular_price : $min_variation_price;
			$this->min_regular_price 	= $min_variation_regular_price;

			$this->max_price 			= ( $max_variation_regular_price < $max_variation_price ) ? $max_variation_regular_price : $max_variation_price;
			$this->max_regular_price 	= $max_variation_regular_price;

			if ( $woocommerce_bundles->helpers->is_wc_21() ) {

				$this->min_price_excl_tax = $min_variation_price_excl_tax;
				$this->min_price_incl_tax = $min_variation_price_incl_tax;

			}

		}

		$this->remove_price_filters();

	}

	/**
	 * Bundled item sale status.
	 * @return  boolean  true if on sale
	 */
	function is_on_sale() {

		$on_sale = $this->on_sale;

		if ( $this->is_out_of_stock() )
			return false;

		return $on_sale;
	}

	/**
	 * Bundled item purchasable status.
	 * @return  boolean  true if purchasable
	 */
	function is_purchasable() {

		return $this->purchasable;
	}

	/**
	 * Bundled item out of stock status.
	 * @return  boolean  true if out of stock
	 */
	function is_out_of_stock() {

		if ( $this->stock_status == 'out-of-stock' )
			return true;

		return false;
	}

	/**
	 * Bundled item backorder status.
	 * @return  boolean  true if on backorder
	 */
	function is_on_backorder() {

		if ( $this->stock_status == 'available-on-backorder' )
			return true;

		return false;
	}

	/**
	 * Bundled item sold individually status.
	 * @return boolean  true if sold individually
	 */
	function is_sold_individually() {

		if ( $this->sold_individually == true )
			return true;

		return false;
	}

	/**
	 * Bundled item name-your-price status.
	 * @return boolean  true if item is NYP
	 */
	function is_nyp() {

		return $this->nyp;
	}

	/**
	 * Check if the product has variables to adjust before adding to cart.
	 * Conditions: ( is NYP ) or ( has required addons ) or ( has options )
	 * @return boolean  true if the item has variables to adjust before adding to cart
	 */
	function has_variables() {

		global $woocommerce_bundles;

		if ( $this->is_nyp() || $woocommerce_bundles->compatibility->has_required_addons( $this->product_id ) || $this->product->product_type == 'variable' )
			return true;

		return false;
	}

	/**
	 * Check if the item is a subscription.
	 * @return boolean  true if the item is a sub
	 */
	function is_sub() {

		if ( $this->product->product_type == 'subscription' )
			return true;

		return false;
	}

	/**
	 * Returns the variation attributes array if this product is variable.
	 * @return array
	 */
	function get_product_variation_attributes() {

		if ( ! empty( $this->product_attributes ) )
			return $this->product_attributes;

		if ( $this->product->product_type == 'variable' ) {

			$this->product_attributes = $this->product->get_variation_attributes();

			return $this->product_attributes;
		}

		return false;

	}

	/**
	 * Returns the selected variation attributes if this product is variable.
	 * @return array
	 */
	function get_selected_product_variation_attributes() {

		if ( ! empty( $this->selected_product_attributes ) )
			return $this->selected_product_attributes;

		if ( $this->product->product_type == 'variable' ) {

			if ( ! empty( $this->selection_overrides ) ) {
				$selected_product_attributes = $this->selection_overrides;
			} else {
				$selected_product_attributes = ( array ) maybe_unserialize( get_post_meta( $this->product_id, '_default_attributes', true ) );
			}

			$this->selected_product_attributes = apply_filters( 'woocommerce_product_default_attributes', $selected_product_attributes );

			return $this->selected_product_attributes;
		}

		return false;
	}

	/**
	 * Returns this product's available variations array.
	 * TODO: Needs to be optimized by adding a filter in core to prevent loading filtered-out variations.
	 * @return array
	 */
	function get_product_variations() {

		if ( ! empty( $this->product_variations ) )
			return $this->product_variations;

		if ( $this->product->product_type == 'variable' ) {

			do_action( 'woocommerce_before_init_bundled_item', $this );
			add_filter( 'woocommerce_available_variation', array( $this, 'bundled_item_available_variation' ), 10, 3 );
			$this->add_price_filters();

			$bundled_item_variations = $this->product->get_available_variations();

			$this->remove_price_filters();
			remove_filter( 'woocommerce_available_variation', array( $this, 'bundled_item_available_variation' ), 10, 3 );
			do_action( 'woocommerce_after_init_bundled_item', $this );

			// add only active variations
			foreach ( $bundled_item_variations as $variation_data ) {

				if ( ! empty( $variation_data ) )
					$this->product_variations[] = $variation_data;
				else
					continue;
			}

			return $this->product_variations;
		}

		return false;
	}

	/**
	 * Modifies the results of get_available_variations() to implement variation filtering and bundle discounts for variable products.
	 * Also calculates variation prices incl. or excl. tax.
	 * @param  array                  $variation_data     unmodified variation data
	 * @param  WC_Product             $bundled_product    the bundled product
	 * @param  WC_Product_Variation   $bundled_variation  the variation in question
	 * @return array                                      modified variation data
	 */
	function bundled_item_available_variation( $variation_data, $bundled_product, $bundled_variation ) {

		global $woocommerce_bundles;

		$bundled_item_id = $this->item_id;

		// Disable if certain conditions are met
		if ( ! empty( $this->allowed_variations ) ) {
			if ( ! is_array( $this->allowed_variations ) )
				return array();
			if ( ! in_array( $bundled_variation->variation_id, $this->allowed_variations ) )
				return array();
		}

		if ( $bundled_variation->price === '' ) {
			return array();
		}

		// Modify product id for JS
		$variation_data[ 'product_id' ] = $bundled_item_id;

		// Add price info
		$variation_data[ 'regular_price' ] 	= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_variation, $this->get_regular_price( $bundled_variation->regular_price, $bundled_variation ) );
		$variation_data[ 'price' ]			= $woocommerce_bundles->helpers->get_product_price_incl_or_excl_tax( $bundled_variation, $this->get_price( $bundled_variation->price, $bundled_variation ) );


		$variation_data[ 'price_html' ]	= $this->per_product_pricing ? '<p class="price">' . $bundled_variation->get_price_html() . '</p>' : '';

		$availability = $woocommerce_bundles->helpers->get_bundled_product_availability( $bundled_variation, $this->quantity );

		if ( ! $bundled_variation->is_in_stock() || ! $bundled_variation->has_enough_stock( $this->quantity ) )
			$variation_data[ 'is_in_stock' ] = false;

		if ( $bundled_variation->is_on_backorder() && $bundled_product->backorders_require_notification() )
			$variation_data[ 'is_on_backorder' ] = 'available-on-backorder';

		$availability_html = ( ! empty( $availability[ 'availability' ] ) ) ? apply_filters( 'woocommerce_stock_html', '<p class="stock ' . $availability[ 'class' ] . '">'. $availability[ 'availability' ].'</p>', $availability[ 'availability' ]  ) : '';

		$variation_data[ 'availability_html' ] = $availability_html;

		return $variation_data;
	}

	/**
	 * Add price filters to implement bundle discounts.
	 * @return void
	 */
	function add_price_filters() {

		add_filter( 'woocommerce_get_price', array( $this, 'get_price' ), 15, 2 );
		add_filter( 'woocommerce_get_sale_price', array( $this, 'get_sale_price' ), 15, 2 );
		add_filter( 'woocommerce_get_regular_price', array( $this, 'get_regular_price' ), 15, 2 );
		add_filter( 'woocommerce_get_price_html', array( $this, 'get_price_html' ), 10, 2 );
		add_filter( 'woocommerce_get_variation_price_html', array( $this, 'get_price_html' ), 10, 2 );
	}

	/**
	 * Removes discount filters.
	 * @return void
	 */
	function remove_price_filters() {

		remove_filter( 'woocommerce_get_price', array( $this, 'get_price' ), 15, 2 );
		remove_filter( 'woocommerce_get_sale_price', array( $this, 'get_sale_price' ), 15, 2 );
		remove_filter( 'woocommerce_get_regular_price', array( $this, 'get_regular_price' ), 15, 2 );
		remove_filter( 'woocommerce_get_price_html', array( $this, 'get_price_html' ), 10, 2 );
		remove_filter( 'woocommerce_get_variation_price_html', array( $this, 'get_price_html' ), 10, 2 );
	}

	/**
	 * Filter get_price() calls for bundled products to include discounts.
	 * @param  double       $price      unmodified price
	 * @param  WC_Product   $product    the bundled product
	 * @return double                   modified price
	 */
	function get_price( $price, $product ) {

		if ( $product->id !== $this->product->id )
			return $price;

		if ( ! $this->per_product_pricing )
			return 0;

		$regular_price = $product->regular_price;

		$discount = $this->discount;

		return empty( $discount ) || empty( $regular_price ) ? ( double ) $price : round( ( double ) $regular_price * ( 100 - $discount ) / 100, 2 );
	}

	/**
	 * Filter get_sale_price() calls for bundled products to include discounts.
	 * @param  double       $price      unmodified reg price
	 * @param  WC_Product   $product    the bundled product
	 * @return double                   modified reg price
	 */
	function get_sale_price( $sale_price, $product ) {

		if ( $product->id !== $this->product->id )
			return $sale_price;

		if ( ! $this->per_product_pricing )
			return 0;

		$discount = $this->discount;

		return empty( $discount ) ? $sale_price : $product->get_price();
	}

	/**
	 * Filter get_regular_price() calls for bundled products to include discounts.
	 * @param  double       $price      unmodified reg price
	 * @param  WC_Product   $product    the bundled product
	 * @return double                   modified reg price
	 */
	function get_regular_price( $regular_price, $product ) {

		if ( $product->id !== $this->product->id )
			return $regular_price;

		if ( ! $this->per_product_pricing )
			return 0;

		$price = $product->price;

		return empty( $regular_price ) ? ( double ) $price : ( double ) $regular_price;

	}

	/**
	 * Filter the html price string of bundled items to show the correct price with discount and tax - needs to be hidden in per-product pricing mode.
	 * @param  string      $price_html    unmodified price string
	 * @param  WC_Product  $product       the bundled product
	 * @return string                     modified price string
	 */
	function get_price_html( $price_html, $product ) {

		global $woocommerce_bundles;

		if ( ! isset( $product->is_filtered_price_html ) ) {

			if ( ! $this->per_product_pricing )
				return '';

			if ( ! $woocommerce_bundles->helpers->is_wc_21() ) {

				$product->sale_price = $this->get_sale_price( $product->sale_price, $product );

				$product->is_filtered_price_html = 'yes';

				$price_html = $product->get_price_html();
			}

		}
		/* translators: for quantity use %2$s */
		return apply_filters( 'woocommerce_bundled_item_price_html', $this->quantity > 1 ? sprintf( __( '%1$s <span class="bundled_item_price_quantity">/ pc.</span>', 'woocommerce-product-bundles' ), $price_html, $this->quantity ) : $price_html, $price_html, $this );
	}

	/**
	 * Filter get_sign_up_fee() calls for bundled subs to include discounts.
	 * @param  double       $price      unmodified price
	 * @param  WC_Product   $product    the bundled sub
	 * @return double                   modified price
	 */
	function get_sign_up_fee( $sign_up_fee, $product ) {

		if ( $product->id !== $this->product->id )
			return $price;

		if ( ! $this->per_product_pricing )
			return 0;

		$discount = $this->sign_up_discount;

		return empty( $discount ) ? ( double ) $sign_up_fee : ( double ) $sign_up_fee * ( 100 - $discount ) / 100;
	}

	/**
	 * Item title.
	 * @return string item title
	 */
	function get_title() {
		return apply_filters( 'woocommerce_bundled_item_title', $this->title, $this );
	}

	/**
	 * Item title.
	 * @return string item title
	 */
	function get_description() {
		return apply_filters( 'woocommerce_bundled_item_description', $this->description, $this );
	}

	/**
	 * Visible or hidden item.
	 * @return boolean true if visible
	 */
	function is_visible() {
		return $this->visibility == 'visible' ? true : false;
	}

	/**
	 * Visible or hidden item thumbnail.
	 * @return boolean true if visible
	 */
	function is_thumbnail_visible() {
		return $this->hide_thumbnail == 'yes' ? false : true;
	}


}
