
define(
    [
        'ko',
        'Magento_Checkout/js/view/payment',
        'Magento_Checkout/js/model/quote',
        'GoMage_SuperLightCheckout/js/model/step-navigator',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Checkout/js/checkout-data',
        'mage/translate',
        'underscore',
        'Magento_Checkout/js/action/get-payment-information'
    ],
    function (
        ko,
        Component,
        quote,
        stepNavigator,
        additionalValidators,
        checkoutData,
        $t,
        _,
        getPaymentInformation
    ) {
        'use strict';

        return Component.extend({
            isVisible: ko.observable(false),
            errorValidationMessage: ko.observable(false),

            initialize: function () {
                var self = this;

                this._super();

                stepNavigator.registerStep(
                    'payment',
                    null,
                    $t('Payment'),
                    this.isVisible,
                    _.bind(this.navigate, this),
                    40
                );

                additionalValidators.registerValidator(this);

                quote.paymentMethod.subscribe(function () {
                    self.errorValidationMessage(false);
                });

                return this;
            },

            /**
             * Load data from server for shipping step
             */
            navigate: function () {
                var self = this;
                getPaymentInformation().done(function () {
                    self.isVisible(true);
                });
            },

            validate: function () {
                if (!quote.paymentMethod()) {
                    this.errorValidationMessage('Please specify a payment method.');

                    return false;
                }

                return true;
            }
        });
    }
);
