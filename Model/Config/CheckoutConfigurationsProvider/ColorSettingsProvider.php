<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ColorSettingsProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration Color Settings.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_COLOR_SETTINGS_PLACE_ORDER_BUTTON = 'gomage_super_light_checkout_configuration/color_settings/place_order_button';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_COLOR_SETTINGS_CHECKOUT_COLOR = 'gomage_super_light_checkout_configuration/color_settings/checkout_color';
    /**#@-*/
    // @codingStandardsIgnoreEnd

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getCheckoutColorSettingsPlaceOrderButton()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_COLOR_SETTINGS_PLACE_ORDER_BUTTON);
    }

    public function getCheckoutColorSettingsCheckoutColor()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_COLOR_SETTINGS_CHECKOUT_COLOR);
    }
}
