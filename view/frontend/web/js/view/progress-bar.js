
define(
    [
        'jquery',
        "underscore",
        'ko',
        'uiComponent',
        'GoMage_SuperLightCheckout/js/model/step-navigator',
        'jquery/jquery.hashchange'
    ],
    function ($, _, ko, Component, stepNavigator) {
        var steps = stepNavigator.steps;

        return Component.extend({
            defaults: {
                template: 'Magento_Checkout/progress-bar',
                visible: true
            },
            steps: steps,

            initialize: function() {
                this._super();

            },

            sortItems: function(itemOne, itemTwo) {
                return stepNavigator.sortItems(itemOne, itemTwo);
            },

            navigateTo: function(step) {
                stepNavigator.navigateTo(step.code);
            },

            isProcessed: function(item) {
                return stepNavigator.isProcessed(item.code);
            }
        });
    }
);
