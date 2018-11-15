define(
    [
        'uiComponent',
        'ko',
        'jquery',
        'Magento_Checkout/js/view/summary/grand-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/customer-data'
    ],
    function(Component, ko, $, grandTotal, quote, customerData) {
        'use strict';
        return Component.extend({
            visible: ko.observable(false),
            showSummarySelector: '#checkout-popup #checkout #modal-header button#show-summary',
            modalTitle: window.checkoutConfig.general.pageTitle,
            itemsCount: ko.observable(0),

            initialize: function () {
                var cartData = customerData.get('cart'),
                    self = this;

                this._super();

                this.itemsCount(cartData().summary_count);
                cartData.subscribe(function (updatedCart) {
                    self.itemsCount(updatedCart.summary_count)
                }, this);

                return this;
            },

            getOrderTotal: ko.computed(function() {
                return grandTotal().getValue();
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
