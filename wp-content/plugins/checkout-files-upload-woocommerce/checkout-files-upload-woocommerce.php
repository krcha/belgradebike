<?php
/*
Plugin Name: Checkout Files Upload for WooCommerce
Plugin URI: https://wpcodefactory.com/item/checkout-files-upload-woocommerce-plugin/
Description: Checkout Files Upload for WooCommerce.
Version: 1.2.0
Author: Algoritmika Ltd
Author URI: http://www.algoritmika.com
Text Domain: checkout-files-upload-woocommerce
Domain Path: /langs
Copyright: © 2017 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if (
	! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) &&
	! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
) {
	return;
}

if ( 'checkout-files-upload-woocommerce.php' === basename( __FILE__ ) ) {
	// Check if Pro is active, if so then return
	$plugin = 'checkout-files-upload-woocommerce-pro/checkout-files-upload-woocommerce-pro.php';
	if (
		in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
		( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		return;
	}
}

if ( ! class_exists( 'Alg_WC_Checkout_Files_Upload' ) ) :

/**
 * Main Alg_WC_Checkout_Files_Upload Class
 *
 * @class   Alg_WC_Checkout_Files_Upload
 * @since   1.0.0
 * @version 1.2.0
 */
final class Alg_WC_Checkout_Files_Upload {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.2.0';

	/**
	 * @var   Alg_WC_Checkout_Files_Upload The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Checkout_Files_Upload Instance
	 *
	 * Ensures only one instance of Alg_WC_Checkout_Files_Upload is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Alg_WC_Checkout_Files_Upload - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Checkout_Files_Upload Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 * @access  public
	 */
	public function __construct() {

		// Set up localisation
		load_plugin_textdomain( 'checkout-files-upload-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Include required files
		$this->includes();

		// Settings & Scripts
		if ( is_admin() ) {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		}
	}

	/**
	 * Show action links on the plugin screen
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 * @param   mixed $links
	 * @return  array
	 */
	public function action_links( $links ) {
		$settings_link   = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_checkout_files_upload' ) . '">' . __( 'Settings', 'woocommerce' )   . '</a>';
		$unlock_all_link = '<a target="_blank" href="' . esc_url( 'https://wpcodefactory.com/item/checkout-files-upload-woocommerce-plugin/' ) . '">' . __( 'Unlock all', 'checkout-files-upload-woocommerce' ) . '</a>';
		$custom_links    = ( PHP_INT_MAX === apply_filters( 'alg_wc_checkout_files_upload_option', 1 ) ) ? array( $settings_link ) : array( $settings_link, $unlock_all_link );
		return array_merge( $custom_links, $links );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	private function includes() {

		require_once( 'includes/alg-wc-checkout-files-upload-functions.php' );

		require_once( 'includes/admin/class-alg-wc-checkout-files-upload-settings-section.php' );
		$settings = array();
		$settings[] = require_once( 'includes/admin/class-alg-wc-checkout-files-upload-settings-general.php' );
		$settings[] = require_once( 'includes/admin/class-alg-wc-checkout-files-upload-settings-emails.php' );
		$settings[] = require_once( 'includes/admin/class-alg-wc-checkout-files-upload-settings-template.php' );
		if ( is_admin() && get_option( 'alg_checkout_files_upload_version', '' ) !== $this->version ) {
			foreach ( $settings as $section ) {
				foreach ( $section->get_settings() as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						/* if ( isset ( $_GET['alg_wc_checkout_files_upload_admin_options_reset'] ) ) {
							require_once( ABSPATH . 'wp-includes/pluggable.php' );
							if ( is_super_admin() ) {
								delete_option( $value['id'] );
							}
						} */
						$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
						add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
					}
				}
			}
			update_option( 'alg_checkout_files_upload_version', $this->version );
		}

		require_once( 'includes/class-alg-wc-checkout-files-upload.php' );
	}

	/**
	 * Add Checkout Files Upload settings tab to WooCommerce settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function add_woocommerce_settings_tab( $settings ) {
		$settings[] = include( 'includes/admin/class-wc-settings-checkout-files-upload.php' );
		return $settings;
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

endif;

/**
 * Returns the main instance of Alg_WC_Checkout_Files_Upload to prevent the need to use globals.
 *
 * @version 1.0.0
 * @since   1.0.0
 * @return  Alg_WC_Checkout_Files_Upload
 */
if ( ! function_exists( 'alg_wc_checkout_files_upload' ) ) {
	function alg_wc_checkout_files_upload() {
		return Alg_WC_Checkout_Files_Upload::instance();
	}
}

alg_wc_checkout_files_upload();
