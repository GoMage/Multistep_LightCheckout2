define(
    [
        'jquery',
        'Magento_Ui/js/modal/modal'
    ],
    function($) {
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
                    title: $.mage.__('PopUp'),
                    modalClass: 'popup',
                    buttons: []
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
