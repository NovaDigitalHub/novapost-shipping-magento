<?php

namespace Novapost\Shipping\Model\Source;

/**
 * Method source
 */
class Method implements \Magento\Shipping\Model\Carrier\Source\GenericInterface
{

    /**
     * Returns array to be used in multiselect on back-end
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'novapost_shipping_warehouse', 'label' => 'NOVA POST Warehouse'],
            ['value' => 'novapost_shipping_courier', 'label' => 'NOVA POST Courier'],
        ];
    }
}
