<?php

namespace Novapost\Shipping\Api\Data;

interface AreaInterface
{
    const ENTITY_ID                                 = 'entity_id';
    const EXTERNAL_ID                               = 'external_id';
    const NAME                                      = 'name';
    const COUNTRY_CODE                              = 'country_code';

    /**
     * Gets the area entity ID.
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
     * Gets the area ExternalId.
     *
     * @return string
     */
    public function getExternalId();

    /**
     *  Sets the ExternalId of the area.
     *
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId);

    /**
     * Gets the name of the area.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the name of the area.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Gets the country of the area.
     *
     * @return string
     */
    public function getCountryCode();

    /**
     * Sets the country of the area.
     *
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode($countryCode);
}
