<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class TermsAndConditionsProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration Terms and Conditions.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_TERMS_AND_CONDITIONS_IS_ENABLED = 'gomage_super_light_checkout_configuration/terms_and_conditions/is_enabled';
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

    public function getIsEnabledTermsAndConditions()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_TERMS_AND_CONDITIONS_IS_ENABLED);
    }
}
