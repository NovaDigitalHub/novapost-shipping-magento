<?php

namespace Novapost\Shipping\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Region extends AbstractDb
{
    /**
     * Region resource model constructor
     */
    public function _construct()
    {
        /**
         * Initialize resource model
         */
        $this->_init('np_region', 'entity_id');
    }
}
