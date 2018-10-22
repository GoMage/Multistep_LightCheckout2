define(
    [
        'jquery',
        'ko',
        'Magento_Ui/js/form/form',
        'uiRegistry',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/action/select-billing-address',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        'Magento_Checkout/js/model/postcode-validator',
        'mage/translate',
        'underscore',
        'Magento_Customer/js/model/customer',
        'GoMage_SuperLightCheckout/js/model/step-navigator',
        'Magento_Checkout/js/action/create-billing-address',
        'Magento_Customer/js/model/address-list',
        'Magento_Checkout/js/model/checkout-data-resolver',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/action/set-billing-address',
        'Magento_Ui/js/model/messageList'
    ],
    function (
        $,
        ko,
        Component,
        uiRegistry,
        checkoutData,
        quote,
        addressConverter,
        selectBillingAddress,
        shippingRatesValidationRules,
        postcodeValidator,
        $t,
        _,
        customer,
        stepNavigator,
        createBillingAddress,
        addressList,
        checkoutDataResolver,
        customerData,
        setBillingAddressAction,
        globalMessageList
    ) {
        'use strict';

        var observedElements = [],
            postcodeElement = null,
            postcodeElementName = 'postcode',
            newAddressOption = {
                /**
                 * Get new address label
                 * @returns {String}
                 */
                getAddressInline: function () {
                    return $t('New Address');
                },
                customerAddressId: null
            },
            countryData = customerData.get('directory-data'),
            addressOptions = addressList().filter(function (address) {
                return address.getType() == 'customer-address';
            });

        addressOptions.push(newAddressOption);

        return Component.extend({
            defaults: {
                template: 'Magento_Checkout/billing-address'
            },
            currentBillingAddress: quote.billingAddress,
            addressOptions: addressOptions,
            customerHasAddresses: addressOptions.length > 1,
            visible: ko.observable(quote.isVirtual()),

            initialize: function () {
                this._super();

                quote.paymentMethod.subscribe(function () {
                    checkoutDataResolver.resolveBillingAddress();
                }, this);

                uiRegistry.async('checkoutProvider')(function (checkoutProvider) {
                    var billingAddressData = checkoutData.getBillingAddressFromData();

                    if (billingAddressData) {
                        checkoutProvider.set(
                            'billingAddress',
                            $.extend(true, {}, checkoutProvider.get('billingAddress'), billingAddressData)
                        );
                    }
                    checkoutProvider.on('billingAddress', function (billingAddressData) {
                        checkoutData.setBillingAddressFromData(billingAddressData);
                    });
                });

                this.initFields();

                stepNavigator.registerStep(
                    'billing_address',
                    null,
                    $t('Billing Address'),
                    this.visible,
                    _.bind(this.navigate, this),
                    20
                );

                return this;
            },

            /**
             * Load data from server for shipping step
             */
            navigate: function () {
                //load data from server for shipping step
            },

            initObservable: function () {
                var isAddressNew = false;
                if (!customer.isLoggedIn() || this.addressOptions.length === 1) {
                    isAddressNew = true;
                }

                this._super()
                    .observe({
                        isAddressNew: isAddressNew,
                        selectedAddress: null,
                        isAddressSameAsShipping: false,
                        saveInAddressBook: 1
                    });

                quote.billingAddress.subscribe(function () {
                    if (quote.isVirtual()) {
                        this.isAddressSameAsShipping(false);
                    }
                }, this);

                // check if not only new address present
                if (this.addressOptions.length > 1) {
                    for (var i = 0; i < this.addressOptions.length; i++) {
                        if (this.addressOptions[i].isDefaultBilling()) {
                            this.selectedAddress(this.addressOptions[i]);
                            break;
                        }
                    }
                }

                return this;
            },

            /**
             * Perform postponed binding for fieldset elements
             */
            initFields: function () {
                var formPath = 'checkout.steps.billing-address-step.billingAddress.billing-address-fieldset',
                    self = this,
                    elements = shippingRatesValidationRules.getObservableFields();

                if ($.inArray(postcodeElementName, elements) === -1) {
                    // Add postcode field to observables if not exist for zip code validation support
                    elements.push(postcodeElementName);
                }

                $.each(elements, function (index, field) {
                    uiRegistry.async(formPath + '.' + field)(self.doElementBinding.bind(self));
                });
            },

            canUseShippingAddress: ko.computed(function () {
                return !quote.isVirtual() && quote.shippingAddress() && quote.shippingAddress().canUseForBilling();
            }),

            /**
             * @param {Object} address
             * @return {*}
             */
            addressOptionsText: function (address) {
                return address.getAddressInline();
            },

            /**
             * Bind shipping rates request to form element
             *
             * @param {Object} element
             * @param {Boolean} force
             * @param {Number} delay
             */
            doElementBinding: function (element, force, delay) {
                var observableFields = shippingRatesValidationRules.getObservableFields();

                if (element && (observableFields.indexOf(element.index) !== -1 || force)) {
                    if (element.index !== postcodeElementName) {
                        this.bindHandler(element, delay);
                    }
                }

                if (element.index === postcodeElementName) {
                    this.bindHandler(element, delay);
                    postcodeElement = element;
                }
            },

            /**
             * @param {Object} element
             * @param {Number} delay
             */
            bindHandler: function (element, delay) {
                var self = this;

                delay = typeof delay === 'undefined' ? self.validateDelay : delay;

                if (element.component.indexOf('/group') !== -1) {
                    $.each(element.elems(), function (index, elem) {
                        self.bindHandler(elem);
                    });
                } else {
                    element.on('value', function () {
                        clearTimeout(self.validateAddressTimeout);
                        self.validateAddressTimeout = setTimeout(function () {
                            if (self.postcodeValidation()) {
                                self.validateFields();
                            }
                        }, delay);

                    });
                    observedElements.push(element);
                }
            },

            /**
             * @return {*}
             */
            postcodeValidation: function () {
                var countryId = $('select[name="country_id"]').val(),
                    validationResult,
                    warnMessage;

                if (postcodeElement == null || postcodeElement.value() == null) {
                    return true;
                }

                postcodeElement.warn(null);
                validationResult = postcodeValidator.validate(postcodeElement.value(), countryId);

                if (!validationResult) {
                    warnMessage = $t('Provided Zip/Postal Code seems to be invalid.');

                    if (postcodeValidator.validatedPostCodeExample.length) {
                        warnMessage += $t(' Example: ') + postcodeValidator.validatedPostCodeExample.join('; ') + '. ';
                    }
                    warnMessage += $t('If you believe it is the right one you can ignore this notice.');
                    postcodeElement.warn(warnMessage);
                }

                return validationResult;
            },

            /**
             * Convert form data to quote address and validate fields for shipping rates
             */
            validateFields: function () {
                var addressFlat,
                    address;

                    addressFlat = uiRegistry.get('checkoutProvider').billingAddress;
                    address = addressConverter.formAddressDataToQuoteAddress(addressFlat);
                    selectBillingAddress(address);
            },

            /**
             * Collect observed fields data to object
             *
             * @returns {*}
             */
            collectObservedData: function () {
                var observedValues = {};

                $.each(observedElements, function (index, field) {
                    observedValues[field.dataScope] = field.value();
                });

                return observedValues;
            },

            /**
             * @inheritDoc
             */
            onAddressChange: function (address) {
                var streetObj = {};

                if (address && address.customerAddressId !== null) {
                    this.isAddressNew(false);
                    address.country_id = address.countryId;
                    address.region_id = address.regionId;
                    selectBillingAddress(address);

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

                this.source.set('billingAddress', address);
            },

            /**
             * @param {int} countryId
             * @return {*}
             */
            getCountryName: function (countryId) {
                return countryData()[countryId] != undefined ? countryData()[countryId].name : '';
            },

            /**
             * @inheritDoc
             */
            useShippingAddress: function () {
                checkoutData.setSelectedBillingAddress(null);

                if (this.isAddressSameAsShipping()) {
                    selectBillingAddress(quote.shippingAddress());

                    this.updateAddresses();
                    this.isAddressNew(false);
                } else {
                    this.onAddressChange(this.selectedAddress());
                    if (this.selectedAddress() !== null) {
                        selectBillingAddress(this.selectedAddress());
                        checkoutData.setSelectedBillingAddress(this.selectedAddress().getKey());
                    }
                }

            return true;
            },

            /**
             * Get code
             * @param {Object} parent
             * @returns {String}
             */
            getCode: function (parent) {
                return _.isFunction(parent.getCode) ? parent.getCode() : 'shared';
            },

            /**
             * Trigger action to update shipping and billing addresses
             */
            updateAddresses: function () {
                if (window.checkoutConfig.reloadOnBillingAddress ||
                    !window.checkoutConfig.displayBillingOnPaymentMethod
                ) {
                    setBillingAddressAction(globalMessageList);
                }
            },

            /**
             * @inheritDoc
             */
            setBillingAddress: function () {
                if (this.isAddressSameAsShipping()) {
                    stepNavigator.next();
                } else if (this.selectedAddress()
                    && this.selectedAddress() != this.addressOptions[this.addressOptions.length - 1]
                ) {
                    this.saveInAddressBook(0);
                    selectBillingAddress(this.selectedAddress());
                    checkoutData.setSelectedBillingAddress(this.selectedAddress().getKey());
                    stepNavigator.next();
                } else {
                    this.source.set('params.invalid', false);
                    this.source.trigger('billingAddress.data.validate');

                    if (this.source.get('billingAddress.custom_attributes')) {
                        this.source.trigger('billingAddress.custom_attributes.data.validate');
                    }

                    if (!this.source.get('params.invalid')) {
                        var addressData = this.source.get('billingAddress'),
                            newBillingAddress;

                        if (customer.isLoggedIn() && !this.customerHasAddresses) {
                            this.saveInAddressBook(1);
                        }

                        addressData['save_in_address_book'] = this.saveInAddressBook() ? 1 : 0;
                        newBillingAddress = createBillingAddress(addressData);

                        // New address must be selected as a billing address
                        selectBillingAddress(newBillingAddress);
                        checkoutData.setSelectedBillingAddress(newBillingAddress.getKey());
                        checkoutData.setNewCustomerBillingAddress(addressData);
                        stepNavigator.next();
                    }
                }
            },

            returnToPreviousStep: function () {
                stepNavigator.prev();
            }
        });
    }
);
