define(
    [
        'Magento_Checkout/js/view/shipping',
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
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/model/shipping-rate-registry',
        'Magento_Checkout/js/action/set-shipping-information',
        'GoMage_SuperLightCheckout/js/model/step-navigator',
        'Magento_Ui/js/modal/modal',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Magento_Checkout/js/checkout-data',
        'uiRegistry',
        'mage/translate'
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

        var newAddressOption = {
                /**
                 * Get new address label
                 * @returns {String}
                 */
                getAddressInline: function () {
                    return $t('New Address');
                },
                customerAddressId: null
            },
            addressOptions = addressList().filter(function (address) {
                return address.getType() == 'customer-address';
            });

        addressOptions.push(newAddressOption);

        return Component.extend({
            defaults: {
                template: 'GoMage_SuperLightCheckout/shipping-address'
            },
            addressOptions: addressOptions,

            initialize: function () {
                this._super();

                if (!quote.isVirtual()) {
                    stepNavigator.registerStep(
                        'shipping-address',
                        null,
                        $t('Shipping Address'),
                        this.visible,
                        _.bind(this.navigate, this),
                        10
                    );
                }
                return this;
            },

            initObservable: function () {
                this._super()
                    .observe({
                        selectedAddress: null,
                        isAddressNew: false,
                        saveInAddressBook: 1
                    });

                // check if not only new address present
                if (this.addressOptions.length > 1) {
                    for (var i = 0; i < this.addressOptions.length; i++) {
                        if (this.addressOptions[i].isDefaultShipping()) {
                            this.selectedAddress(this.addressOptions[i]);
                            break;
                        }
                    }
                }

                return this;
            },

            /**
             * Set shipping address.
             */
            setShippingInformation: function () {
                if (this.validateShippingAddress()) {
                    stepNavigator.next();
                }
            },

            /**
             * @return {Boolean}
             */
            validateShippingAddress: function () {
                var shippingAddress,
                    addressData,
                    loginFormSelector = 'form[data-role=email-with-possible-login]',
                    emailValidationResult = customer.isLoggedIn();

                if (!customer.isLoggedIn()) {
                    $(loginFormSelector).validation();
                    emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
                }

                this.source.set('params.invalid', false);
                this.source.trigger('shippingAddress.data.validate');

                if (this.source.get('shippingAddress.custom_attributes')) {
                    this.source.trigger('shippingAddress.custom_attributes.data.validate');
                }

                if (this.source.get('params.invalid')) {
                    return false;
                }

                shippingAddress = quote.shippingAddress();
                addressData = addressConverter.formAddressDataToQuoteAddress(
                    this.source.get('shippingAddress')
                );

                //Copy form data to quote shipping address object
                for (var field in addressData) {

                    if (addressData.hasOwnProperty(field) &&
                        shippingAddress.hasOwnProperty(field) &&
                        typeof addressData[field] != 'function' &&
                        _.isEqual(shippingAddress[field], addressData[field])
                    ) {
                        shippingAddress[field] = addressData[field];
                    } else if (typeof addressData[field] != 'function' &&
                        !_.isEqual(shippingAddress[field], addressData[field])) {
                        shippingAddress = addressData;
                        break;
                    }
                }

                if (customer.isLoggedIn()) {
                    this.saveInAddressBook(this.isAddressNew());
                }

                if (customer.isLoggedIn() && this.addressOptions.length === 1) {
                    this.saveInAddressBook(1);
                }

                addressData['save_in_address_book'] = this.saveInAddressBook() ? 1 : 0;
                addressData.saveInAddressBook = this.saveInAddressBook() ? 1 : 0;

                selectShippingAddress(shippingAddress);

                if (!emailValidationResult) {
                    $(loginFormSelector + ' input[name=username]').focus();

                    return false;
                }

                return true;
            },

            /**
             * @param {Object} address
             * @return {*}
             */
            addressOptionsText: function (address) {
                return address.getAddressInline();
            },

            /**
             * @param {Object} address
             */
            onAddressChange: function (address) {
                var streetObj = {};

                if (address.customerAddressId !== null) {
                    this.isAddressNew(false);
                    address.country_id = address.countryId;
                    address.region_id = address.regionId;

                    if (_.isArray(address.street)) {
                        //convert array to object to display street values on frontend.
                        for (var i = 0; i < address.street.length; i++) {
                            streetObj[i] = address.street[i];
                        }

                        address.street = streetObj;
                    }
                } else {
                    this.isAddressNew(true);
                }

                this.source.set('shippingAddress', address);
            }
        });
    }
);
