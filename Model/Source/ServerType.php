<?php

namespace Novapost\Shipping\Model\Source;

/**
 * Server type source
 */
class ServerType implements \Magento\Shipping\Model\Carrier\Source\GenericInterface
{
    /**
     * Returns array to be used in select on back-end
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('production'),
                'value' => \Novapost\Shipping\Helper\Data::NP_SERVER_TYPE_PROD
            ],
            [
                'label' => __('sandbox'),
                'value' => \Novapost\Shipping\Helper\Data::NP_SERVER_TYPE_SAND
            ]
        ];
    }
}
