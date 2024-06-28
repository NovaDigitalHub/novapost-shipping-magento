<?php

namespace Novapost\Shipping\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Settlement extends AbstractDb
{
    /**
     * Settlement resource model constructor
     */
    public function _construct()
    {
        /**
         * Initialize resource model
         */
        $this->_init('np_settlement', 'entity_id');
    }

    /**
     * Retrieves the settlement ID by settlement name.
     *
     * @param string $name
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSettlementIdByName($name)
    {
        $select = $this->getConnection()->select()
            ->from(['settlement' => $this->getMainTable()], 'external_id')
            ->where('settlement.name=?', $name)
            ->limit(1);
        $row = $this->getConnection()->fetchRow($select);

        return $row['external_id'] ?? '';
    }
}
