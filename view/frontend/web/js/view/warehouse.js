define([
    'jquery',
    'Magento_Ui/js/form/element/select',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/address-list',
    'Novapost_Shipping/js/model/settlement',
    'mage/translate'
], function ($, Select, quote, addressList, settlement, $t) {
    'use strict';


    return Select.extend({

        defaults: {
            street: '',
            postCode: '',
            exports: {
                street: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.0:value',
                building: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.1:value',
                postCode: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.postcode:value'
            }
        },

        warehouses: {},

        initialize: function () {
            this._super();
            this.setOptions(this.warehouses);
            this.street(this.getPreview());
            return this;
        },

        initObservable: function () {
            this._super();
            this.observe('street');
            this.observe('building');
            this.observe('postCode');
            return this;
        },

        getPreview: function () {
            return $('[name="' + this.inputName + '"] option:selected').text();
        },

        selectedMethodCode: function () {
            let method = quote.shippingMethod();
            let selectedMethodCode = method != null ? method.method_code : false;

            return selectedMethodCode === 'novapost_shipping_warehouse' ? selectedMethodCode : '';
        },

        setDifferedFromDefault: function () {
            this._super();
            this.street(this.getPreview());
        },

        select2: function (element) {
            let self = this;

            $(element).select2({
                placeholder: $t('choose a warehouse'),
                dropdownAutoWidth: true
            }).on('select2:select', function(e) {
                let postcode = e.params.data?.element?.getAttribute('data-postcode');
                if (postcode) {
                    self.postCode(postcode);
                }

                self.building('');
            });
        }

    });
});
