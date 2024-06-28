define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';
    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction, messageContainer) {
            let shippingAddress = quote.shippingAddress(),
                street = '',
                building = '';

            if (shippingAddress['extension_attributes'] == undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            if (quote.shippingMethod().method_code == 'novapost_shipping_courier') {
                let geoSelect = $('[name="np_geo_address"]').val();
                if (geoSelect) {
                    street = $('[name="street[0]"]').val();
                    building = $('[name="street[1]"]').val();
                } else {
                    street = $('[name="np_geo_address_input"]').val();
                    building = $('[name="np_geo_address_building"]').val();
                }

                shippingAddress['extension_attributes']['np_geo_address'] = street;
                shippingAddress['extension_attributes']['np_geo_address_building'] = building;
            } else {
                shippingAddress['extension_attributes']['np_settlement_external_id'] = $('[name="np_settlement_external_id"]').val();
                shippingAddress['extension_attributes']['np_warehouse_external_id'] = $('[name="np_warehouse_external_id"]').val();
            }

            return originalAction();
        });
    };
});
