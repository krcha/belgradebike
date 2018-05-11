<?php

/*
 * Plugin Name: Flexible Woocommerce Checkout Field Editor
 * Plugin URI: http://193.124.57.131/woocommerceFieldsEditor/wp-admin/admin.php?page=flexible_checkout_fields_editor
 * Description: Flexible Woocommerce Checkout Field Editor lets you posibility to manage fields in checkout form.
 * Version: 1.0.0
 * Author: heoLixfy
 * Author URI: https://wordpress.org/support/profile/heolixfy
 * Requires at least: 4.4
 * Tested up to: 4.5
 * WC requires at least: 2.3
 * WC tested up to: 2.5
 */

if (!defined('ABSPATH')) {
    exit;
}

class FWCFE_Settings_Page_Manager {

    public static function init() {
        add_action('admin_menu', __CLASS__ . '::register_submenu_page');
    }

    public static function register_submenu_page() {
        add_submenu_page('woocommerce', 'Checkout field editor', 'Checkout field editor', 'manage_options', 'flexible_checkout_fields_editor', __CLASS__ . '::show_page');
    }

    public static function show_page() {
        include 'assets/fields-editor.php';
    }

}

class FWCFE_Checkout_Fields_Manager {

    public static function init() {
        add_filter('woocommerce_checkout_fields', __CLASS__ . '::override_checkout_fields', 50);
        add_action('woocommerce_checkout_update_order_meta', __CLASS__ . '::update_checkout_fields_order_metadata');
        add_action('woocommerce_admin_order_data_after_billing_address', __CLASS__ . '::show_checkout_fields_order_page', 10, 1);
    }

    static function show_editor_value($order, $descriptionFields) {
        foreach ($descriptionFields as $itemField) {
            if ($itemField["standartField"] === false && $itemField["showForAdmin"]) {
                echo '<p><strong>' . __($itemField["label"]) . ':</strong><br/>' . get_post_meta($order->id, $itemField["name"], true) . '</p>';
            }
        }
    }

    function show_checkout_fields_order_page($order) {
        $jsonBillingOptions = get_option('billingFieldsOptions');
        $jsonShippingOptions = get_option('shippingFieldsOptions');
        $jsonAccountOptions = get_option('accountFieldsOptions');
        $jsonOrderOptions = get_option('orderFieldsOptions');

        if ($jsonBillingOptions == true) {
            $descriptionBillingFields = json_decode($jsonBillingOptions, true);
            FWCFE_Checkout_Fields_Manager::show_editor_value($order, $descriptionBillingFields);
        }

        if ($jsonShippingOptions == true) {
            $descriptionShippingFields = json_decode($jsonShippingOptions, true);
            FWCFE_Checkout_Fields_Manager::show_editor_value($order, $descriptionShippingFields);
        }

        if ($jsonAccountOptions == true) {
            $descriptionAccountFields = json_decode($jsonAccountOptions, true);
            FWCFE_Checkout_Fields_Manager::show_editor_value($order, $descriptionAccountFields);
        }

        if ($jsonOrderOptions == true) {
            $descriptionOrderFields = json_decode($jsonOrderOptions, true);
            FWCFE_Checkout_Fields_Manager::show_editor_value($order, $descriptionOrderFields);
        }
    }

    static function update_order_metadata($order_id, $descriptionFields) {
        foreach ($descriptionFields as $itemField) {
            if ($itemField["standartField"] === false) {
                if (!empty($_POST[$itemField["name"]])) {
                    $value = $_POST[$itemField["name"]];

                    if ($itemField["typeField"] === 3 && $value === "1")
                        if (!empty($itemField["textTrue"]))
                            $value = $itemField["textTrue"];
                        else
                            $value = "True";

                    if ($itemField["typeField"] === 5) {
                        $value = '';
                        foreach ($_POST as $key => $valuePost) {
                            if ($key === $itemField["name"]) {
                                foreach ($valuePost as $k1 => $v1)
                                    $value = $value . $v1 . '; ';
                            }
                        }
                    }

                    update_post_meta($order_id, $itemField["name"], sanitize_text_field($value));
                } else if ($itemField["typeField"] === 3) {

                    if (!empty($itemField["textFalse"]))
                        update_post_meta($order_id, $itemField["name"], sanitize_text_field($itemField["textFalse"]));
                    else
                        update_post_meta($order_id, $itemField["name"], sanitize_text_field("False"));
                }
            }
        }
    }

    function update_checkout_fields_order_metadata($order_id) {
        $jsonBillingOptions = get_option('billingFieldsOptions');
        $jsonShippingOptions = get_option('shippingFieldsOptions');
        $jsonAccountOptions = get_option('accountFieldsOptions');
        $jsonOrderOptions = get_option('orderFieldsOptions');

        if ($jsonBillingOptions == true) {
            $descriptionBillingFields = json_decode($jsonBillingOptions, true);
            FWCFE_Checkout_Fields_Manager::update_order_metadata($order_id, $descriptionBillingFields);
        }

        if ($jsonShippingOptions == true) {
            $descriptionShippingFields = json_decode($jsonShippingOptions, true);
            FWCFE_Checkout_Fields_Manager::update_order_metadata($order_id, $descriptionShippingFields);
        }

        if ($jsonAccountOptions == true) {
            $descriptionAccountFields = json_decode($jsonAccountOptions, true);
            FWCFE_Checkout_Fields_Manager::update_order_metadata($order_id, $descriptionAccountFields);
        }

        if ($jsonOrderOptions == true) {
            $descriptionOrderFields = json_decode($jsonOrderOptions, true);
            FWCFE_Checkout_Fields_Manager::update_order_metadata($order_id, $descriptionOrderFields);
        }
    }

    static function set_order_fields(&$standartFields, $savedFields) {
        $resultArray = array();
        for ($indexSavedField = 0; $indexSavedField <= count($savedFields); $indexSavedField++) {
            $nameCurrentField = $savedFields[$indexSavedField]["name"];
            foreach ($standartFields as $key => $value) {
                if ($key === $nameCurrentField)
                    $resultArray+= array($key => $value);
            }
        }
        $standartFields = $resultArray;
    }

    static function set_place_field(&$classes, $place) {
        if ($classes === null)
            return;

        unset($classes[array_search("form-row-first", $classes)]);
        unset($classes[array_search("form-row-last", $classes)]);
        unset($classes[array_search("form-row-wide", $classes)]);

        $classColumn = '';
        switch ($place) {
            case 0: {
                    $classColumn = "form-row-wide";
                    break;
                }
            case 1: {
                    $classColumn = "form-row-first";
                    break;
                }
            case 2: {
                    $classColumn = "form-row-last";
                    break;
                }
        }

        array_push($classes, $classColumn);
    }

    static function set_base_props(&$standartField, $userDescriptionField) {
        if ($userDescriptionField["label"])
            $standartField["label"] = $userDescriptionField["label"];
        $standartField["required"] = $userDescriptionField["required"];
        $standartField["clear"] = $userDescriptionField["clear"];

        if ($userDescriptionField["placeholder"])
            $standartField["placeholder"] = $userDescriptionField["placeholder"];


        FWCFE_Checkout_Fields_Manager::set_place_field($standartField['class'], $userDescriptionField["placement"]);
    }

    static function add_custom_field(&$standartFields, $userDescriptionField) {
 if ($userDescriptionField["serverFilterMode"] === 1)
            if (!FWCFE_Checkout_Fields_Manager::contains_products_in_cart($userDescriptionField["serverFilterDescription"]["products"]))
                return;

        if ($userDescriptionField["serverFilterMode"] === 2)
            if (!FWCFE_Checkout_Fields_Manager::contains_categories_in_cart($userDescriptionField["serverFilterDescription"]["categories"]))
                return;
       
        $placeClass = array();
        FWCFE_Checkout_Fields_Manager::set_place_field($placeClass, $userDescriptionField["placement"]);

        $typeEditor = "text";
        switch ($userDescriptionField["typeField"]) {
            case 0: {
                    $typeEditor = "text";
                    break;
                }
            case 1: {
                    $typeEditor = "textarea";
                    break;
                }
            case 2: {
                    $typeEditor = "password";
                    break;
                }
            case 3: {
                    $typeEditor = "checkbox";
                    break;
                }
            case 5:
            case 4: {
                    $typeEditor = "select";
                    break;
                }
        }

        $fieldProps = array("label" => $userDescriptionField["label"],
            "required" => $userDescriptionField["required"],
            "clear" => $userDescriptionField["clear"],
            "placeholder" => $userDescriptionField["placeholder"],
            "class" => $placeClass,
            "type" => $typeEditor
        );

        if ($userDescriptionField["typeField"] === 4 || $userDescriptionField["typeField"] === 5) {

            $optionsArray = json_decode($userDescriptionField["jsonArrayValue"], true);
            if (!$optionsArray)
                return;

            $arrayOptions = array();
            $indexOptions = 0;
            foreach ($optionsArray as $option) {
                $arrayOptions[$option] = __($option, 'woocommerce');
                $indexOptions++;
            }

            $fieldProps["options"] = $arrayOptions; 
        }

        $standartFields+= array($userDescriptionField["name"] => $fieldProps);
    }

    static function contains_products_in_cart($filterProducts) {
        global $woocommerce;
        $items = $woocommerce->cart->get_cart();

        foreach ($items as $item => $values) {
            foreach ($filterProducts as $idFilterProduct) {
                if ($idFilterProduct === $values['product_id'])
                    return true;
            }
        }

        return false;
    }

    static function contains_categories_in_cart($filterCategories) {
        global $woocommerce;
        $items = $woocommerce->cart->get_cart();

        foreach ($items as $item => $values) {
            $terms = get_the_terms($values['product_id'], 'product_cat');
            if ($terms === false) {
                continue;
            }

            foreach ($filterCategories as $idFilterCategory) {
                foreach ($terms as $categoryDescription) {
                    if ($categoryDescription->term_id === $idFilterCategory)
                        return true;
                }
            }
        }
        return false;
    }

    static function set_visibility_field(&$fields, $itemField) {
        if ($itemField["isVisible"] == false)
            unset($fields[$itemField["name"]]);

        if ($itemField["currentRuleVisibility"] === 1)
            if (!FWCFE_Checkout_Fields_Manager::contains_products_in_cart($itemField["visibilityRuleDescription"]["products"]))
                unset($fields[$itemField["name"]]);

        if ($itemField["currentRuleVisibility"] === 2)
            if (!FWCFE_Checkout_Fields_Manager::contains_categories_in_cart($itemField["visibilityRuleDescription"]["categories"]))
                unset($fields[$itemField["name"]]);
    }

    

    static function include_js_resources($jsonBillingSettings, $jsonShippingSettings, $jsonAccountSettings, $jsonOrderSettings) {
        wp_enqueue_script('jquery-ui-datepicker');

        wp_register_style('jquery-ui-datepicker-styles', plugin_dir_url(__FILE__) . 'assets/vendors/jqueryui/jquery-ui.css');
        wp_enqueue_style('jquery-ui-datepicker-styles');

        wp_register_script('appJs1', plugin_dir_url(__FILE__) . 'assets/customerJs/app.js');
        wp_enqueue_script('appJs1');

        wp_localize_script('appJs1', 'checkoutFieldsEditor', array(
            'billingEditorsDescription' => $jsonBillingSettings,
            'shippingEditorsDescription' => $jsonShippingSettings,
            'accountEditorsDescription' => $jsonAccountSettings,
            'orderEditorsDescription' => $jsonOrderSettings)
        );
    }

    static function modify_fields($descriptionFields, &$wooFields) {

        foreach ($descriptionFields as $itemField) {
            if ($wooFields[$itemField["name"]] === null) {
                FWCFE_Checkout_Fields_Manager::add_custom_field($wooFields, $itemField);
                continue;
            }

            FWCFE_Checkout_Fields_Manager::set_base_props($wooFields[$itemField["name"]], $itemField);
           FWCFE_Checkout_Fields_Manager::set_visibility_field($wooFields, $itemField);
        }

        FWCFE_Checkout_Fields_Manager::set_order_fields($wooFields, $descriptionFields);
    }

    function override_checkout_fields($standartFields) {

        $jsonBillingOptions = get_option('billingFieldsOptions');
        $jsonShippingOptions = get_option('shippingFieldsOptions');
        $jsonAccountOptions = get_option('accountFieldsOptions');
        $jsonOrderOptions = get_option('orderFieldsOptions');

        FWCFE_Checkout_Fields_Manager::include_js_resources($jsonBillingOptions, $jsonShippingOptions, $jsonAccountOptions, $jsonOrderOptions);

        if ($jsonBillingOptions == true) {
            $billingDescriptionFields = json_decode($jsonBillingOptions, true);
            FWCFE_Checkout_Fields_Manager::modify_fields($billingDescriptionFields, $standartFields['billing']);
        }

        if ($jsonShippingOptions == true) {
            $shippingDescriptionFields = json_decode($jsonShippingOptions, true);
            FWCFE_Checkout_Fields_Manager::modify_fields($shippingDescriptionFields, $standartFields['shipping']);
        }

        if ($jsonAccountOptions == true) {
            $accountDescriptionFields = json_decode($jsonAccountOptions, true);
            FWCFE_Checkout_Fields_Manager::modify_fields($accountDescriptionFields, $standartFields['account']);
        }

        if ($jsonOrderOptions == true) {
            $orderDescriptionFields = json_decode($jsonOrderOptions, true);
            FWCFE_Checkout_Fields_Manager::modify_fields($orderDescriptionFields, $standartFields['order']);
        }

        return $standartFields;
    }

}

class FWCFE_AJAX_Controller {

    static function init() {

        add_action('wp_ajax_savebillingfields', __CLASS__ . '::save_billing_fields');
        add_action('wp_ajax_nopriv_savebillingfields', __CLASS__ . '::save_billing_fields');
        add_action('wp_ajax_resetbillingfields', __CLASS__ . '::reset_billing_fields');
        add_action('wp_ajax_nopriv_resetbillingfields', __CLASS__ . '::reset_billing_fields');

        add_action('wp_ajax_saveshippingfields', __CLASS__ . '::save_shipping_fields');
        add_action('wp_ajax_nopriv_saveshippingfields', __CLASS__ . '::save_shipping_fields');
        add_action('wp_ajax_resetshippingfields', __CLASS__ . '::reset_shipping_fields');
        add_action('wp_ajax_nopriv_resetshippingfields', __CLASS__ . '::reset_shipping_fields');

        add_action('wp_ajax_saveaccountfields', __CLASS__ . '::save_account_fields');
        add_action('wp_ajax_nopriv_saveaccountfields', __CLASS__ . '::save_account_fields');
        add_action('wp_ajax_resetaccountfields', __CLASS__ . '::reset_account_fields');
        add_action('wp_ajax_nopriv_resetaccountfields', __CLASS__ . '::reset_account_fields');

        add_action('wp_ajax_saveorderfields', __CLASS__ . '::save_order_fields');
        add_action('wp_ajax_nopriv_saveorderfields', __CLASS__ . '::save_order_fields');
        add_action('wp_ajax_resetorderfields', __CLASS__ . '::reset_order_fields');
        add_action('wp_ajax_nopriv_resetorderfields', __CLASS__ . '::reset_order_fields');
    }

    function reset_billing_fields() {
        echo delete_option('billingFieldsOptions');
        die();
    }

    function save_billing_fields() {
        $post = file_get_contents('php://input');
        parse_str($post, $output);

        $jsonBillingOptions = $output['fieldsOptions'];
        update_option('billingFieldsOptions', $jsonBillingOptions);
        die();
    }

    function reset_shipping_fields() {
        echo delete_option('shippingFieldsOptions');
        die();
    }

    function save_shipping_fields() {
        $post = file_get_contents('php://input');
        parse_str($post, $output);

        $jsonShippingOptions = $output['fieldsOptions'];
        update_option('shippingFieldsOptions', $jsonShippingOptions);
        die();
    }

    function reset_account_fields() {
        echo delete_option('accountFieldsOptions');
        die();
    }

    function save_account_fields() {
        $post = file_get_contents('php://input');
        parse_str($post, $output);

        $jsonAccountOptions = $output['fieldsOptions'];
        update_option('accountFieldsOptions', $jsonAccountOptions);
        die();
    }

    function reset_order_fields() {
        echo delete_option('orderFieldsOptions');
        die();
    }

    function save_order_fields() {
        $post = file_get_contents('php://input');
        parse_str($post, $output);

        $jsonOrderOptions = $output['fieldsOptions'];
        update_option('orderFieldsOptions', $jsonOrderOptions);
        die();
    }

}

class FWCFE_Email_Provider {

    static function init() {
        add_action('woocommerce_email_customer_details_fields', __CLASS__ . '::extend_information_client', 10, 3);
    }

    static function add_editor_value($order, $descriptionFields, &$fields) {
        foreach ($descriptionFields as $itemField) {
            if ($itemField["standartField"] === false && $itemField["showForEmail"]) {
                $fields[$itemField["name"]] = array();
                $fields[$itemField["name"]]["label"] = $itemField["label"];
                $fields[$itemField["name"]]["value"] = get_post_meta($order->id, $itemField["name"], true);
            }
        }
    }

    static function extend_information_client($fields, $sent_to_admin, $order) {
        if (!$sent_to_admin)
            return $fields;

        $jsonBillingOptions = get_option('billingFieldsOptions');
        $jsonShippingOptions = get_option('shippingFieldsOptions');
        $jsonAccountOptions = get_option('accountFieldsOptions');
        $jsonOrderOptions = get_option('orderFieldsOptions');

        if ($jsonBillingOptions == true) {
            $descriptionBillingFields = json_decode($jsonBillingOptions, true);
            FWCFE_Email_Provider::add_editor_value($order, $descriptionBillingFields, $fields);
        }

        if ($jsonShippingOptions == true) {
            $descriptionShippingFields = json_decode($jsonShippingOptions, true);
            FWCFE_Email_Provider::add_editor_value($order, $descriptionShippingFields, $fields);
        }

        if ($jsonAccountOptions == true) {
            $descriptionAccountFields = json_decode($jsonAccountOptions, true);
            FWCFE_Email_Provider::add_editor_value($order, $descriptionAccountFields, $fields);
        }

        if ($jsonOrderOptions == true) {
            $descriptionOrderFields = json_decode($jsonOrderOptions, true);
            FWCFE_Email_Provider::add_editor_value($order, $descriptionOrderFields, $fields);
        }

        return $fields;
    }

}

class FWCFE_Uninstall_Manager {

    static function init() {
        register_deactivation_hook(__FILE__, __CLASS__ . '::remove_all_settings');
        register_uninstall_hook(__FILE__, __CLASS__ . '::remove_all_settings');
    }

    static function remove_all_settings() {
        if (!current_user_can('activate_plugins'))
            return;

        delete_option('billingFieldsOptions');
        delete_option('shippingFieldsOptions');
        delete_option('accountFieldsOptions');
        delete_option('orderFieldsOptions');
    }

}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    FWCFE_Settings_Page_Manager::init();
    FWCFE_Checkout_Fields_Manager::init();
    FWCFE_AJAX_Controller::init();
    FWCFE_Email_Provider::init();
    FWCFE_Uninstall_Manager::init();
}
