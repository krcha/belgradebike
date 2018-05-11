<?php
/**
 * Checkout Files Upload - Settings
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Settings_Checkout_Files_Upload' ) ) :

class Alg_WC_Settings_Checkout_Files_Upload extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id    = 'alg_wc_checkout_files_upload';
		$this->label = __( 'Checkout Files Upload', 'checkout-files-upload-woocommerce' );
		parent::__construct();
		add_action( 'woocommerce_admin_field_custom_number_checkout_files_upload', array( $this, 'output_custom_number_checkout_files_upload' ) );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function get_settings() {
		global $current_section;
		return apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() );
	}

	/**
	 * output_custom_number_checkout_files_upload.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function output_custom_number_checkout_files_upload( $value ) {

		$type         = 'number';
		$option_value = get_option( $value['id'], $value['default'] );

		$tooltip_html = ( isset( $value['desc_tip'] ) && '' != $value['desc_tip'] ) ? '<span class="woocommerce-help-tip" data-tip="' . $value['desc_tip'] . '"></span>' : '';
		$description = ' <span class="description">' . $value['desc'] . '</span>';
		$save_button = ( PHP_INT_MAX === apply_filters( 'alg_wc_checkout_files_upload_option', 1 ) ? '<input name="save" class="button-primary" type="submit" value="' . __( 'Save changes', 'woocommerce' ) . '">' : '' );

		// Custom attribute handling
		$custom_attributes = array();
		if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
			foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		?><tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
				<?php echo $tooltip_html; ?>
			</th>
			<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
				<input
					name="<?php echo esc_attr( $value['id'] ); ?>"
					id="<?php echo esc_attr( $value['id'] ); ?>"
					type="<?php echo esc_attr( $type ); ?>"
					style="<?php echo esc_attr( $value['css'] ); ?>"
					value="<?php echo esc_attr( $option_value ); ?>"
					class="<?php echo esc_attr( $value['class'] ); ?>"
					placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
					<?php echo implode( ' ', $custom_attributes ); ?>
					/><?php echo $save_button; ?><?php echo $description; ?>
			</td>
		</tr><?php
	}

}

endif;

return new Alg_WC_Settings_Checkout_Files_Upload();
