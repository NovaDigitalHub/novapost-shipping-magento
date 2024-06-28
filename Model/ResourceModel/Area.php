<?php

namespace Novapost\Shipping\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Area extends AbstractDb
{
    /**
     * Area resource model constructor
     */
    public function _construct()
    {
        /**
         * Initialize resource model
         */
        $this->_init('np_area', 'entity_id');
    }
}
