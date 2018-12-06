
/**
 * Light checkout adapter for customer data storage
 */
define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function ($, storage) {
    'use strict';

    var cacheKey = 'superlight-checkout-data';

    var saveData = function (checkoutData) {
        storage.set(cacheKey, checkoutData);
    },
        
    /**
     * @return {*}
     */
    getData = function () {
        var data = storage.get(cacheKey)();

        if ($.isEmptyObject(data)) {
            data = {
                'isAddressSameAsShipping': null,
            };
            saveData(data);
        }

        return data;
    };

    return {
        getSubscribedEmailValue: function () {
            var obj = getData();

            return obj.subscribedEmailValue ? obj.subscribedEmailValue : '';
        },
        setSubscribedEmailValue: function (email) {
            var obj = getData();

            obj.subscribedEmailValue = email;
            saveData(obj);
        }
    }
});
