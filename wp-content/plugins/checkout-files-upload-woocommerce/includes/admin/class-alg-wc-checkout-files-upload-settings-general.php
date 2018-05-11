<?php
/**
 * Checkout Files Upload - General Section Settings
 *
 * @version 1.2.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 * @todo    add "Reset settings" button (to all sections);
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Checkout_Files_Upload_Settings_General' ) ) :

class Alg_WC_Checkout_Files_Upload_Settings_General extends Alg_WC_Checkout_Files_Upload_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'checkout-files-upload-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function get_settings() {

		// General Settings
		$settings = array(
			array(
				'title'     => __( 'Checkout Files Upload Options', 'checkout-files-upload-woocommerce' ),
				'type'      => 'title',
				'desc'      => __( 'Let your customers upload files on (or after) WooCommerce checkout.', 'checkout-files-upload-woocommerce' ),
				'id'        => 'alg_wc_checkout_files_upload_options',
			),
			array(
				'title'     => __( 'WooCommerce Checkout Files Upload', 'checkout-files-upload-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable', 'checkout-files-upload-woocommerce' ) . '</strong>',
				'desc_tip'  => __( 'Checkout Files Upload for WooCommerce.', 'checkout-files-upload-woocommerce' ),
				'id'        => 'alg_wc_checkout_files_upload_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_checkout_files_upload_options',
			),
		);

		// Products Tags
		$product_tags_options = array();
		$product_tags = get_terms( 'product_tag', 'orderby=name&hide_empty=0' );
		if ( ! empty( $product_tags ) && ! is_wp_error( $product_tags ) ){
			foreach ( $product_tags as $product_tag ) {
				$product_tags_options[ $product_tag->term_id ] = $product_tag->name;
			}
		}

		// Products Cats
		$product_cats_options = array();
		$product_cats = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
		if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ){
			foreach ( $product_cats as $product_cat ) {
				$product_cats_options[ $product_cat->term_id ] = $product_cat->name;
			}
		}

		// Products
		$offset = 0;
		$block_size = 256;
		while( true ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => $block_size,
				'offset'         => $offset,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
			);
			$loop = new WP_Query( $args );
			if ( ! $loop->have_posts() ) {
				break;
			}
			foreach ( $loop->posts as $post_id ) {
				$products_options[ $post_id ] = get_the_title( $post_id );
			}
			$offset += $block_size;
		}

		// Checkout Files Loop
		$settings = array_merge( $settings, array(
			array(
				'title'    => __( 'Files Options', 'checkout-files-upload-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_checkout_files_upload_options',
			),
			array(
				'title'    => __( 'Total Files', 'checkout-files-upload-woocommerce' ),
				'id'       => 'alg_checkout_files_upload_total_number',
				'desc_tip' => __( '<em>Save changes</em> after you change this number.', 'checkout-files-upload-woocommerce' ),
				'default'  => 1,
				'type'     => 'custom_number_checkout_files_upload',
				'desc'     => ( PHP_INT_MAX === apply_filters( 'alg_wc_checkout_files_upload_option', 1 ) ) ?
					'' : sprintf(
						__( 'Get %s plugin to change this number', 'checkout-files-upload-woocommerce' ),
						'<a target="_blank" href="' . esc_url( 'https://wpcodefactory.com/item/checkout-files-upload-woocommerce-plugin/' ) . '">' .
							__( 'Checkout Files Upload for WooCommerce Pro', 'checkout-files-upload-woocommerce' ) . '</a>'
						),
				'custom_attributes' => ( PHP_INT_MAX === apply_filters( 'alg_wc_checkout_files_upload_option', 1 ) ) ?
					array( 'step' => '1', 'min' => '1' ) : array( 'readonly' => 'readonly' ),
			),
		) );
		$total_number = ( PHP_INT_MAX === apply_filters( 'alg_wc_checkout_files_upload_option', 1 ) ? get_option( 'alg_checkout_files_upload_total_number', 1 ) : 1 );
		for ( $i = 1; $i <= $total_number; $i++ ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => __( 'File', 'checkout-files-upload-woocommerce' ) . ' #' . $i,
					'id'       => 'alg_checkout_files_upload_enabled_' . $i,
					'desc'     => __( 'Enabled', 'checkout-files-upload-woocommerce' ),
					'type'     => 'checkbox',
					'default'  => 'yes',
				),
				array(
					'id'       => 'alg_checkout_files_upload_required_' . $i,
					'desc'     => __( 'Required', 'checkout-files-upload-woocommerce' ),
					'type'     => 'checkbox',
					'default'  => 'no',
				),
				array(
					'id'       => 'alg_checkout_files_upload_hook_' . $i,
					'desc'     => __( 'Position', 'checkout-files-upload-woocommerce' ),
					'default'  => 'woocommerce_before_checkout_form',
					'type'     => 'select',
					'options'  => array(
						'woocommerce_before_checkout_form' => __( 'Before checkout form', 'checkout-files-upload-woocommerce' ),
						'woocommerce_after_checkout_form'  => __( 'After checkout form', 'checkout-files-upload-woocommerce' ),
						'disable'                          => __( 'Do not add on checkout', 'checkout-files-upload-woocommerce' ),
					),
					'css'      => 'width:250px;',
				),
				array(
					'desc'     => __( 'Position order (i.e. priority)', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_hook_priority_' . $i,
					'default'  => 20,
					'type'     => 'number',
					'custom_attributes' => array( 'min' => '0' ),
					'css'      => 'width:250px;',
				),
				array(
					'id'       => 'alg_checkout_files_upload_add_to_thankyou_' . $i,
					'desc'     => __( 'Add to Thank You page', 'checkout-files-upload-woocommerce' ),
					'type'     => 'checkbox',
					'default'  => 'no',
				),
				array(
					'id'       => 'alg_checkout_files_upload_add_to_myaccount_' . $i,
					'desc'     => __( 'Add to My Account page', 'checkout-files-upload-woocommerce' ),
					'type'     => 'checkbox',
					'default'  => 'no',
				),
				array(
					'desc'     => __( 'Label', 'checkout-files-upload-woocommerce' ),
					'desc_tip' => __( 'Leave blank to disable label', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_label_' . $i,
					'default'  => __( 'Please select file to upload', 'checkout-files-upload-woocommerce' ),
					'type'     => 'textarea',
					'css'      => 'width:250px;',
				),
				array(
					'desc'     => __( 'Accepted file types', 'checkout-files-upload-woocommerce' ),
					'desc_tip' => __( 'Accepted file types. E.g.: ".jpg,.jpeg,.png". Leave blank to accept all files', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_file_accept_' . $i,
					'default'  => '.jpg,.jpeg,.png',
					'type'     => 'text',
					'css'      => 'width:250px;',
				),
				array(
					'desc'     => __( 'Label: Upload button', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_label_upload_button_' . $i,
					'default'  =>  __( 'Upload', 'checkout-files-upload-woocommerce' ),
					'type'     => 'text',
					'css'      => 'width:250px;',
				),
				array(
					'desc'     => __( 'Label: Remove button', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_label_remove_button_' . $i,
					'default'  =>  __( 'Remove', 'checkout-files-upload-woocommerce' ),
					'type'     => 'text',
					'css'      => 'width:250px;',
				),
				array(
					'desc'     => __( 'Notice: Wrong file type', 'checkout-files-upload-woocommerce' ),
					'desc_tip' => __( '%s will be replaced with file name', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_notice_wrong_file_type_' . $i,
					'default'  =>  __( 'Wrong file type: "%s"!', 'checkout-files-upload-woocommerce' ),
					'type'     => 'textarea',
					'css'      => 'width:250px;',
				),
				array(
					'desc'     => __( 'Notice: File is required', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_notice_required_' . $i,
					'default'  =>  __( 'File is required!', 'checkout-files-upload-woocommerce' ),
					'type'     => 'textarea',
					'css'      => 'width:250px;',
				),
				array(
					'desc'     => __( 'Notice: File was successfully uploaded', 'checkout-files-upload-woocommerce' ),
					'desc_tip' => __( '%s will be replaced with file name', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_notice_success_upload_' . $i,
					'default'  =>  __( 'File "%s" was successfully uploaded.', 'checkout-files-upload-woocommerce' ),
					'type'     => 'textarea',
					'css'      => 'width:250px;',
				),
				array(
					'desc'     => __( 'Notice: No file selected', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_notice_upload_no_file_' . $i,
					'default'  =>  __( 'Please select file to upload!', 'checkout-files-upload-woocommerce' ),
					'type'     => 'textarea',
					'css'      => 'width:250px;',
				),
				array(
					'desc'     => __( 'Notice: File was successfully removed', 'checkout-files-upload-woocommerce' ),
					'desc_tip' => __( '%s will be replaced with file name', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_notice_success_remove_' . $i,
					'default'  =>  __( 'File "%s" was successfully removed.', 'checkout-files-upload-woocommerce' ),
					'type'     => 'textarea',
					'css'      => 'width:250px;',
				),
				array(
					'title'    => '',
					'desc'     => __( 'PRODUCTS to show this field', 'checkout-files-upload-woocommerce' ),
					'desc_tip' => __( 'To show this field only if at least one selected product is in cart, enter products here. Leave blank to show for all products.', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_show_products_in_' . $i,
					'default'  => '',
					'class'    => 'chosen_select',
					'type'     => 'multiselect',
					'options'  => $products_options,
				),
				array(
					'title'    => '',
					'desc'     => __( 'CATEGORIES to show this field', 'checkout-files-upload-woocommerce' ),
					'desc_tip' => __( 'To show this field only if at least one product of selected category is in cart, enter categories here. Leave blank to show for all products.', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_show_cats_in_' . $i,
					'default'  => '',
					'class'    => 'chosen_select',
					'type'     => 'multiselect',
					'options'  => $product_cats_options,
				),
				array(
					'title'    => '',
					'desc'     => __( 'TAGS to show this field', 'checkout-files-upload-woocommerce' ),
					'desc_tip' => __( 'To show this field only if at least one product of selected tag is in cart, enter tags here. Leave blank to show for all products.', 'checkout-files-upload-woocommerce' ),
					'id'       => 'alg_checkout_files_upload_show_tags_in_' . $i,
					'default'  => '',
					'class'    => 'chosen_select',
					'type'     => 'multiselect',
					'options'  => $product_tags_options,
				),
			) );
		}
		$settings = array_merge( $settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_checkout_files_upload_options',
			),
		) );

		return $settings;
	}

}

endif;

return new Alg_WC_Checkout_Files_Upload_Settings_General();
