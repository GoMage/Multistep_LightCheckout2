define(
    [
        'uiComponent',
        'ko',
        'jquery',
        'Magento_Checkout/js/view/summary/grand-total'
    ],
    function(Component, ko, $, grandTotal) {
        'use strict';
        return Component.extend({
            visible: ko.observable(false),
            showSummarySelector: '#checkout-popup #checkout #modal-header button#show-summary',
            orderTotal: ko.computed(function() {
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
