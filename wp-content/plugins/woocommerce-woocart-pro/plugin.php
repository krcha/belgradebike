<?php
// @codingStandardsIgnoreStart
/**
 * Plugin Name: WooCommerce WooCart Pro
 * Plugin URI: http://festi.team/plugins/woocommerce_cart/
 * Description:  WooCart Pro is powerful plugin for create and modify cart for your online shop. We try create flexible solution for you.
 * Version: 2.3.0
 * Author: Festi Team
 * Author URI: http://festi.team/
 * License: You should have purchased a license from http://codecanyon.net/item/woocommerce-woocart-pro/7992078?ref=Festi-Team
 * Copyright 2014  Festi Team  http://festi.team/
 */
// @codingStandardsIgnoreEnd

require_once dirname(__FILE__).'/config.php';

if (!class_exists('FestiPlugin')) {
    require_once dirname(__FILE__).'/libs/FestiPlugin.php';
}

if (!class_exists('FestiPluginChild')) {
    require_once dirname(__FILE__).'/common/FestiPluginWithOptionsFilter.php';
}

if (!class_exists('SettingsWooCartFacade')) {
    require_once dirname(__FILE__).'/common/SettingsWooCartFacade.php';
}


class WooCartFestiPlugin extends FestiPluginWithOptionsFilter
{
    protected $_version = '2.2.1';
    protected $_languageDomain = 'festi_cart';
    protected $_optionsPrefix = 'festi_cart_';
    protected $_currentIconFolder = 'user';
    protected $wpmlPluginName = 'woocommerce-woocartpro';


    protected function onInit()
    {
        $this->addActionListener('plugins_loaded', 'onLanguagesInitAction');

        if ($this->_isWoocommercePluginNotActiveWhenFestiCartPluginActive()) {
            $this->addActionListener(
                'admin_notices',
                'onDisplayInfoAboutDisabledWoocommerceAction'
            );

            return false;
        }

        if ($this->_isJqueryVersionLassRecommend()) {
            $this->addActionListener(
                'admin_notices',
                'onDisplayInfoAboutNotRecommendJqueryVersionAction'
            );

            return false;
        }

        $this->addActionListener('widgets_init', 'onWidgetInitAction');

        parent::onInit();
    } // end onInit

    protected function oInitWpmlTraslator()
    {
        if (!class_exists('FestiWpmlTranslator')) {
            $fileName = 'FestiWpmlTranslator.php';
            require_once $this->_pluginPath.'common/wpml/'.$fileName;
        }
        new FestiWpmlTranslator('WooCartPro', __FILE__);
    } // end oInitWpmlTraslator

    private function _isWoocommercePluginNotActiveWhenFestiCartPluginActive()
    {
        return $this->_isFestiCartPluginActive()
               && !$this->_isWoocommercePluginActive();
    } // end _isWoocommercePluginNotActiveWhenFestiCartPluginActive

    public function onInstall()
    {
        if (!$this->_isWoocommercePluginActive()) {
            $message = 'WooCommerce not active or not installed.';
            $this->displayError($message);
            exit();
        }

        if (!$this->_isInstalationGD()) {
            $message = 'It looks like GD is not installed.';
            $this->displayError($message);
            exit();
        }

        $plugin = $this->onBackendInit();

        $plugin->onInstall();
    } // end onInstall

    public function onUninstall()
    {
        $plugin = $this->onBackendInit();

        $plugin->onUninstall();
    } // end onUnistall

    public function onBackendInit()
    {
        require_once $this->_pluginPath.'common/WooCartBackendFestiPlugin.php';
        $backend = new WooCartBackendFestiPlugin(__FILE__);
        return $backend;
    } // end onBackendInit

    protected function onFrontendInit()
    {
        require_once $this->_pluginPath.'common/WooCartFrontendFestiPlugin.php';
        $frontend = new WooCartFrontendFestiPlugin(__FILE__);
        return $frontend;
    } // end onFrontendIn

    public function onWidgetInitAction($action = '')
    {
        require_once $this->_pluginPath.'common/WooCartFestiWidget.php';
        if ($action) {
            $widget = new WooCartFestiWidget();
            return $widget;
        }
        register_widget('WooCartFestiWidget');
    } // end onWidgetInit

    public function onLanguagesInitAction()
    {
        load_plugin_textdomain(
            $this->_languageDomain,
            false,
            $this->_pluginLanguagesPath
        );
    } // end onLanguagesInitAction

    private function _isFestiCartPluginActive()
    {
        return $this->isPluginActive('woocommerce-woocartpro/plugin.php');
    } // end _isFestiCartPluginActive

    private function _isWoocommercePluginActive()
    {
        return $this->isPluginActive('woocommerce/woocommerce.php');
    } // end _isWoocommercePluginActive

    public function &getWoocommerceInstance()
    {
        return $GLOBALS['woocommerce'];
    } // end getWoocommerceInstance

    private function _isInstalationGD()
    {
        return (extension_loaded('gd') && function_exists('gd_info'));
    } // end _isInstalationGD

    public function getPluginIconsPath($dirname = '')
    {
        return $this->getPluginImagesPath('icons/'.$dirname);
    } // end getPluginIconsPath

    public function getPluginIconsUrl($file, $dirname = '')
    {
        $sufix = '?'.time();
        return $this->getPluginImagesUrl('icons/'.$dirname.'/'.$file.$sufix);
    } // end getPluginIconsUrl

    public function onDisplayInfoAboutDisabledWoocommerceAction()
    {
        $message = 'WooCommerce WooCart Pro: ';
        $message .= 'WooCommerce not active or not installed.';
        $this->displayError($message);
    } //end onDisplayInfoAboutDisabledWoocommerceAction

    public function getSettings()
    {
        $settingsFacade = new SettingsWooCartFacade($this);

        return $settingsFacade->getOptions();
    } // end getSettings

    public function isSelectedPageForCart($id)
    {
        $settings = $this->getSettings();
        $name = 'festiDisplayCartOnPage';

        if (!$this->_isOptionExist($name, $settings)) {
            return false;
        }

        return in_array($id, $settings[$name]);
    }

    protected function isDisplayOnAllPagesOptionOn()
    {
        $settings = $this->getSettings();

        $name = 'displayCartOnAllPages';

        return $this->_isOptionExist($name, $settings);
    }

    private function _isOptionExist($name, $options)
    {
        if (!is_array($options)) {
            return false;
        }

        return array_key_exists($name, $options) && !empty($options[$name]);
    }

    public function convertHexToRgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
              $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));

              $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));

              $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } else {
              $r = hexdec(substr($hex, 0, 2));

              $g = hexdec(substr($hex, 2, 2));

              $b = hexdec(substr($hex, 4, 2));
        }

        $rgb = array($r, $g, $b);

        return $rgb;
    } // end _convertHexToRgb



    protected function getFileNamesListOfCustomizeCart()
    {
        $list = array(
            'cart_customize_style',
            'dropdown_list_customize_style',
            'widget_customize_style',
            'popup_customize_style'
        );

        return $list;
    } // end getFileNamesListOfCustomizeCart

    protected function getCustomCssPath($fileName = '')
    {
        return $this->getPluginCssPath('frontend/customize/'.$fileName);
    } //end getCustomCssPath

    private function _isJqueryVersionLassRecommend()
    {
        $wpScripts = new WP_Scripts();
        $minimalVersion = '1.7.0';
        return (
            version_compare(
                $wpScripts->registered['jquery']->ver, $minimalVersion
            ) == -1
        );
    }

    public function onDisplayInfoAboutNotRecommendJqueryVersionAction()
    {
        $message = 'WooCommerce WooCart Pro: ';
        $message .= 'The minimum JQuery version required ';
        $message .= 'for WooCart PRO is 1.7. ';
        $message .= 'To avoid Internet Explorer crashes please contact ';
        $message .= 'theme developer to upgrade JQuery version on your server.';
        $this->displayError($message);
    } //end onDisplayInfoAboutDisabledWoocommerceAction
}

$GLOBALS['wooCommerceFestiCart'] = new WooCartFestiPlugin(__FILE__);