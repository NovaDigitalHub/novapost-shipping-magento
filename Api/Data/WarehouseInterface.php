<?php

namespace Novapost\Shipping\Api\Data;

interface WarehouseInterface
{
    const ENTITY_ID                                 = 'entity_id';
    const DIVISION_ID                               = 'division_id';
    const NAME                                      = 'name';
    const SHORT_NAME                                = 'short_name';
    const EXTERNAL_ID                               = 'external_id';
    const SOURCE                                    = 'source';
    const COUNTRY_CODE                              = 'country_code';
    const SETTLEMENT_ID                             = 'settlement_id';
    const ADDRESS                                   = 'address';
    const NUMBER                                    = 'number';
    const STATUS                                    = 'status';
    const CITY_DESCRIPTION                          = 'city_description';
    const CUSTOMER_SERVICE_AVAILABLE                = 'customer_service_available';
    const DIVISION_CATEGORY                         = 'division_category';
    const PUBLIC_PHONES                             = 'public_phones';
    const INTERNAL_PHONES                           = 'internal_phones';
    const RESPONSIBLE_PERSON                        = 'responsible_person';
    const SETTLEMENT_TYPE_DESCRIPTION               = 'settlement_type_description';
    const PARTNER                                   = 'partner';
    const OWNER_DIVISION                            = 'owner_division';
    const LATITUDE                                  = 'latitude';
    const LONGITUDE                                 = 'longitude';
    const POSTERMINAL                               = 'posterminal';
    const DISTANCE                                  = 'distance';
    const POSTCODE                                  = 'postcode';
    const MAX_WEIGHT_PLACE_SENDER                   = 'max_weight_place_sender';
    const MAX_LENGTH_PLACE_SENDER                   = 'max_length_place_sender';
    const MAX_WIDTH_PLACE_SENDER                    = 'max_width_place_sender';
    const MAX_HEIGHT_PLACE_SENDER                   = 'max_height_place_sender';
    const MAX_WEIGHT_PLACE_RECIPIENT                = 'max_weight_place_recipient';
    const MAX_LENGTH_PLACE_RECIPIENT                = 'max_length_place_recipient';
    const MAX_WIDTH_PLACE_RECIPIENT                 = 'max_width_place_recipient';
    const MAX_HEIGHT_PLACE_RECIPIENT                = 'max_height_place_recipient';
    const PROHIBITED_SENDING                        = 'prohibited_sending';
    const MAX_COST_PLACE                            = 'max_cost_place';
    const MAX_DECLARED_COST_PLACE                   = 'max_declared_cost_place';
    const WORK_SCHEDULE                             = 'work_schedule';

    /**
     * Gets the warehouse entity ID.
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
     * Gets the name of the warehouse.
     *
     * @return string
     */
    public function getName();

    /**
     * Sets the name of the warehouse.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Gets the short name of the warehouse.
     *
     * @return string
     */
    public function getShortName();

    /**
     * Sets the short name of the warehouse
     *
     * @param string $shortName
     * @return $this
     */
    public function setShortName($shortName);

    /**
     * Gets the ExternalId of the warehouse.
     *
     * @return string
     */
    public function getExternalId();

    /**
     * Sets the ExternalId of the warehouse.
     *
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId);

    /**
     * Gets the source of the warehouse.
     *
     * @return string
     */
    public function getSource();

    /**
     * Sets the source of the warehouse.
     *
     * Source of information about a division (for instance, NPUA, NPAX, InPost, NPDepartment, NPMD1C, GLS_CZ, DPD,
     * Venipak, SPS, ExpressOne. The list grows as additional partners are incorporated).
     *
     * @param string $source
     * @return $this
     */
    public function setSource($source);

    /**
     * Gets the country code of the warehouse.
     *
     * @return string
     */
    public function getCountryCode();

    /**
     * Sets the country code of the warehouse.
     *
     * Country code where warehouse is located according to the ISO 3166-1 Alpha-2 standard.
     *
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode($countryCode);

    /**
     * Gets the settlement Id of the warehouse.
     *
     * @return int
     */
    public function getSettlementId();

    /**
     * Sets the settlement Id of the warehouse.
     *
     * @param int $settlementId
     * @return $this
     */
    public function setSettlementId($settlementId);

    /**
     * Gets the address of the warehouse.
     *
     * @return string
     */
    public function getAddress();

    /**
     * Sets the address of the warehouse.
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address);

    /**
     * Gets the number of warehouse.
     *
     * @return string
     */
    public function getNumber();

    /**
     * Sets the number of warehouse.
     *
     * @param string $number
     * @return $this
     */
    public function setNumber($number);

    /**
     * Gets the status of warehouse.
     *
     * @return string
     */
    public function getStatus();

    /**
     * Sets the current operational status of warehouse.
     *
     * Possible values states are Working, NotWorking, NotWorkingTemporary, InProcessOpening.
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Gets the customer service availability of the warehouse.
     *
     * @return bool
     */
    public function getCustomerServiceAvailable();

    /**
     * Sets the customer service availability of the warehouse.
     *
     * @param bool $customerServiceAvailable
     *
     * @return $this
     */
    public function setCustomerServiceAvailable($customerServiceAvailable);

    /**
     * Gets the division category type of the warehouse.
     *
     * @return string
     */
    public function getDivisionCategory();

    /**
     * Sets the division category type of the warehouse.
     *
     * @param string $divisionCategory
     * @return $this
     */
    public function setDivisionCategory($divisionCategory);

    /**
     * Gets the public phones of the warehouse.
     *
     * @return string[]
     */
    public function getPublicPhones();

    /**
     * Sets the public phone of the warehouse.
     *
     * @param string[] $publicPhones
     * @return $this
     */
    public function setPublicPhones($publicPhones);

    /**
     * Gets the internal phones of the warehouse.
     *
     * @return null[]
     */
    public function getInternalPhones();

    /**
     * Sets the internal phones of the warehouse.
     *
     * @param null[] $internalPhones
     *
     * @return $this
     */
    public function setInternalPhones($internalPhones);

    /**
     * Gets the responsible person of the warehouse.
     *
     * @return int
     */
    public function getResponsiblePerson();

    /**
     * Sets the responsible person of the warehouse.
     *
     * @param int $responsiblePerson
     * @return $this
     */
    public function setResponsiblePerson($responsiblePerson);

    /**
     * Gets the partner of the warehouse.
     *
     * @return int
     */
    public function getPartner();

    /**
     * Sets the partner of the warehouse.
     *
     * @param int $partner
     * @return $this
     */
    public function setPartner($partner);

    /**
     * Gets the owner division of the warehouse.
     *
     * @return int
     */
    public function getOwnerDivision();

    /**
     * Sets the owner division of the warehouse.
     *
     * @param int $ownerDivision
     * @return $this
     */
    public function setOwnerDivision($ownerDivision);

    /**
     * Gets the latitude coordinate of the warehouse.
     *
     * @return float
     */
    public function getLatitude();

    /**
     * Sets the latitude coordinate of the warehouse.
     *
     * @param float $latitude
     * @return $this
     */
    public function setLatitude($latitude);

    /**
     * Gets the longitude coordinate of the warehouse.
     *
     * @return float
     */
    public function getLongitude();

    /**
     * Sets the longitude coordinate of the warehouse.
     *
     * @param float $longitude
     * @return $this
     */
    public function setLongitude($longitude);

    /**
     * Gets the postcode of the warehouse.
     *
     * @return int
     */
    public function getPostcode();

    /**
     * Sets the postcode of the warehouse.
     *
     * @param string $postcode
     * @return $this
     */
    public function setPostcode($postcode);

    /**
     * Gets the distance of the warehouse.
     *
     * @return int
     */
    public function getDistance();

    /**
     * Sets the distance of the warehouse.
     *
     * @param int $distance
     * @return $this
     */
    public function setDistance($distance);

    /**
     * Gets the sender's maximum weight for a single parcel that can be dispatched from this warehouse.
     *
     * @return int
     */
    public function getMaxWeightPlaceSender();

    /**
     * Sets the sender's maximum weight for a single parcel, measured in grams.
     *
     * @param int $maxWeightPlaceSender
     * @return $this
     */
    public function setMaxWeightPlaceSender($maxWeightPlaceSender);

    /**
     * Gets the sender's maximum length for a single parcel that can be dispatched from this warehouse.
     *
     * @return int
     */
    public function getMaxLengthPlaceSender();

    /**
     * Sets the sender's maximum length for a single parcel, measured in millimeters.
     *
     * @param int $maxLengthPlaceSender
     * @return $this
     */
    public function setMaxLengthPlaceSender($maxLengthPlaceSender);

    /**
     * Gets the sender's maximum width for a single parcel that can be dispatched from this warehouse.
     *
     * @return int
     */
    public function getMaxWidthPlaceSender();

    /**
     * Sets the sender's maximum width for a single parcel, measured in millimeters.
     *
     * @param int $maxWidthPlaceSender
     * @return $this
     */
    public function setMaxWidthPlaceSender($maxWidthPlaceSender);

    /**
     * Gets the sender's maximum height for a single parcel that can be dispatched from this warehouse.
     *
     * @return int
     */
    public function getMaxHeightPlaceSender();

    /**
     * Sets the sender's maximum height for a single parcel, measured in millimeters.
     *
     * @param int $maxHeightPlaceSender
     * @return $this
     */
    public function setMaxHeightPlaceSender($maxHeightPlaceSender);

    /**
     * Gets the recipient's maximum weight for a single parcel that can be received to this warehouse.
     *
     * @return int
     */
    public function getMaxWeightPlaceRecipient();

    /**
     * Sets the recipient's maximum weight for a single parcel, measured in grams.
     *
     * @param int $maxWeightPlaceRecipient
     * @return $this
     */
    public function setMaxWeightPlaceRecipient($maxWeightPlaceRecipient);

    /**
     * Gets the recipient's maximum length for a single parcel that can be received to this warehouse.
     *
     * @return int
     */
    public function getMaxLengthPlaceRecipient();

    /**
     * Sets the recipient's maximum length for a single parcel, measured in millimeters.
     *
     * @param int $maxLengthPlaceRecipient
     * @return $this
     */
    public function setMaxLengthPlaceRecipient($maxLengthPlaceRecipient);

    /**
     * Gets the recipient's maximum width for a single parcel that can be received to this warehouse.
     *
     * @return int
     */
    public function getMaxWidthPlaceRecipient();

    /**
     * Sets the recipient's maximum width for a single parcel, measured in millimeters.
     *
     * @param int $maxWidthPlaceRecipient
     * @return $this
     */
    public function setMaxWidthPlaceRecipient($maxWidthPlaceRecipient);

    /**
     * Gets the recipient's maximum height for a single parcel that can be received to this warehouse.
     *
     * @return int
     */
    public function getMaxHeightPlaceRecipient();

    /**
     * Sets the recipient's maximum height for a single parcel, measured in millimeters.
     *
     * @param int $maxHeightPlaceRecipient
     * @return $this
     */
    public function setMaxHeightPlaceRecipient($maxHeightPlaceRecipient);

    /**
     * Gets the max cost for one shipment to this warehouse.
     *
     * @return int
     */
    public function getMaxCostPlace();

    /**
     * Sets the max cost for one shipment to this warehouse.
     *
     * @param int $maxCostPlace
     * @return $this
     */
    public function setMaxCostPlace($maxCostPlace);

    /**
     * Gets the max declared cost for one shipment to this warehouse.
     *
     * @return int
     */
    public function getMaxDeclaredCostPlace();

    /**
     * Sets the max declared cost for one shipment to this warehouse.
     *
     * @param int $maxDeclaredCostPlace
     * @return $this
     */
    public function setMaxDeclaredCostPlace($maxDeclaredCostPlace);

    /**
     * Gets the working schedule of the warehouse.
     *
     * @return string[]
     */
    public function getWorkSchedule();

    /**
     * Sets the working schedule of the warehouse. Returns an array with working days and times.
     *
     * @param string[] $workSchedule
     * @return $this
     */
    public function setWorkSchedule($workSchedule);
}
