<?php

namespace Novapost\Shipping\Model\Source;

/**
 * Sender type source
 */
class SenderType implements \Magento\Shipping\Model\Carrier\Source\GenericInterface
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
                'label' => __('the post office'),
                'value' => \Novapost\Shipping\Helper\Data::SENDER_TYPE_OFFICE
            ],
            [
                'label' => __('actual address'),
                'value' => \Novapost\Shipping\Helper\Data::SENDER_TYPE_ADDRESS
            ]
        ];
    }
}
