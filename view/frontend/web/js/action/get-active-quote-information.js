define(
    [
        'Magento_Checkout/js/model/quote',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/model/payment/method-converter',
        'Magento_Checkout/js/model/payment-service',
        'GoMage_SuperLightCheckout/js/model/resource-url-manager',
        'Magento_Checkout/js/model/shipping-service'
    ],
    function (
        quote,
        storage,
        errorProcessor,
        fullScreenLoader,
        methodConverter,
        paymentService,
        resourceUrlManager,
        shippingService
    ) {
        'use strict';

        return function () {
            var serviceUrl = resourceUrlManager.getUrlForGetActiveQuoteInformation(quote.getQuoteId());

            fullScreenLoader.startLoader();

            return storage.post(
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
                    if (!quote.getQuoteId()) {
                        quote.setQuoteData(response.quote);
                        if (response.quote_masked_id && response.quote_masked_id.quote_masked_id) {
                            quote.setQuoteId(response.quote_masked_id.quote_masked_id);
                        } else {
                            quote.setQuoteId(response.id);
                        }
                    }
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
