<?php

namespace Novapost\Shipping\Model\ResourceModel\Region;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Novapost\Shipping\Model\Region as RegionModel;
use Novapost\Shipping\Model\ResourceModel\Region as RegionResource;

class Collection extends AbstractCollection
{
    /**
     * Region constructor.
     */
    public function _construct()
    {
        /**
         * Initialize the collection.
         */
        $this->_init(RegionModel::class, RegionResource::class);
    }
}
