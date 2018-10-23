define(
    [
        'Magento_Ui/js/form/form',
        'jquery',
        'underscore',
        'ko',
        'Magento_Customer/js/model/customer',
        'Magento_Customer/js/model/address-list',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/create-shipping-address',
        'Magento_Checkout/js/action/select-shipping-address',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-address/form-popup-state',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/model/shipping-rate-registry',
        'Magento_Checkout/js/action/set-shipping-information',
        'GoMage_SuperLightCheckout/js/model/step-navigator',
        'Magento_Ui/js/modal/modal',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Magento_Checkout/js/checkout-data',
        'uiRegistry',
        'mage/translate',
        'Magento_Checkout/js/model/shipping-rate-service'
    ],
    function (
        Component,
        $,
        _,
        ko,
        customer,
        addressList,
        addressConverter,
        quote,
        createShippingAddress,
        selectShippingAddress,
        shippingRatesValidator,
        formPopUpState,
        shippingService,
        selectShippingMethodAction,
        rateRegistry,
        setShippingInformationAction,
        stepNavigator,
        modal,
        checkoutDataResolver,
        checkoutData,
        registry,
        $t
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'GoMage_SuperLightCheckout/shipping-methods'
            },
            visible: ko.observable(false),
            isFormInline: addressList().length == 0,
            errorValidationMessage: ko.observable(false),

            initialize: function () {
                this._super();

                if (!quote.isVirtual()) {
                    stepNavigator.registerStep(
                        'shipping-method',
                        null,
                        $t('Shipping Method'),
                        this.visible,
                        _.bind(this.navigate, this),
                        30
                    );
                }

                if (!checkoutData.getSelectedShippingRate() && window.checkoutConfig.general.defaultShippingMethod) {
                    checkoutData.setSelectedShippingRate(window.checkoutConfig.general.defaultShippingMethod);
                }

                return this;
            },

            /**
             * Load data from server for shipping step
             */
            navigate: function () {
                //load data from server for shipping step
            },

            /**
             * Set shipping method.
             */
            setShippingMethod: function () {
                if (this.validateShippingMethod()) {
                    setShippingInformationAction().done(
                        function () {
                            stepNavigator.next();
                        }
                    );
                }
            },

            /**
             * @return {Boolean}
             */
            validateShippingMethod: function () {
                var result = true;
                if (!quote.shippingMethod()) {
                    this.errorValidationMessage($t('Please specify a shipping method.'));

                    result = false;
                }

                if (!quote.shippingMethod().method_code
                    || !quote.shippingMethod().carrier_code
                ) {
                    result = false;
                }

                return result;
            },

            /**
             * Shipping Method View
             */
            rates: shippingService.getShippingRates(),
            isLoading: shippingService.isLoading,
            isSelected: ko.computed(function () {
                    return quote.shippingMethod() ?
                        quote.shippingMethod().carrier_code + '_' + quote.shippingMethod().method_code
                        : null;
                }
            ),

            /**
             * @param {Object} shippingMethod
             * @return {Boolean}
             */
            selectShippingMethod: function (shippingMethod) {
                selectShippingMethodAction(shippingMethod).done(
                    function () {
                        checkoutData.setSelectedShippingRate(shippingMethod.carrier_code + '_' + shippingMethod.method_code);
                    });

                return true;
            },

            returnToPreviousStep: function () {
                stepNavigator.prev();
            }
        });
    }
);
