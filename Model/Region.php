<?php

namespace Novapost\Shipping\Model;

use Novapost\Shipping\Api\Data\RegionInterface;
use Magento\Framework\Model\AbstractModel;
use Novapost\Shipping\Model\ResourceModel\Region as RegionResource;

class Region extends AbstractModel implements RegionInterface
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(RegionResource::class);
    }

    /**
     * Gets the region entity ID.
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Sets entity ID.
     *
     * @param int $entityId
     * @return Region
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Gets the region ExternalId.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->getData(self::EXTERNAL_ID);
    }

    /**
     * Sets the ExternalId of the region.
     *
     * @param string $externalId
     * @return Region
     */
    public function setExternalId($externalId)
    {
        return $this->setData(self::EXTERNAL_ID, $externalId);
    }

    /**
     * Gets the name of the region.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Sets the name of the region.
     *
     * @param string $name
     * @return Region
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Gets the area of the region.
     *
     * @return int
     */
    public function getAreaId()
    {
        return $this->getData(self::AREA_ID);
    }

    /**
     * Sets the area of the region.
     *
     * @param int $areaId
     * @return Region
     */
    public function setAreaId($areaId)
    {
        return $this->setData(self::AREA_ID, $areaId);
    }
}
