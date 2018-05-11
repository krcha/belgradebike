<?php
/**
 * Product bundle add to cart template.
 * @version 4.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

global $woocommerce, $product, $post, $woocommerce_bundles;

$per_product_pricing = $product->per_product_pricing_active;

?>

<?php do_action('woocommerce_before_add_to_cart_form'); ?>

<form method="post" enctype='multipart/form-data' >

	<?php

	foreach ( $bundled_items as $bundled_item ) {

		$bundled_item_id 	= $bundled_item->item_id;
		$bundled_product 	= $bundled_item->product;
		$item_quantity 		= $bundled_item->quantity;

		?>

		<div class="bundled_product bundled_product_summary product" <?php echo ( ! $bundled_item->is_visible() ? 'style=display:none;' : '' ); ?> >

		<?php
			if ( $bundled_product->product_type == 'simple' || $bundled_product->product_type == 'subscription' ) {

				if ( $bundled_item->is_visible() ) {

					// title template
					wc_bundles_get_template( 'single-product/bundled-item-title.php', array(
							'quantity' 			=> $item_quantity,
							'title' 			=> $bundled_item->get_title()
						), false, $woocommerce_bundles->woo_bundles_plugin_path() . '/templates/' );

					// image template
					if ( $bundled_item->is_thumbnail_visible() )
						wc_bundles_get_template( 'single-product/bundled-item-image.php', array( 'post_id' => $bundled_product->id ), false, $woocommerce_bundles->woo_bundles_plugin_path() . '/templates/' );

				}

				?><div class="details"><?php

					if ( $bundled_item->is_visible() ) {

						// description template
						wc_bundles_get_template( 'single-product/bundled-item-description.php', array(
								'description' 			=> $bundled_item->get_description()
							), false, $woocommerce_bundles->woo_bundles_plugin_path() . '/templates/' );
					}

					// Availability
					$availability = $woocommerce_bundles->helpers->get_bundled_product_availability( $bundled_product, $item_quantity );

					?>

					<div class="cart" data-bundled-item-id="<?php echo $bundled_item_id; ?>" data-product_id="<?php echo $post->ID . str_replace( '_', '', $bundled_item_id ); ?>" data-bundle-id="<?php echo $post->ID; ?>">

						<?php
						if ( $availability[ 'availability' ] )
							echo apply_filters( 'woocommerce_stock_html', '<p class="stock '. $availability[ 'class' ] .'">' . $availability[ 'availability' ] . '</p>', $availability[ 'availability' ] );

						?>
						<div class="bundled_item_wrap">
							<?php

								$bundled_item->add_price_filters();

								if ( $per_product_pricing )
									wc_bundles_get_template( 'single-product/bundled-item-price.php', array(
										'bundled_item' => $bundled_item ), false, $woocommerce_bundles->woo_bundles_plugin_path() . '/templates/' );

								// Compatibility with plugins that normally hook to woocommerce_before_add_to_cart_button
								do_action( 'woocommerce_bundled_product_add_to_cart', $bundled_product->id, $bundled_item_id );

								$bundled_item->remove_price_filters();

								?>
								<div class="quantity" style="display:none;"><input class="qty" type="hidden" name="bundled_item_quantity" value="<?php echo $item_quantity; ?>" /></div>
						</div>

					</div>

				</div>
				<?php

			} elseif ( $bundled_product->product_type == 'variable' ) {

				if ( $bundled_item->is_visible() ) {

					// title template
					wc_bundles_get_template( 'single-product/bundled-item-title.php', array(
							'quantity' 			=> $item_quantity,
							'title' 			=> $bundled_item->get_title()
						), false, $woocommerce_bundles->woo_bundles_plugin_path() . '/templates/' );

					// image template
					if ( $bundled_item->is_thumbnail_visible() )
						wc_bundles_get_template( 'single-product/bundled-item-image.php', array( 'post_id' => $bundled_product->id ), false, $woocommerce_bundles->woo_bundles_plugin_path() . '/templates/' );

				}

				?><div class="details"><?php

					if ( $bundled_item->is_visible() ) {

						// description template
						wc_bundles_get_template( 'single-product/bundled-item-description.php', array(
								'description' 			=> $bundled_item->get_description()
							), false, $woocommerce_bundles->woo_bundles_plugin_path() . '/templates/' );

					}

					?>
					<div class="variations_form cart" data-product_variations="<?php echo esc_attr( json_encode( $available_variations[ $bundled_item_id ] ) ); ?>" data-bundled-item-id="<?php echo $bundled_item_id; ?>" data-product_id="<?php echo $post->ID . str_replace('_', '', $bundled_item_id); ?>" data-bundle-id="<?php echo $post->ID; ?>">
						<div class="variations">
							<?php

							$loop = 0; foreach ( $attributes[ $bundled_item_id ] as $name => $options ) { $loop++; ?>
								<div class="attribute-options">
								<label for="<?php echo sanitize_title( $name ) . '_' . $bundled_item_id; ?>"><?php if ( function_exists( 'ssc_remove_accents' ) ) { echo ssc_remove_accents( wc_bundles_attribute_label( $name ) ); } else { echo wc_bundles_attribute_label( $name ); } ?></label>
								<select id="<?php echo esc_attr( sanitize_title( $name ) . '_' . $bundled_item_id ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>">
									<option value=""><?php echo __( 'Choose an option', 'woocommerce' ) ?>&hellip;</option>
									<?php
										if( is_array( $options ) ) {

											if ( isset( $_REQUEST[ 'bundle_attribute_' . sanitize_title( $name ) ][ $bundled_item_id ] ) ) {
												$selected_value = $_REQUEST[ 'bundle_attribute_' . sanitize_title( $name ) ][ $bundled_item_id ];
											} elseif ( isset( $selected_attributes[ $bundled_item_id ][ sanitize_title( $name ) ] ) ) {
												$selected_value = $selected_attributes[ $bundled_item_id ][ sanitize_title( $name ) ];
											} else {
												$selected_value = '';
											}

											// Placeholder: Do not show filtered-out (disabled) options

											if ( taxonomy_exists( $name ) ) {

												$orderby = wc_bundles_attribute_order_by( $name );

												switch ( $orderby ) {
													case 'name' :
														$args = array( 'orderby' => 'name', 'hide_empty' => false, 'menu_order' => false );
													break;
													case 'id' :
														$args = array( 'orderby' => 'id', 'order' => 'ASC', 'menu_order' => false );
													break;
													case 'menu_order' :
														$args = array( 'menu_order' => 'ASC' );
													break;
												}

												$terms = get_terms( $name, $args );

												foreach ( $terms as $term ) {
													if ( ! in_array( $term->slug, $options ) )
														continue;

													echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
												}
											} else {

												foreach ( $options as $option ) {
													echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
												}

											}

										}
									?>
								</select></div><?php

								if ( sizeof( $attributes[ $bundled_item_id ] ) == $loop ) {
									echo '<a class="reset_variations" href="#reset_' . $bundled_item_id .'">'.__( 'Clear selection', 'woocommerce' ).'</a>';
								}

							}
						?>

						</div>

						<?php

						$bundled_item->add_price_filters();

						// Compatibility with plugins that normally hook to woocommerce_before_add_to_cart_button
						do_action( 'woocommerce_bundled_product_add_to_cart', $bundled_product->id, $bundled_item_id );

						$bundled_item->remove_price_filters();

						?>

						<div class="single_variation_wrap bundled_item_wrap" style="display:none;">
							<div class="single_variation"></div>
							<div class="variations_button">
								<input type="hidden" name="variation_id" value="" />
								<input class="qty" type="hidden" name="bundled_item_quantity" value="<?php echo $item_quantity; ?>" />
							</div>
						</div>

					</div>
				</div>
			<?php
			}
		?>

		</div>

	<?php } ?>

	<div class="cart bundle_form bundle_form_<?php echo $post->ID; ?>" data-bundle_price_data="<?php echo esc_attr( json_encode( $bundle_price_data ) ); ?>" data-bundled_item_quantities="<?php echo esc_attr( json_encode( $bundled_item_quantities ) ); ?>" data-bundle-id="<?php echo $post->ID; ?>">
	<?php do_action('woocommerce_before_add_to_cart_button'); ?>

		<div class="bundle_wrap" style="display:none;">
			<div class="bundle_price"></div>
			<?php
				// Bundle Availability
				$availability = $product->get_availability();

				if ( $availability[ 'availability' ] )
					echo apply_filters( 'woocommerce_stock_html', '<p class="stock '.$availability[ 'class' ].'">'.$availability[ 'availability' ].'</p>', $availability[ 'availability' ] );
			?>
			<div class="bundle_button">
				<?php

				foreach ( $bundled_items as $bundled_item_id => $bundled_item ) {

					$bundled_item_id = $bundled_item->item_id;
					$bundled_product = $bundled_item->product;

					if ( $bundled_product->product_type == 'variable' ) {

						?><input type="hidden" name="bundle_variation_id[<?php echo $bundled_item_id; ?>]" value="" /><?php
						foreach ( $attributes[ $bundled_item_id ] as $name => $options ) { ?>
							<input type="hidden" name="bundle_attribute_<?php echo sanitize_title($name) . '[' . $bundled_item_id . ']'; ?>" value=""><?php
						}
					}
				}

				if ( ! $product->is_sold_individually() )
					woocommerce_quantity_input( array ( 'min_value' => 1 ) );

				?>
				<button type="submit" class="single_add_to_cart_button bundle_add_to_cart_button button alt"><?php echo apply_filters( 'single_add_to_cart_text', __( 'Add to cart', 'woocommerce' ), $product->product_type ); ?></button>
			</div>
			<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</div>

</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
