define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        'Novapost_Shipping/js/model/shipping-rates-validator',
        'Novapost_Shipping/js/model/shipping-rates-validation-rules'
    ],
    function (
        Component,
        defaultShippingRatesValidator,
        defaultShippingRatesValidationRules,
        shippingRatesValidator,
        shippingRatesValidationRules
    ) {
        'use strict';

        defaultShippingRatesValidator.registerValidator('novapost', shippingRatesValidator);
        defaultShippingRatesValidationRules.registerRules('novapost', shippingRatesValidationRules);

        return Component;
    }
);
