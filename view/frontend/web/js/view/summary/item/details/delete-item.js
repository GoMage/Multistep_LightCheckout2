define(
    [
        'uiComponent',
        'GoMage_SuperLightCheckout/js/action/remove-quote-item',
        'Magento_Customer/js/customer-data'
    ],
    function (Component, removeQuoteItemAction, customerData) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'GoMage_SuperLightCheckout/summary/item/details/delete-item'
            },
            removeItem: function (item) {
                var itemId = item.item_id;

                removeQuoteItemAction(itemId);

                customerData.invalidate('cart');
                customerData.reload('cart', true);
            }
        });
    }
);
