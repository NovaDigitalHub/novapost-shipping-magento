define(
    [],
    function () {
        "use strict";
        return {
            getRules: function () {
                return {
                    'postcode': {
                        'required': false
                    },
                    'country_id': {
                        'required': false
                    },
                    'region': {
                        'required': false
                    },
                    'region_id': {
                        'required': false
                    },
                    'np_settlement_external_id': {
                        'required': false
                    },
                    'np_warehouse_external_id': {
                        'required': false
                    },
                    'np_geo_address': {
                        'required': false
                    }
                };
            }
        };
    }
);
