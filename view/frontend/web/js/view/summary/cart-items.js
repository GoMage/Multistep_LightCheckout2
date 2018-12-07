/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Checkout/js/view/summary/cart-items'
], function (Component) {
    'use strict';

    return Component.extend({
        productClasses: 'product-item',
        
        /**
         *
         * @inheritDoc
         */
        setItems: function (items) {
            this.items(items);
        }
    });
});
