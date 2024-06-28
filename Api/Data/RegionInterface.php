<?php

namespace Novapost\Shipping\Api\Data;

/**
 * Interface RegionInterface
 *
 * @api
 * @since 1.0.0
 */
interface RegionInterface
{
    const ENTITY_ID                                 = 'entity_id';
    const EXTERNAL_ID                               = 'external_id';
    const NAME                                      = 'name';
    const AREA_ID                                   = 'area_id';

    /**
     * Gets the region entity ID.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Sets entity ID.
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Gets the region ExternalId.
     *
     * @return string
     */
    public function getExternalId();

    /**
     * Sets the ExternalId of the region.
     *
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId);

    /**
     * Gets the name of the region.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the name of the region.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Gets the area of the region.
     *
     * @return int
     */
    public function getAreaId();

    /**
     * Sets the area of the region.
     *
     * @param int $areaId
     * @return $this
     */
    public function setAreaId($areaId);
}
