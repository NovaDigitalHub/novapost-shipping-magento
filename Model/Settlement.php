<?php

namespace Novapost\Shipping\Model;

use Novapost\Shipping\Api\Data\SettlementInterface;
use Magento\Framework\Model\AbstractModel;
use Novapost\Shipping\Model\ResourceModel\Settlement as SettlementResource;

class Settlement extends AbstractModel implements SettlementInterface
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(SettlementResource::class);
    }

    /**
     * Gets the settlement entity ID.
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
     * @return Settlement
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Gets the settlement ExternalId.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->getData(self::EXTERNAL_ID);
    }

    /**
     * Sets the ExternalId of the settlement.
     *
     * @param string $externalId
     * @return Settlement
     */
    public function setExternalId($externalId)
    {
        return $this->setData(self::EXTERNAL_ID, $externalId);
    }

    /**
     * Gets the name of the settlement.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Sets the name of the settlement.
     *
     * @param string $name
     * @return Settlement
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Gets the settlement's region.
     *
     * @return int
     */
    public function getRegionId()
    {
        return $this->getData(self::REGION_ID);
    }

    /**
     * Sets the settlement of the region.
     *
     * @param int $regionId
     * @return Settlement
     */
    public function setRegionId($regionId)
    {
        return $this->setData(self::REGION_ID, $regionId);
    }

    /**
     * Gets the settlement's country code.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getData(self::COUNTRY_CODE);
    }

    /**
     * Sets the settlement of the country code.
     *
     * @param string $countryCode
     * @return Settlement
     */
    public function setCountryCode($countryCode)
    {
        return $this->setData(self::COUNTRY_CODE, $countryCode);
    }
}
