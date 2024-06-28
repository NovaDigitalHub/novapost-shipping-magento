define([
    'jquery',
    'Magento_Ui/js/form/element/textarea',
    'Magento_Checkout/js/model/quote',
    'mage/translate'
], function ($, Input, quote, $t) {
    'use strict';

    return Input.extend({

        defaults: {
            building: '',
            placeholder: $t('Enter building'),
            imports: {
                getCountry: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.country_id:value'
            },
            exports: {
                'building': 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.1:value'
            }
        },
        countryCode: false,

        initialize: function () {
            this._super();
            return this;
        },

        initObservable: function () {
            this._super();
            this.observe('building');
            return this;
        },

        getPreview: function () {
            return $('[name="' + this.inputName + '"]').val();
        },

        getCountry: function (value = this.countryCode) {
            let allowedCountry = ['PL', 'LT', 'LV', 'EE', 'DE', 'IT', 'ES'];

            this.countryCode = value;
            return !allowedCountry.includes(value);
        },

        selectedMethodCode: function () {
            let method = quote.shippingMethod();
            let selectedMethodCode = method != null ? method.method_code : false;

            return selectedMethodCode === 'novapost_shipping_courier' ? selectedMethodCode : '';
        }
    });
});
