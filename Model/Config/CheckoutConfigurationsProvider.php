<?php

namespace GoMage\SuperLightCheckout\Model\Config;

use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider\AutofillByZipCode;
use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider\CheckoutAddressFieldsProvider;
use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider\GeneralConfigurationsProvider;
use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider\AutoCompleteByStreetProvider;
use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider\HelpMessagesProvider;
use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider\RegistrationSettingsProvider;
use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider\SocialLoginProvider;
use GoMage\SuperLightCheckout\Model\Config\CheckoutConfigurationsProvider\TermsAndConditionsProvider;

class CheckoutConfigurationsProvider
{
    /**
     * @var GeneralConfigurationsProvider
     */
    private $generalConfigurationsProvider;

    /**
     * @var CheckoutAddressFieldsProvider
     */
    private $checkoutAddressFieldsProvider;

    /**
     * @var RegistrationSettingsProvider
     */
    private $registrationSettingsProvider;

    /**
     * @var TermsAndConditionsProvider
     */
    private $termsAndConditionsProvider;

    /**
     * @var SocialLoginProvider
     */
    private $socialLoginProvider;

    /**
     * @var AutofillByZipCode
     */
    private $autofillByZipCode;

    /**
     * @var HelpMessagesProvider
     */
    private $helpMessagesProvider;

    /**
     * @var AutoCompleteByStreetProvider
     */
    private $autoCompleteByStreetProvider;

    /**
     * @param GeneralConfigurationsProvider $generalConfigurationsProvider
     * @param CheckoutAddressFieldsProvider $checkoutAddressFieldsProvider
     * @param RegistrationSettingsProvider $registrationSettingsProvider
     * @param TermsAndConditionsProvider $termsAndConditionsProvider
     * @param SocialLoginProvider $socialLoginProvider
     * @param AutofillByZipCode $autofillByZipCode
     * @param HelpMessagesProvider $helpMessagesProvider
     * @param AutoCompleteByStreetProvider $autoCompleteByStreetProvider
     */
    public function __construct(
        GeneralConfigurationsProvider $generalConfigurationsProvider,
        CheckoutAddressFieldsProvider $checkoutAddressFieldsProvider,
        RegistrationSettingsProvider $registrationSettingsProvider,
        TermsAndConditionsProvider $termsAndConditionsProvider,
        SocialLoginProvider $socialLoginProvider,
        AutofillByZipCode $autofillByZipCode,
        HelpMessagesProvider $helpMessagesProvider,
        AutoCompleteByStreetProvider $autoCompleteByStreetProvider
    ) {
        $this->generalConfigurationsProvider = $generalConfigurationsProvider;
        $this->checkoutAddressFieldsProvider = $checkoutAddressFieldsProvider;
        $this->registrationSettingsProvider = $registrationSettingsProvider;
        $this->termsAndConditionsProvider = $termsAndConditionsProvider;
        $this->socialLoginProvider = $socialLoginProvider;
        $this->autofillByZipCode = $autofillByZipCode;
        $this->helpMessagesProvider = $helpMessagesProvider;
        $this->autoCompleteByStreetProvider = $autoCompleteByStreetProvider;
    }

    public function getGeneralConfigurations()
    {
        return $this->generalConfigurationsProvider;
    }

    public function getCheckoutAddressFields()
    {
        return $this->checkoutAddressFieldsProvider;
    }

    public function getRegistrationSettings()
    {
        return $this->registrationSettingsProvider;
    }

    public function getTermsAndConditions()
    {
        return $this->termsAndConditionsProvider;
    }

    public function getSocialLogin()
    {
        return $this->socialLoginProvider;
    }

    public function getAutofillByZipCode()
    {
        return $this->autofillByZipCode;
    }

    public function getHelpMessages()
    {
        return $this->helpMessagesProvider;
    }

    public function getAutoCompleteByStreet()
    {
        return $this->autoCompleteByStreetProvider;

    }
}
