define(
    [
        'uiComponent',
        'GoMage_SuperLightCheckout/js/action/update-quote-item'
    ],
    function (Component, updateQuoteItemAction) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'GoMage_SuperLightCheckout/summary/item/details/increase-item-qty'
            },
            increaseItemQty: function (item) {
                item.qty++;

                updateQuoteItemAction(item);
            }
        });
    }
);
