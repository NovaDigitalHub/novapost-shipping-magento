<?php

namespace Novapost\Shipping\Model\ResourceModel\Area;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Novapost\Shipping\Model\Area as AreaModel;
use Novapost\Shipping\Model\ResourceModel\Area as AreaResource;

class Collection extends AbstractCollection
{
    /**
     * Area constructor.
     */
    public function _construct()
    {
        /**
         * Initialize the collection.
         */
        $this->_init(AreaModel::class, AreaResource::class);
    }
}
