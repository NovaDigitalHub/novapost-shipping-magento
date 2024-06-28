define([
    'jquery',
    'Magento_Ui/js/form/element/select',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/address-list',
    'mage/url',
    'mage/translate'
], function ($, Select, quote, addressList, url, $t) {
    'use strict';


    return Select.extend({

        defaults: {
            country_id: '',
            city: '',
            postCode: '',
            street: '',
            building: '',
            imports: {
                'country_id': 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.country_id:value'
            },
            exports: {
                street: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.0:value',
                building: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street.1:value',
                city: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city:value',
                postCode: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.postcode:value'
            }
        },

        addresses: {},

        initialize: function () {
            this._super();
            this.setOptions(this.addresses);
            return this;
        },

        initObservable: function () {
            this._super();
            this.observe('postCode');
            this.observe('city');
            this.observe('street');
            this.observe('building');
            return this;
        },

        getCountry: function () {
            let allowedCountry = ['PL', 'LT', 'LV', 'EE', 'DE', 'IT', 'ES'];

            return allowedCountry.includes(this.country_id);
        },

        selectedMethodCode: function () {
            let method = quote.shippingMethod();
            let selectedMethodCode = method != null ? method.method_code : false;

            return selectedMethodCode === 'novapost_shipping_courier' ? selectedMethodCode : '';
        },

        select2: function (element) {
            let self = this;

            function formatRepo (repo) {
                if (repo.loading) {
                    return repo.text;
                }

                let $container = $(
                    "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'></div>" +
                    "<div class='select2-result-repository__description'></div>" +
                    "</div>" +
                    "</div>"
                );

                let address = repo.text;
                if (repo.street && repo.building) {
                    address = repo.street + ', ' + repo.building;
                }

                $container.find(".select2-result-repository__title").text(repo.city);
                $container.find(".select2-result-repository__description").text(address);

                return $container;
            }
            $(element).select2({
                minimumInputLength: 2,
                templateResult: formatRepo,
                ajax: {
                    url: url.build('novapost/place/'),
                    dataType: 'json',
                    type: 'POST',
                    data: function(params) {
                        return {
                            address: params.term,
                            country_id: self.country_id
                        };
                    },
                    processResults: function(data) {
                        data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        });

                        return {
                            results: data
                        };
                    },
                    error: function () {
                        console.log('Error loading data');
                    }
                },
                placeholder: $t('Start typing to search'),
                dropdownAutoWidth: true,

            }).on('select2:select', function(e) {
                let data = e.params.data;
                if (data?.city) {
                    self.city(data.city);
                }

                if (data?.street) {
                    self.street(data.street);
                }

                if (data?.building) {
                    self.building(data.building);
                }

                if (data?.zip_code) {
                    self.postCode(data.zip_code);
                }
            });
        }

    });
});
