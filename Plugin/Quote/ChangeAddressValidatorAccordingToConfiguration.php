<?php

namespace GoMage\SuperLightCheckout\Plugin\Quote;

use GoMage\Core\Helper\Data as CoreData;
use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Directory\Helper\Data;

class ChangeAddressValidatorAccordingToConfiguration
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var Data
     */
    private $directoryData;

    /**
     * @var CoreData
     */
    private $helper;

    /**
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param Data $directoryData
     * @param CoreData $helper
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        Data $directoryData,
        CoreData $helper
    ) {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->directoryData = $directoryData;
        $this->helper = $helper;
    }

    public function aroundValidate(
        \Magento\Quote\Model\Quote\Address $subject,
        \Closure $proceed
    ) {
        if ($this->helper->isA(CheckoutConfigurationsProvider::MODULE_NAME)) {
            $errors = $this->validate($subject);
        } else {
            $errors = $proceed();
        }
        return $errors;
    }

    /**
     * @return array|bool
     */
    private function validate($subject)
    {
        $errors = [];
        $isFirstNameRequired = (bool)
        $this->checkoutConfigurationsProvider->getCheckoutAddressFieldsRequired()->getIsRequiredAddressFieldFirstName();
        if ($isFirstNameRequired && !\Zend_Validate::is($subject->getFirstname(), 'NotEmpty')) {
            $errors[] = __('Please enter the first name.');
        }

        $isLastNameRequired = (bool)$this->checkoutConfigurationsProvider->getCheckoutAddressFieldsRequired()
            ->getIsRequiredAddressFieldLastName();
        if ($isLastNameRequired && !\Zend_Validate::is($subject->getLastname(), 'NotEmpty')) {
            $errors[] = __('Please enter the last name.');
        }

        $isStreetRequired = (bool)$this->checkoutConfigurationsProvider->getCheckoutAddressFieldsRequired()
            ->getIsRequiredAddressFieldStreetAddress();
        if ($isStreetRequired && !\Zend_Validate::is($subject->getStreetLine(1), 'NotEmpty')) {
            $errors[] = __('Please enter the street.');
        }

        $isCityRequired = (bool)$this->checkoutConfigurationsProvider->getCheckoutAddressFieldsRequired()
            ->getIsRequiredAddressFieldCity();
        if ($isCityRequired && !\Zend_Validate::is($subject->getCity(), 'NotEmpty')) {
            $errors[] = __('Please enter the city.');
        }

        $isPhoneRequired = (bool)$this->checkoutConfigurationsProvider->getCheckoutAddressFieldsRequired()
            ->getIsRequiredAddressFieldPhoneNumber();
        if ($isPhoneRequired && !\Zend_Validate::is($subject->getTelephone(), 'NotEmpty')) {
            $errors[] = __('Please enter the phone number.');
        }

        $isZipRequired = (bool)$this->checkoutConfigurationsProvider->getCheckoutAddressFieldsRequired()
            ->getIsRequiredAddressFieldZipPostalCode();
        if ($isZipRequired && !\Zend_Validate::is(
                $subject->getPostcode(),
                'NotEmpty'
            )
        ) {
            $errors[] = __('Please enter the zip/postal code.');
        }

        $isCountryRequired = (bool)$this->checkoutConfigurationsProvider->getCheckoutAddressFieldsRequired()
            ->getIsRequiredAddressFieldCountry();
        if ($isCountryRequired && !\Zend_Validate::is($subject->getCountryId(), 'NotEmpty')) {
            $errors[] = __('Please enter the country.');
        }

        $isStateRequired = (int)$this->checkoutConfigurationsProvider->getCheckoutAddressFieldsRequired()
            ->getIsRequiredAddressFieldStateProvince();
        if ($isStateRequired === 2) {
            if ($subject->getCountryModel()->getRegionCollection()->getSize() && !\Zend_Validate::is(
                    $subject->getRegionId(),
                    'NotEmpty'
                ) && $this->directoryData->isRegionRequired(
                    $subject->getCountryId()
                )
            ) {
                $errors[] = __('Please enter the state/province.');
            }
        } elseif ($isStateRequired === 1 && !\Zend_Validate::is($subject->getRegionId(), 'NotEmpty')) {
            $errors[] = __('Please enter the state/province.');
        }

        $isCompanyRequired = (bool)$this->checkoutConfigurationsProvider->getCheckoutAddressFieldsRequired()
            ->getIsRequiredAddressFieldCompany();
        if ($isCompanyRequired && !\Zend_Validate::is($subject->getCompany(), 'NotEmpty')) {
            $errors[] = __('Please enter the company.');
        }

        if (empty($errors)) {
            return true;
        }

        return $errors;
    }
}
