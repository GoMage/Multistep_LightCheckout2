define(
    [
        'jquery',
        'text!GoMage_SuperLightCheckout/template/modal/modal-popup.html',
        'GoMage_SuperLightCheckout/js/action/get-active-quote-information',
        'Magento_Ui/js/modal/modal',
        'mage/translate'
    ],
    function($, popupTpl, getActiveQuoteInformationAction) {
        "use strict";

        $.widget('GoMage.Popup', {
            options: {
                modalForm: '#checkout-popup',
                modalButton: '.popup-open'
            },
            _create: function() {
                this._super();
                this.options.modalOption = this.getModalOptions();
                this._bind();
            },
            getModalOptions: function() {
                /** * Modal options */
                return {
                    type: 'popup',
                    responsive: true,
                    clickableOverlay: false,
                    title: $.mage.__('Checkout'),
                    modalClass: 'gslc popup-checkout',
                    buttons: [],
                    popupTpl: popupTpl
                };
            },
            _bind: function(){
                var modalOption = this.options.modalOption;
                var modalForm = this.options.modalForm;

                $(document).on('click', this.options.modalButton, function(){
                    getActiveQuoteInformationAction();
                    $(modalForm).modal(modalOption);
                    $(modalForm).trigger('openModal');
                });
            }
        });

        return $.GoMage.Popup;
    }
);
