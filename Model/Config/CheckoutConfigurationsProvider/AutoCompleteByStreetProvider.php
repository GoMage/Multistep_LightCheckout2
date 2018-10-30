<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class AutoCompleteByStreetProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration AutoComplete Street.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_AUTO_COMPLETE_BY_STREET_ENABLE = 'gomage_super_light_checkout_configuration/auto_complete_by_street/enable';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_AUTO_COMPLETE_BY_STREET_GOOGLE_API_KEY = 'gomage_super_light_checkout_configuration/auto_complete_by_street/google_api_key';
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

    public function getIsEnabledAutoCompleteByStreet()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_AUTO_COMPLETE_BY_STREET_ENABLE);
    }

    public function getAutoCompleteByStreetGoogleApiKey()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_AUTO_COMPLETE_BY_STREET_GOOGLE_API_KEY);
    }
}
