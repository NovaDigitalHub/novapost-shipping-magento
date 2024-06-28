<?php

namespace Novapost\Shipping\Model\Source;

/**
 * Measurement source
 */
class Measurement implements \Magento\Shipping\Model\Carrier\Source\GenericInterface
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
                'label' => __('pieces'),
                'value' => \Novapost\Shipping\Helper\Data::MEASUREMENT_PIECES
            ],
            [
                'label' => __('kilograms'),
                'value' => \Novapost\Shipping\Helper\Data::MEASUREMENT_KILOGRAMS
            ],
            [
                'label' => __('meters'),
                'value' => \Novapost\Shipping\Helper\Data::MEASUREMENT_METERS
            ]
        ];
    }
}
