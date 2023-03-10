<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class CheckoutAddressFieldsProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration Address Fields Form.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_FORM = 'gomage_super_light_checkout_configuration/checkout_address_fields_sorting/fields_form';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_KEEP_FIELDS_INSIDE = 'gomage_super_light_checkout_configuration/checkout_address_fields_sorting/keep_field_names_inside';
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

    public function getAddressFieldsForm()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_FORM);
    }

    public function getAddressFieldsKeepInside()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_KEEP_FIELDS_INSIDE);
    }
}
