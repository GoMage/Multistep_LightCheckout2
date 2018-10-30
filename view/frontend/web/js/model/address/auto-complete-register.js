define([
    'GoMage_SuperLightCheckout/js/model/address/google-auto-complete'
], function (googleAutoComplete) {
    'use strict';

    var addressType = {
        billing: 'checkout.steps.billing-address-step.billingAddress.billing-address-fieldset',
        shipping: 'checkout.steps.shipping-address-step.shippingAddress.shipping-address-fieldset'
    };

    return {
        register: function (type) {
            if (window.checkoutConfig.autoCompleteStreet.enabled == 1) {
                new googleAutoComplete(addressType[type]);
            }
        }
    };
});
