<?php

namespace Novapost\Shipping\Model\Source;

/**
 * Warehouse naming source
 */
class Naming implements \Magento\Shipping\Model\Carrier\Source\GenericInterface
{

    /**
     * Returns array to be used in select on back-end
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'shortName', 'label' => 'Short Name'],
            ['value' => 'fullName', 'label' => 'Full Name'],
        ];
    }
}
