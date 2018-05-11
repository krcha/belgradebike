<?php
/**
 * Checkout Files Upload - Emails Section Settings
 *
 * @version 1.1.0
 * @since   1.1.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Checkout_Files_Upload_Settings_Emails' ) ) :

class Alg_WC_Checkout_Files_Upload_Settings_Emails extends Alg_WC_Checkout_Files_Upload_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	public function __construct() {
		$this->id   = 'emails';
		$this->desc = __( 'Emails', 'checkout-files-upload-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_settings() {
		// Emails Options
		$settings = array(
			array(
				'title'    => __( 'Emails Options', 'checkout-files-upload-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_checkout_files_upload_emails_options',
			),
			array(
				'title'    => __( 'Attach Files to Admin\'s New Order Emails', 'checkout-files-upload-woocommerce' ),
				'desc'     => __( 'Attach', 'checkout-files-upload-woocommerce' ),
				'id'       => 'alg_checkout_files_upload_attach_to_admin_new_order',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Attach Files to Customer\'s Processing Order Emails', 'checkout-files-upload-woocommerce' ),
				'desc'     => __( 'Attach', 'checkout-files-upload-woocommerce' ),
				'id'       => 'alg_checkout_files_upload_attach_to_customer_processing_order',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_checkout_files_upload_emails_options',
			),
		);
		return $settings;
	}

}

endif;

return new Alg_WC_Checkout_Files_Upload_Settings_Emails();
