<?php

namespace Novapost\Shipping\Model;

use Novapost\Shipping\Api\Data\AreaInterface;
use Magento\Framework\Model\AbstractModel;
use Novapost\Shipping\Model\ResourceModel\Area as AreaResource;

class Area extends AbstractModel implements AreaInterface
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(AreaResource::class);
    }

    /**
     * Gets the area entity ID.
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
     * @return Area
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Gets the area ExternalId.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->getData(self::EXTERNAL_ID);
    }

    /**
     * Sets the ExternalId of the area.
     *
     * @param string $externalId
     * @return Area
     */
    public function setExternalId($externalId)
    {
        return $this->setData(self::EXTERNAL_ID, $externalId);
    }

    /**
     * Gets the name of the area.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Sets the name of the area.
     *
     * @param string $name
     * @return Area
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Gets the country of the area.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getData(self::COUNTRY_CODE);
    }

    /**
     * Sets the country of the area.
     *
     * @param string $countryCode
     * @return Area
     */
    public function setCountryCode($countryCode)
    {
        return $this->setData(self::COUNTRY_CODE, $countryCode);
    }
}
