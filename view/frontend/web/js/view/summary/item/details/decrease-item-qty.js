define(
    [
        'uiComponent',
        'GoMage_SuperLightCheckout/js/action/update-quote-item',
        'Magento_Customer/js/customer-data'
    ],
    function (Component, updateQuoteItemAction, customerData) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'GoMage_SuperLightCheckout/summary/item/details/decrease-item-qty'
            },
            decreaseItemQty: function (item) {
                var qty = item.qty;
                qty--;

                if (qty >= 0) {
                    item.qty = qty;
                    updateQuoteItemAction(item);
                }

                customerData.invalidate('cart');
                customerData.reload('cart', true);
            }
        });
    }
);
