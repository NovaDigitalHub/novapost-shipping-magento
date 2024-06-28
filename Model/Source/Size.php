<?php

namespace Novapost\Shipping\Model\Source;

class Size implements \Magento\Shipping\Model\Carrier\Source\GenericInterface
{
    /**
     * Returns array to be used in select on back-end
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('Regular'), 'value' => 0],
            ['label' => __('Specific'), 'value' => 1]
        ];
    }
}
