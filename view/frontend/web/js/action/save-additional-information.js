define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'GoMage_SuperLightCheckout/js/model/resource-url-manager',
        'underscore'
    ],
    function (
        $,
        quote,
        storage,
        errorProcessor,
        fullScreenLoader,
        resourceUrlManager,
        _
    ) {
        'use strict';

        return function () {
            var serviceUrl = resourceUrlManager.getUrlForSaveAdditionalInformation(),
                selectors = {
                    password : '#account-password',
                    accountCheckbox: 'input[name=create-account-checkbox]',
                    passwordForLoginForm : '.form-login #customer-email-fieldset #customer-password',
                    customerEmail: '.form-login #customer-email-fieldset #customer-email',
                    subscribeToNewsletter: '#subscribe-newsletter input[type=checkbox]'
                },
                passwordVal = $(selectors.password).val(),
                isCheckboxChecked = $(selectors.accountCheckbox).is(":checked"),
                isSubscribeToNewsletterCheckboxChecked = $(selectors.subscribeToNewsletter).is(":checked"),
                payload = {
                    additionInformation: {}
                };

            if (isCheckboxChecked) {
                payload.additionInformation.password = passwordVal;
            } else {
                payload.additionInformation.password = null;
            }

            if (isSubscribeToNewsletterCheckboxChecked) {
                payload.additionInformation.subscribe = isSubscribeToNewsletterCheckboxChecked;
                payload.additionInformation.customerEmail = $(selectors.customerEmail).val();
            }

            if (_.isEmpty(payload.additionInformation)) {
                return;
            }

            fullScreenLoader.startLoader();

            return storage.post(
                serviceUrl,
                JSON.stringify(payload)
            ).fail(
                function (response) {
                    errorProcessor.process(response);
                }
            ).always(function () {
                fullScreenLoader.stopLoader();
            });
        };
    }
);
