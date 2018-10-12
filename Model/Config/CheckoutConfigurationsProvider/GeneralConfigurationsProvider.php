<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class GeneralConfigurationsProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration General Tab settings.
     */
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_IS_ENABLED = 'gomage_super_light_checkout_configuration/general/is_enabled';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_DEFAULT_SHIPPING_METHOD = 'gomage_super_light_checkout_configuration/general/default_shipping_method';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_DEFAULT_PAYMENT_METHOD = 'gomage_super_light_checkout_configuration/general/default_payment_method';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_PAGE_CONTENT = 'gomage_super_light_checkout_configuration/general/page_content';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ENABLE_DIFFERENT_SHIPPING_ADDRESS = 'gomage_super_light_checkout_configuration/general/enable_different_shipping_address';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_CHANGE_QTY = 'gomage_super_light_checkout_configuration/general/allow_to_change_qty';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_REMOVE_ITEM_FROM_CHECKOUT = 'gomage_super_light_checkout_configuration/general/allow_to_remove_item_from_checkout';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ENABLE_DISCOUNT_CODES = 'gomage_super_light_checkout_configuration/general/enable_discount_codes';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_SHOW_ORDER_SUMMARY_ON_SUCCESS_PAGE = 'gomage_super_light_checkout_configuration/general/show_order_summary_on_success_page';
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
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_IS_ENABLED);
    }

    public function getDefaultShippingMethod()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_DEFAULT_SHIPPING_METHOD);
    }

    public function getDefaultPaymentMethod()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_DEFAULT_PAYMENT_METHOD);
    }

    public function getPageContent()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_PAGE_CONTENT);
    }

    public function getIsEnabledDifferentShippingAddress()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_ENABLE_DIFFERENT_SHIPPING_ADDRESS);
    }

    public function getIsAllowedToChangeQty()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_CHANGE_QTY);
    }

    public function getIsAllowedToRemoveItemFromCheckout()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_REMOVE_ITEM_FROM_CHECKOUT);
    }

    public function getIsEnabledDiscountCodes()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_ENABLE_DISCOUNT_CODES);
    }

    public function getIsShownOrderSummaryOnSuccessPage()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_SHOW_ORDER_SUMMARY_ON_SUCCESS_PAGE);
    }
}
