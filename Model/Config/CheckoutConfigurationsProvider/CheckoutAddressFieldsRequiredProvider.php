<?php

namespace GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;

use Magento\Framework\App\Config\ScopeConfigInterface;

class CheckoutAddressFieldsRequiredProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration Address Fields Required.
     */
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_FIRST_NAME = 'gomage_super_light_checkout_configuration/checkout_address_fields_required/firstname';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_LAST_NAME = 'gomage_super_light_checkout_configuration/checkout_address_fields_required/lastname';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_COMPANY = 'gomage_super_light_checkout_configuration/checkout_address_fields_required/company';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_COUNTRY = 'gomage_super_light_checkout_configuration/checkout_address_fields_required/country_id';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_CITY = 'gomage_super_light_checkout_configuration/checkout_address_fields_required/city';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_STREET_ADDRESS = 'gomage_super_light_checkout_configuration/checkout_address_fields_required/street';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_ZIPCODE = 'gomage_super_light_checkout_configuration/checkout_address_fields_required/postcode';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_STATE = 'gomage_super_light_checkout_configuration/checkout_address_fields_required/region_id';
    const XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_PHONE = 'gomage_super_light_checkout_configuration/checkout_address_fields_required/telephone';
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

    public function getIsRequiredAddressFieldFirstName()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_FIRST_NAME);
    }

    public function getIsRequiredAddressFieldLastName()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_LAST_NAME);
    }

    public function getIsRequiredAddressFieldCompany()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_COMPANY);
    }

    public function getIsRequiredAddressFieldCountry()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_COUNTRY);
    }

    public function getIsRequiredAddressFieldCity()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_CITY);
    }

    public function getIsRequiredAddressFieldStreetAddress()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_STREET_ADDRESS);
    }

    public function getIsRequiredAddressFieldZipPostalCode()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_ZIPCODE);
    }

    public function getIsRequiredAddressFieldStateProvince()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_STATE);
    }

    public function getIsRequiredAddressFieldPhoneNumber()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_SUPER_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_PHONE);
    }
}
