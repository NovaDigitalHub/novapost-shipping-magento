<?php

namespace Novapost\Shipping\Api\Data;

interface SettlementInterface
{
    const ENTITY_ID                                 = 'entity_id';
    const EXTERNAL_ID                               = 'external_id';
    const NAME                                      = 'name';
    const REGION_ID                                 = 'region_id';
    const COUNTRY_CODE                              = 'country_code';

    /**
     * Gets the settlement entity ID.
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
     * Gets the settlement ExternalId.
     *
     * @return mixed
     */
    public function getExternalId();

    /**
     * Sets the ExternalId of the settlement.
     *
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId);

    /**
     * Gets the name of the settlement.
     *
     * @return mixed
     */
    public function getName();

    /**
     * Sets the name of the settlement.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Gets the settlement's region.
     *
     * @return int
     */
    public function getRegionId();

    /**
     * Sets the settlement of the region.
     *
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId);

    /**
     * Gets the settlement's country code.
     *
     * @return string
     */
    public function getCountryCode();

    /**
     * Sets the settlement of the country code.
     *
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode($countryCode);
}
