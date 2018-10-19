define(
    [
      ],
    function () {
        'use strict';

        return function (abstractDefault) {

            return abstractDefault.extend({
                isFullMode: function () {
                    var result = true;

                    if (!this.getTotals()) {
                        result = false;
                    }

                    return result;
                }
            });
        }
    });
