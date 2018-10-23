define(
    [
        'Magento_Checkout/js/model/quote',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/model/payment/method-converter',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/shipping-service',
        'GoMage_SuperLightCheckout/js/model/resource-url-manager'
    ],
    function (
        quote,
        storage,
        errorProcessor,
        fullScreenLoader,
        methodConverter,
        paymentService,
        shippingService,
        resourceUrlManager
    ) {
        'use strict';

        return function (itemId) {
            var serviceUrl = resourceUrlManager.getUrlForRemoveItem(quote.getQuoteId(), itemId);

            fullScreenLoader.startLoader();

            return storage.delete(
                serviceUrl
            ).done(
                function (response) {
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                        return;
                    }
                    if (response.shipping_methods && !quote.isVirtual()) {
                        shippingService.setShippingRates(response.shipping_methods);
                    }
                    quote.setTotals(response.totals);
                    paymentService.setPaymentMethods(methodConverter(response.payment_methods));
                }
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
