define([
    'jquery',
    'Magento_Checkout/js/model/url-builder',
    'mage/storage',
    'uiRegistry',
    'Magento_Customer/js/model/address-list',
    'Magento_Checkout/js/model/quote',
    'mage/url',
    'Magento_Checkout/js/model/full-screen-loader',
    'loader',
    'jquery/ui',
], function ($, urlBuilder, storage, registry, addressList, quote, url, fullScreenLoader) {
    'use strict';

    return {
        getWarehouses: function () {
            var settlementId = $('select[name=np_settlement_external_id]').val();

            if (settlementId && settlementId != undefined && parseInt(settlementId) != 0) {
                var dropdown = $('select[name=np_warehouse_external_id]');
                fullScreenLoader.startLoader();
                $.ajax({
                    url: url.build('rest/V1/novapost/warehouse'),
                    data: JSON.stringify({
                        settlementId: settlementId
                    }),
                    contentType: "application/json",
                    type: "POST",
                    dataType: 'json',
                    error : function () {
                        console.log('Error loading data');
                        fullScreenLoader.stopLoader();
                    },
                    success : function (data) {
                        var items = JSON.parse(data);
                        dropdown.html("");
                        $.each(items, function (key, entry) {
                            dropdown.append($('<option></option>').attr('value', entry.id).attr('data-postcode', entry.postcode).text(entry.text));
                        });
                        fullScreenLoader.stopLoader();
                    }
                });
            }
        }
    };
})
