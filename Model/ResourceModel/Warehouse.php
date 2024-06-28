<?php

namespace Novapost\Shipping\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Warehouse extends AbstractDb
{
    /**
     * Warehouse resource model constructor
     */
    public function _construct()
    {
        /**
         * Initialize resource model
         */
        $this->_init('np_warehouse', 'entity_id');
    }

    /**
     * Retrieves the warehouse ID by warehouse name and settlement ID.
     *
     * @param string $name
     * @param int $settlementId
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getWarehouseIdByNameAndSettlement($name, $settlementId)
    {
        $select = $this->getConnection()->select()
            ->from(['warehouse' => $this->getMainTable()], 'division_id')
            ->where('warehouse.name=?', $name)
            ->where('warehouse.settlement_id=?', $settlementId)
            ->limit(1);
        $row = $this->getConnection()->fetchRow($select);

        return $row['division_id'] ?? '';
    }
}
