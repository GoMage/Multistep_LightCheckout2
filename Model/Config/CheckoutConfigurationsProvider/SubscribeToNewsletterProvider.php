<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class SubscribeToNewsletterProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration Subscribe To Newsletter.
     */
    const XML_PATH_LIGHT_CHECKOUT_NEWSLETTER_CHECKBOX_ENABLE = 'gomage_super_light_checkout_configuration/newsletter_checkbox/enable';
    const XML_PATH_LIGHT_CHECKOUT_NEWSLETTER_CHECKBOX_CHECKBOX_IS_CHECKED = 'gomage_super_light_checkout_configuration/newsletter_checkbox/checkbox_is_checked';
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

    public function getIsEnabledSubscribeToNewsletter()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_NEWSLETTER_CHECKBOX_ENABLE);
    }

    public function getSubscribeToNewsletterIsCheckboxChecked()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_NEWSLETTER_CHECKBOX_CHECKBOX_IS_CHECKED);
    }
}
