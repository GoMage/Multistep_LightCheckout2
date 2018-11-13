define(
    [
        'uiComponent',
        'ko',
        'jquery',
        'Magento_Checkout/js/view/summary/grand-total',
        'Magento_Checkout/js/model/quote'
    ],
    function(Component, ko, $, grandTotal, quote) {
        'use strict';
        return Component.extend({
            visible: ko.observable(false),
            showSummarySelector: '#checkout-popup #checkout #modal-header button#show-summary',
            modalTitle: window.checkoutConfig.general.pageTitle,

            getOrderTotal: ko.computed(function() {
                return grandTotal().getValue();
            }, this),

            getItemsCount: ko.computed(function() {
                return quote.getItems().length;
            }, this),

            summaryToggle: function () {
                var showSummary = $(this.showSummarySelector);
                if (this.visible()) {
                    this.visible(false);
                    showSummary.removeClass('active');

                } else {
                    this.visible(true);
                    showSummary.addClass('active');
                }
            }
        });
    }
);
