<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class GeneralConfigurationsProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration General Tab settings.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_IS_ENABLED = 'gomage_super_light_checkout_configuration/general/is_enabled';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_DEFAULT_SHIPPING_METHOD = 'gomage_super_light_checkout_configuration/general/default_shipping_method';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_DEFAULT_PAYMENT_METHOD = 'gomage_super_light_checkout_configuration/general/default_payment_method';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_PAGE_TITLE = 'gomage_super_light_checkout_configuration/general/page_title';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_PAGE_CONTENT = 'gomage_super_light_checkout_configuration/general/page_content';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_BILLING_AND_SHIPPING_ARE_THE_SAME_CHECKBOX = 'gomage_super_light_checkout_configuration/general/billing_and_shipping_address_are_the_same_checked';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_CHANGE_QTY = 'gomage_super_light_checkout_configuration/general/allow_to_change_qty';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_REMOVE_ITEM_FROM_CHECKOUT = 'gomage_super_light_checkout_configuration/general/allow_to_remove_item_from_checkout';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_IS_ENABLED_DISCOUNT_CODES = 'gomage_super_light_checkout_configuration/general/is_enabled_discount_codes';
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

    public function isEnabledSuperLightCheckout()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_IS_ENABLED);
    }

    public function getDefaultShippingMethod()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_DEFAULT_SHIPPING_METHOD);
    }

    public function getDefaultPaymentMethod()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_DEFAULT_PAYMENT_METHOD);
    }

    public function getPageTitle()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_PAGE_TITLE);
    }

    public function getPageContent()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_PAGE_CONTENT);
    }

    public function getBillingAndShippingAreTheSameCheckboxChecked()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_BILLING_AND_SHIPPING_ARE_THE_SAME_CHECKBOX);
    }

    public function getIsAllowedToChangeQty()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_CHANGE_QTY);
    }

    public function getIsAllowedToRemoveItemFromCheckout()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_REMOVE_ITEM_FROM_CHECKOUT);
    }

    public function getIsEnabledDiscountCodes()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_GENERAL_IS_ENABLED_DISCOUNT_CODES);
    }
}
