<?php

namespace Novapost\Shipping\Model\ResourceModel\Settlement;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Novapost\Shipping\Model\Settlement as SettlementModel;
use Novapost\Shipping\Model\ResourceModel\Settlement as SettlementResource;

class Collection extends AbstractCollection
{
    /**
     * Settlement constructor.
     */
    public function _construct()
    {
        /**
         * Initialize the collection.
         */
        $this->_init(SettlementModel::class, SettlementResource::class);
    }
}
