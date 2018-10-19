define(
    [
        'jquery',
        'text!GoMage_SuperLightCheckout/template/modal/modal-popup.html',
        'Magento_Ui/js/modal/modal'
    ],
    function($, popupTpl) {
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
                    title: $.mage.__('PopUp 222'),
                    modalClass: 'popup-checkout',
                    buttons: [],
                    popupTpl: popupTpl
                };
            },
            _bind: function(){
                var modalOption = this.options.modalOption;
                var modalForm = this.options.modalForm;

                $(document).on('click', this.options.modalButton, function(){
                    $(modalForm).modal(modalOption);
                    $(modalForm).trigger('openModal');
                });
            }
        });

        return $.GoMage.Popup;
    }
);
