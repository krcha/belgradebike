<?php
namespace Bookly\Frontend;

use Bookly\Lib;

/**
 * Class Frontend
 * @package Bookly\Frontend
 */
class Frontend
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action( 'wp_loaded', array( $this, 'init' ) );
        add_action( get_option( 'bookly_gen_link_assets_method' ) == 'enqueue' ? 'wp_enqueue_scripts' : 'wp_loaded', array( $this, 'linkAssets' ) );

        // Init controllers.
        $this->bookingController         = Modules\Booking\Controller::getInstance();
        $this->customerProfileController = Modules\CustomerProfile\Controller::getInstance();
        $this->cancelConfirmController   = Modules\CancellationConfirmation\Controller::getInstance();
        $this->wooCommerceController     = Modules\WooCommerce\Controller::getInstance();

        if ( Lib\Config::paypalEnabled() ) {
            $this->paypalController = Modules\Paypal\Controller::getInstance();
        }

        // Register shortcodes.
        add_shortcode( 'bookly-form', array( $this->bookingController, 'renderShortCode' ) );
        /** @deprecated [ap-booking] */
        add_shortcode( 'ap-booking', array( $this->bookingController, 'renderShortCode' ) );
        add_shortcode( 'bookly-appointments-list', array( $this->customerProfileController, 'renderShortCode' ) );
        add_shortcode( 'bookly-cancellation-confirmation', array( $this->cancelConfirmController, 'renderShortCode' ) );
    }

    /**
     * Link assets.
     */
    public function linkAssets()
    {
        /** @var \WP_Locale $wp_locale */
        global $wp_locale;

        $link_style  = get_option( 'bookly_gen_link_assets_method' ) == 'enqueue' ? 'wp_enqueue_style'  : 'wp_register_style';
        $link_script = get_option( 'bookly_gen_link_assets_method' ) == 'enqueue' ? 'wp_enqueue_script' : 'wp_register_script';
        $version     = Lib\Plugin::getVersion();
        $resources   = plugins_url( 'resources', __FILE__ );

        // Assets for [bookly-form].
        if ( get_option( 'bookly_cst_phone_default_country' ) != 'disabled' ) {
            call_user_func( $link_style, 'bookly-intlTelInput', $resources . '/css/intlTelInput.css', array(), $version );
        }
        call_user_func( $link_style, 'bookly-ladda-min',    $resources . '/css/ladda.min.css',       array(), $version );
        call_user_func( $link_style, 'bookly-picker',       $resources . '/css/picker.classic.css',  array(), $version );
        call_user_func( $link_style, 'bookly-picker-date',  $resources . '/css/picker.classic.date.css', array(), $version );
        call_user_func( $link_style, 'bookly-main',         $resources . '/css/bookly-main.css',     get_option( 'bookly_cst_phone_default_country' ) != 'disabled' ? array( 'bookly-intlTelInput', 'bookly-picker-date' ) : array( 'bookly-picker-date' ), $version );
        if ( is_rtl() ) {
            call_user_func( $link_style, 'bookly-rtl',      $resources . '/css/bookly-rtl.css',      array(), $version );
        }
        call_user_func( $link_script, 'bookly-spin',        $resources . '/js/spin.min.js',          array(), $version );
        call_user_func( $link_script, 'bookly-ladda',       $resources . '/js/ladda.min.js',         array( 'bookly-spin' ), $version );
        call_user_func( $link_script, 'bookly-hammer',      $resources . '/js/hammer.min.js',        array( 'jquery' ), $version );
        call_user_func( $link_script, 'bookly-jq-hammer',   $resources . '/js/jquery.hammer.min.js', array( 'jquery' ), $version );
        call_user_func( $link_script, 'bookly-picker',      $resources . '/js/picker.js',            array( 'jquery' ), $version );
        call_user_func( $link_script, 'bookly-picker-date', $resources . '/js/picker.date.js',       array( 'bookly-picker' ), $version );
        if ( get_option( 'bookly_cst_phone_default_country' ) != 'disabled' ) {
            call_user_func( $link_script, 'bookly-intlTelInput', $resources . '/js/intlTelInput.min.js', array( 'jquery' ), $version );
        }
        if ( Lib\Config::showFacebookLoginButton() && ! get_current_user_id() ) {
            call_user_func( $link_script, 'bookly-facebook-sdk', sprintf( 'https://connect.facebook.net/%s/sdk.js', Lib\Config::getLocale() ), array(), $version );
        }
        call_user_func( $link_script, 'bookly', $resources . '/js/bookly.js', array( 'bookly-ladda', 'bookly-hammer', 'bookly-picker-date' ), $version );

        // Assets for [bookly-appointments-list].
        call_user_func( $link_style,  'bookly-customer-profile', plugins_url( 'modules/customer_profile/resources/css/customer_profile.css', __FILE__ ), array(), $version );
        call_user_func( $link_script, 'bookly-customer-profile', plugins_url( 'modules/customer_profile/resources/js/customer_profile.js', __FILE__ ), array( 'jquery' ), $version );

        Lib\Proxy\Shared::enqueueBookingAssets();

        wp_localize_script( 'bookly', 'BooklyL10n', array(
            'csrf_token' => Lib\Utils\Common::getCsrfToken(),
            'today'      => __( 'Today', 'bookly' ),
            'months'     => array_values( $wp_locale->month ),
            'days'       => array_values( $wp_locale->weekday ),
            'daysShort'  => array_values( $wp_locale->weekday_abbrev ),
            'nextMonth'  => __( 'Next month', 'bookly' ),
            'prevMonth'  => __( 'Previous month', 'bookly' ),
            'show_more'  => __( 'Show more', 'bookly' ),
        ) );
    }

    /**
     * Init.
     */
    public function init()
    {
        if ( ! session_id() ) {
            @session_start();
        }

        // Payments ( PayPal Express Checkout and etc. )
        if ( isset( $_REQUEST['bookly_action'] ) ) {
            // Disable caching.
            Lib\Utils\Common::noCache();

            switch ( $_REQUEST['bookly_action'] ) {
                // PayPal Express Checkout.
                case 'paypal-ec-init':
                    $this->paypalController->ecInit();
                    break;
                case 'paypal-ec-return':
                    $this->paypalController->ecReturn();
                    break;
                case 'paypal-ec-cancel':
                    $this->paypalController->ecCancel();
                    break;
                case 'paypal-ec-error':
                    $this->paypalController->ecError();
                    break;
                default:
                    Lib\Proxy\Shared::handleRequestAction( $_REQUEST['bookly_action'] );
            }
        }
    }

}