<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class RegistrationSettingsProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration Registration Tab settings.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_REGISTRATION_CHECKOUT_MODE = 'gomage_super_light_checkout_configuration/registration/checkout_mode';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_REGISTRATION_AUTO_REGISTRATION = 'gomage_super_light_checkout_configuration/registration/auto_registration';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_REGISTRATION_CREATE_AN_ACCOUNT_CHECKBOX = 'gomage_super_light_checkout_configuration/registration/create_an_account_checkbox';
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

    public function getCheckoutMode()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_REGISTRATION_CHECKOUT_MODE);
    }

    public function getIsAutoRegistration()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_REGISTRATION_AUTO_REGISTRATION);
    }

    public function getCreateAnAccountCheckbox()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_REGISTRATION_CREATE_AN_ACCOUNT_CHECKBOX);
    }
}
