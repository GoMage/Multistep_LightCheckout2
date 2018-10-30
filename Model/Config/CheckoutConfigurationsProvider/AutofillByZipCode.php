<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class AutofillByZipCode
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration Autofill by Zip Code.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_ENABLE= 'gomage_super_light_checkout_configuration/autofill_by_zipcode/enable';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_ENABLE_ZIP_CACHING = 'gomage_super_light_checkout_configuration/autofill_by_zipcode/enabled_zip_caching';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_GOOGLE_API_KEY = 'gomage_super_light_checkout_configuration/autofill_by_zipcode/google_api_key';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_API_MODE = 'gomage_super_light_checkout_configuration/autofill_by_zipcode/api_mode';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_DISABLE_ADDRESS_FIELDS = 'gomage_super_light_checkout_configuration/autofill_by_zipcode/disable_address_fields';
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

    public function getIsEnabledAutoFillByZipCode()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_ENABLE);
    }

    public function getAutoFillByZipCodeIsEnabledZipCaching()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_ENABLE_ZIP_CACHING);
    }

    public function getAutoFillByZipCodeGoogleApiKey()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_GOOGLE_API_KEY);
    }

    public function getAutoFillByZipCodeApiMode()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_API_MODE);
    }

    public function getAutoFillByZipCodeIsDisabledAddressFields()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_DISABLE_ADDRESS_FIELDS);
    }
}
