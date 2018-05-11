<?php
/**
 * Adds New Badge to products class
 *
 * @package coup
 **/

if ( ! class_exists( 'WC_nb' ) ) {

	class WC_nb {

		public function __construct() {
			add_action( 'init', array($this, 'init') );
		}

		function init() {

			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'coup_woocommerce_show_product_loop_new_badge' ), 30 ); 	// The new badge function

			// Init settings
			$this->settings = array(
				array(
					'name' => esc_html__( 'New Badge', 'coup-shop' ),
					'type' => 'title',
					'id' => 'wc_nb_options'
				),
				array(
					'name' 		=> esc_html__( 'Product Newness', 'coup-shop' ),
					'desc' 		=> esc_html__( "Display the 'New' flash for how many days?", 'coup-shop' ),
					'id' 		=> 'wc_nb_newness',
					'type' 		=> 'number',
				),
				array( 'type' => 'sectionend', 'id' => 'wc_nb_options' ),
			);


			// Default options
			add_option( 'wc_nb_newness', '30' );


			// Admin
			add_action( 'woocommerce_settings_image_options_after', array( $this, 'admin_settings' ), 20 );
			add_action( 'woocommerce_update_options_catalog', array( $this, 'save_admin_settings' ) );
			add_action( 'woocommerce_update_options_products', array( $this, 'save_admin_settings' ) );
		}


        /*-----------------------------------------------------------------------------------*/
		/* Class Functions */
		/*-----------------------------------------------------------------------------------*/

		// Load the settings
		function admin_settings() {
			woocommerce_admin_fields( $this->settings );
		}


		// Save the settings
		function save_admin_settings() {
			woocommerce_update_options( $this->settings );
		}

		/*-----------------------------------------------------------------------------------*/
		/* Frontend Functions */
		/*-----------------------------------------------------------------------------------*/

		// Display the new badge
		function coup_woocommerce_show_product_loop_new_badge() {
			$postdate 		= get_the_time( 'Y-m-d' );			// Post date
			$postdatestamp 	= strtotime( $postdate );			// Timestamped post date
			$newness 		= get_option( 'wc_nb_newness' ); 	// Newness in days as defined by option

			if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) { // If the product was published within the newness time frame display the new badge
				echo '<span class="wc-new-badge">' . esc_html__( 'New', 'coup-shop' ) . '</span>';
			}
		}
	}


	$WC_nb = new WC_nb();
}

