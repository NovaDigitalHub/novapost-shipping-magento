define([
    'jquery',
    'Magento_Ui/js/form/element/select',
    'Magento_Checkout/js/model/quote',
    'mage/url',
    'mage/translate',
    'Novapost_Shipping/js/model/settlement',
    'Novapost_Shipping/js/lib/select2/select2'
], function ($, Select, quote, url, $t, settlement) {
    'use strict';
    return Select.extend({

        defaults: {
            settlementName: '',
            imports: {
                update: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.country_id:value'
            },
            exports: {
                settlementName: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city:value'
            }
        },

        currentCountry: '',
        settlements: {},

        initialize: function () {
            this._super();

            this.settlementName(this.getPreview());

            return this;
        },

        update: function (value) {
            let self = this,
                settlement = $('select[name=np_settlement_external_id]'),
                warehouse = $('select[name=np_warehouse_external_id]');

            settlement.html('');
            warehouse.html('');

            $.ajax({
                url: url.build('rest/V1/novapost/settlement'),
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify({
                        countryCode: value
                    }),
                contentType: 'application/json',
                error: function () {
                    alert($t('Error loading settlement'));
                },
                success: function (data) {
                    let items = JSON.parse(data);

                    self.currentCountry = value;
                    self.settlements = items;
                    self.select2('select[name=np_settlement_external_id]');

                    return items;
                }
            });
        },

        select2: function (element) {
            let self = this;
            let settlements = self.settlements;

            $.fn.select2.amd.require(["select2/data/array", "select2/utils"],
                function (ArrayData, Utils) {
                    function SettlementData($element, options) {
                        SettlementData.__super__.constructor.call(this, $element, options);
                    }
                    Utils.Extend(SettlementData, ArrayData);

                    SettlementData.prototype.query = function (params, callback) {

                        let pageSize = 20;
                        let results = settlements;
                        let data = {};

                        if (params.term && params.term !== '') {
                            results = _.filter(settlements, function(e) {
                                return e.text != null ? e.text.toUpperCase().indexOf(params.term.toUpperCase()) >= 0 : false;
                            });
                        }

                        params.page = params.page || 1;

                        data.results = results.slice((params.page - 1) * pageSize, params.page * pageSize);
                        data.pagination = {};
                        data.pagination.more = params.page * pageSize < results.length;
                        callback(data);
                    };

                    $(element).select2({
                        data: settlements,
                        ajax: {},
                        dataAdapter: SettlementData
                    });
                })
        },

        initObservable: function () {
            this._super();
            this.observe('settlementName');

            return this;
        },

        getPreview: function () {
            return $('[name="' + this.inputName + '"] option:selected').text();
        },

        getSettlementName: function () {
            return this.settlementName();
        },

        setDifferedFromDefault: function () {
            this._super();
            this.settlementName(this.getPreview());
        },

        selectedMethodCode: function () {
            let method = quote.shippingMethod();
            let selectedMethodCode = method != null ? method.method_code : false;

            return selectedMethodCode === 'novapost_shipping_warehouse' ? selectedMethodCode : '';
        },

        loadWarehouses: function (e) {
            if (this.selectedMethodCode()) {
                settlement.getWarehouses();
            }

        },

    });
});
