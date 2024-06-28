<?php

namespace Novapost\Shipping\Model\ResourceModel\Warehouse;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Novapost\Shipping\Model\Warehouse as WarehouseModel;
use Novapost\Shipping\Model\ResourceModel\Warehouse as WarehouseResource;

class Collection extends AbstractCollection
{
    /**
     * Warehouse constructor.
     */
    public function _construct()
    {
        /**
         * Initialize the collection.
         */
        $this->_init(WarehouseModel::class, WarehouseResource::class);
    }
}
