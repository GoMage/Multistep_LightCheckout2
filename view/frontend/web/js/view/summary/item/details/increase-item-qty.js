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
                template: 'GoMage_SuperLightCheckout/summary/item/details/increase-item-qty'
            },
            increaseItemQty: function (item) {
                item.qty++;

                updateQuoteItemAction(item);

                customerData.invalidate('cart');
                customerData.reload('cart', true);
            }
        });
    }
);
