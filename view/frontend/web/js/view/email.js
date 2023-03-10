
define([
    'jquery',
    'ko',
    'Magento_Checkout/js/view/form/element/email',
    'Magento_Customer/js/action/check-email-availability',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Magento_Customer/js/model/customer',
    'GoMage_SuperLightCheckout/js/action/check-is-subscribed',
    'GoMage_SuperLightCheckout/js/light-checkout-data'
], function (
    $,
    ko,
    Component,
    checkEmailAvailability,
    checkoutData,
    additionalValidators,
    customer,
    checkIsSubscribed,
    lightCheckoutData
) {
    'use strict';

    return Component.extend({
        customerEmailErrorSelector: '#customer-email-error',
        customerNoteSelector: '#note-customer-email',
        onlyRegistered: ko.observable(false),
        checkoutMode: parseInt(window.checkoutConfig.registration.checkoutMode),
        autoRegistration: parseInt(window.checkoutConfig.registration.autoRegistration),
        error: 'Customer with this email does not exist. Please '
        + '<a href="' + window.checkoutConfig.registration.registrationUrl + '" target="__blank">register</a>'
        + ' before placing order.',
        errorText: ko.observable(''),
        isSubscribeVisible: ko.observable(true),

        /**
         *
         * @inheritDoc
         */
        initialize: function () {
            this.errorText(this.error);
            this._super();

            if (!customer.isLoggedIn() && this.checkoutMode === 1 && !this.autoRegistration) {
                this.onlyRegistered(true);
                additionalValidators.registerValidator(this);
            }

            return this;
        },

        initConfig: function () {
            var shouldSubscribeNotBeVisible;
            this._super();

            shouldSubscribeNotBeVisible = this.resolveInitialSubscribeNotVisibility();
            if (shouldSubscribeNotBeVisible) {
                this.isSubscribeVisible(false);
            }
        },

        /**
         * Initializes observable properties of instance
         *
         * @returns {Object} Chainable.
         */
        initObservable: function () {
            this._super();

            if (this.email() === '') {
                this.errorText('');
            }

            return this;
        },

        /**
         * Check email existing.
         */
        checkEmailAvailability: function () {
            var self = this;

            this.validateRequest();
            this.isEmailCheckComplete = $.Deferred();
            this.isLoading(true);
            this.checkRequest = checkEmailAvailability(this.isEmailCheckComplete, this.email());

            $.when(this.isEmailCheckComplete).done(function () {
                this.isPasswordVisible(false);

                if (!customer.isLoggedIn() && this.checkoutMode === 1 && !this.autoRegistration) {
                    self.showErrorText();
                }
            }.bind(this)).fail(function () {
                this.isPasswordVisible(true);
                checkoutData.setCheckedEmailValue(this.email());

                if (!customer.isLoggedIn() && this.checkoutMode === 1 && !this.autoRegistration) {
                    self.hideErrorText();
                }
            }.bind(this)).always(function () {
                this.isLoading(false);
                this.checkIsSubscribed();
            }.bind(this));
        },

        checkIsSubscribed: function () {
            var isSubscribedCheckComplete = $.Deferred(),
                self = this;

            this.isLoading(true);
            checkIsSubscribed(isSubscribedCheckComplete, this.email());

            $.when(isSubscribedCheckComplete).done(function () {
                self.isSubscribeVisible(false);
                lightCheckoutData.setSubscribedEmailValue(this.email());
            }.bind(this)).fail(function () {
                self.isSubscribeVisible(true);
            }.bind(this)).always(function () {
                this.isLoading(false);
            }.bind(this));
        },

        hideErrorText: function () {
            $(this.customerEmailErrorSelector).hide();
        },

        showErrorText: function () {
            $(this.customerEmailErrorSelector).html(this.errorText());
            $(this.customerEmailErrorSelector).show();
        },

        validate: function () {
            if (!customer.isLoggedIn() && !this.isPasswordVisible()) {
                this.showErrorText();

                return false;
            }

            return true;
        },

        /**
         *
         * @inheritdoc
         */
        validateEmail: function (focused) {
            var result = this._super(focused);

            if (this.checkoutMode === 1 && !this.autoRegistration) {
                if (this.email() !== '' && this.errorText() === '') {
                    this.errorText(this.error);
                }

                if (!customer.isLoggedIn()
                    && !this.isPasswordVisible()
                    && !$(this.customerEmailErrorSelector).is(':visible')
                ) {
                    this.showErrorText();
                }
            }

            return result;
        },

        resolveInitialSubscribeNotVisibility: function () {
            if (lightCheckoutData.getSubscribedEmailValue() !== '') {
                return checkoutData.getInputFieldEmailValue() === lightCheckoutData.getSubscribedEmailValue();
            }

            return false;
        }
    });
});
