<?php

namespace Novapost\Shipping\Model;

use Novapost\Shipping\Api\Data\WarehouseInterface;
use Magento\Framework\Model\AbstractModel;
use Novapost\Shipping\Model\ResourceModel\Warehouse as WarehouseResource;

class Warehouse extends AbstractModel implements WarehouseInterface
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(WarehouseResource::class);
    }

    /**
     * Gets the warehouse entity ID.
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
     * @return Warehouse
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Gets the division ID of the warehouse.
     *
     * @return int
     */
    public function getDivisionId()
    {
        return $this->getData(self::DIVISION_ID);
    }

    /**
     * Sets the division ID of the warehouse.
     *
     * @param int $divisionId
     * @return Warehouse
     */
    public function setDivisionId($divisionId)
    {
        return $this->setData(self::DIVISION_ID, $divisionId);
    }

    /**
     * Gets the name of the warehouse.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Sets the name of the warehouse.
     *
     * @param string $name
     * @return warehouse
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Gets the short name of the warehouse.
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->getData(self::SHORT_NAME);
    }

    /**
     * Sets the short name of the warehouse.
     *
     * @param string $shortName
     * @return Warehouse
     */
    public function setShortName($shortName)
    {
        return $this->setData(self::SHORT_NAME, $shortName);
    }

    /**
     * Gets the ExternalId of the warehouse.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->getData(self::EXTERNAL_ID);
    }

    /**
     * Sets the ExternalId of the warehouse
     *
     * @param string $externalId
     * @return Warehouse
     */
    public function setExternalId($externalId)
    {
        return $this->setData(self::EXTERNAL_ID, $externalId);
    }

    /**
     * Gets the source of the warehouse.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->getData(self::SOURCE);
    }

    /**
     * Sets the source of the warehouse.
     *
     * Source of information about a division (for instance, NPUA, NPAX, InPost, NPDepartment, NPMD1C, GLS_CZ, DPD,
     * Venipak, SPS, ExpressOne. The list grows as additional partners are incorporated).
     *
     * @param string $source
     * @return Warehouse
     */
    public function setSource($source)
    {
        return $this->setData(self::SOURCE, $source);
    }

    /**
     * Gets the country code of the warehouse.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->getData(self::COUNTRY_CODE);
    }

    /**
     * Sets the country code of the warehouse.
     *
     * Country code where warehouse is located according to the ISO 3166-1 Alpha-2 standard.
     *
     * @param string $countryCode
     * @return Warehouse
     */
    public function setCountryCode($countryCode)
    {
        return $this->setData(self::COUNTRY_CODE, $countryCode);
    }

    /**
     * Gets the settlement ID of the warehouse.
     *
     * @return int
     */
    public function getSettlementId()
    {
        return $this->getData(self::SETTLEMENT_ID);
    }

    /**
     * Sets the settlement ID of the warehouse.
     *
     * @param int $settlementId
     * @return warehouse
     */
    public function setSettlementId($settlementId)
    {
        return $this->setData(self::SETTLEMENT_ID, $settlementId);
    }

    /**
     * Gets the address of the warehouse.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->getData(self::ADDRESS);
    }

    /**
     * Sets the address of the warehouse.
     *
     * @param string $address
     * @return Warehouse
     */
    public function setAddress($address)
    {
        return $this->setData(self::ADDRESS, $address);
    }

    /**
     * Gets the number of the warehouse.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->getData(self::NUMBER);
    }

    /**
     * Sets the number of the warehouse.
     *
     * @param string $number
     * @return Warehouse
     */
    public function setNumber($number)
    {
        return $this->setData(self::NUMBER);
    }

    /**
     * Gets the status of the warehouse.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Sets the status of the warehouse.
     *
     * Possible values states are Working, NotWorking, NotWorkingTemporary, InProcessOpening.
     *
     * @param string $status
     * @return Warehouse
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Gets the customer service availability of the warehouse.
     *
     * @return bool
     */
    public function getCustomerServiceAvailable()
    {
        return $this->getData(self::CUSTOMER_SERVICE_AVAILABLE);
    }

    /**
     * Sets the customer service availability of the warehouse.
     *
     * @param bool $customerServiceAvailable
     * @return Warehouse
     */
    public function setCustomerServiceAvailable($customerServiceAvailable)
    {
        return $this->setData(self::CUSTOMER_SERVICE_AVAILABLE, $customerServiceAvailable);
    }

    /**
     * Gets the division category type of the warehouse.
     *
     * @return string
     */
    public function getDivisionCategory()
    {
        return $this->getData(self::DIVISION_CATEGORY);
    }

    /**
     * Sets the division category type of the warehouse.
     *
     * @param string $divisionCategory
     * @return Warehouse
     */
    public function setDivisionCategory($divisionCategory)
    {
        return $this->setData(self::DIVISION_CATEGORY, $divisionCategory);
    }

    /**
     * Gets the public phones of the warehouse.
     *
     * @return string[]
     */
    public function getPublicPhones()
    {
        return $this->getData(self::PUBLIC_PHONES);
    }

    /**
     * Sets the public phones of the warehouse.
     *
     * @param string[] $publicPhones
     * @return Warehouse
     */
    public function setPublicPhones($publicPhones)
    {
        return $this->setData(self::PUBLIC_PHONES, $publicPhones);
    }

    /**
     * Gets the internal phones of the warehouse.
     *
     * @return null[]
     */
    public function getInternalPhones()
    {
        return $this->getData(self::INTERNAL_PHONES);
    }

    /**
     * Sets the internal phones of the warehouse.
     *
     * @param null[] $internalPhones
     * @return Warehouse
     */
    public function setInternalPhones($internalPhones)
    {
        return $this->setData(self::INTERNAL_PHONES, $internalPhones);
    }

    /**
     * Gets the responsible person of the warehouse.
     *
     * @return int
     */
    public function getResponsiblePerson()
    {
        return $this->getData(self::RESPONSIBLE_PERSON);
    }

    /**
     * Sets the responsible person of the warehouse.
     *
     * @param int $responsiblePerson
     * @return Warehouse
     */
    public function setResponsiblePerson($responsiblePerson)
    {
        return $this->setData(self::RESPONSIBLE_PERSON, $responsiblePerson);
    }

    /**
     * Gets the partner of the warehouse.
     *
     * @return int
     */
    public function getPartner()
    {
        return $this->getData(self::PARTNER);
    }

    /**
     * Sets the partner of the warehouse.
     *
     * @param int $partner
     * @return Warehouse
     */
    public function setPartner($partner)
    {
        return $this->setData(self::PARTNER, $partner);
    }

    /**
     * Gets the owner division of the warehouse.
     *
     * @return int
     */
    public function getOwnerDivision()
    {
        return $this->getData(self::OWNER_DIVISION);
    }

    /**
     * Sets the owner division of the warehouse.
     *
     * @param int $ownerDivision
     * @return Warehouse
     */
    public function setOwnerDivision($ownerDivision)
    {
        return $this->setData(self::OWNER_DIVISION, $ownerDivision);
    }

    /**
     * Gets the latitude coordinate of the warehouse.
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->getData(self::LATITUDE);
    }

    /**
     * Sets the latitude coordinate of the warehouse.
     *
     * @param float $latitude
     * @return Warehouse
     */
    public function setLatitude($latitude)
    {
        return $this->setData(self::LATITUDE, $latitude);
    }

    /**
     * Gets the longitude of the warehouse.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->getData(self::LONGITUDE);
    }

    /**
     * Sets the longitude of the warehouse.
     *
     * @param float $longitude
     * @return Warehouse
     */
    public function setLongitude($longitude)
    {
        return $this->setData(self::LONGITUDE, $longitude);
    }

    /**
     * Gets the distance of the warehouse.
     *
     * @return int
     */
    public function getDistance()
    {
        return $this->getData(self::DISTANCE);
    }

    /**
     * Sets the distance of the warehouse.
     *
     * @param int $distance
     * @return Warehouse
     */
    public function setDistance($distance)
    {
        return $this->setData(self::DISTANCE, $distance);
    }

    /**
     * Gets the postcode of the warehouse.
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->getData(self::POSTCODE);
    }

    /**
     * Sets the postcode of the warehouse.
     *
     * @param string $postcode
     * @return Warehouse
     */
    public function setPostcode($postcode)
    {
        return $this->setData(self::POSTCODE, $postcode);
    }

    /**
     * Gets the sender's maximum weight for a single parcel that can be dispatched from this warehouse.
     *
     * @return int
     */
    public function getMaxWeightPlaceSender()
    {
        return $this->getData(self::MAX_WEIGHT_PLACE_SENDER);
    }

    /**
     * Sets the sender's maximum weight for a single parcel, measured in grams.
     *
     * @param int $maxWeightPlaceSender
     * @return Warehouse
     */
    public function setMaxWeightPlaceSender($maxWeightPlaceSender)
    {
        return $this->setData(self::MAX_WEIGHT_PLACE_SENDER, $maxWeightPlaceSender);
    }

    /**
     * Gets the sender's maximum length for a single parcel that can be dispatched from this warehouse.
     *
     * @return int
     */
    public function getMaxLengthPlaceSender()
    {
        return $this->getData(self::MAX_LENGTH_PLACE_SENDER);
    }

    /**
     * Sets the sender's maximum length for a single parcel, measured in millimeters.
     *
     * @param int $maxLengthPlaceSender
     * @return Warehouse
     */
    public function setMaxLengthPlaceSender($maxLengthPlaceSender)
    {
        return $this->setData(self::MAX_LENGTH_PLACE_SENDER, $maxLengthPlaceSender);
    }

    /**
     * Gets the sender's maximum width for a single parcel that can be dispatched from this warehouse.
     *
     * @return int
     */
    public function getMaxWidthPlaceSender()
    {
        return $this->getData(self::MAX_WIDTH_PLACE_SENDER);
    }

    /**
     * Sets the sender's maximum width for a single parcel, measured in millimeters.
     *
     * @param int $maxWidthPlaceSender
     * @return Warehouse
     */
    public function setMaxWidthPlaceSender($maxWidthPlaceSender)
    {
        return $this->setData(self::MAX_WIDTH_PLACE_SENDER, $maxWidthPlaceSender);
    }

    /**
     * Gets the sender's maximum height for a single parcel that can be dispatched from this warehouse.
     *
     * @return int
     */
    public function getMaxHeightPlaceSender()
    {
        return $this->getData(self::MAX_HEIGHT_PLACE_SENDER);
    }

    /**
     * Sets the sender's maximum height for a single parcel, measured in millimeters.
     *
     * @param int $maxHeightPlaceSender
     * @return Warehouse
     */
    public function setMaxHeightPlaceSender($maxHeightPlaceSender)
    {
        return $this->setData(self::MAX_HEIGHT_PLACE_SENDER, $maxHeightPlaceSender);
    }

    /**
     * Gets the recipient's maximum weight for a single parcel that can be received to this warehouse.
     *
     * @return int
     */
    public function getMaxWeightPlaceRecipient()
    {
        return $this->getData(self::MAX_WEIGHT_PLACE_RECIPIENT);
    }

    /**
     * Sets the recipient's maximum weight for a single parcel, measured in grams.
     *
     * @param int $maxWeightPlaceRecipient
     * @return Warehouse
     */
    public function setMaxWeightPlaceRecipient($maxWeightPlaceRecipient)
    {
        return $this->setData(self::MAX_WEIGHT_PLACE_RECIPIENT, $maxWeightPlaceRecipient);
    }

    /**
     * Gets the recipient's maximum length for a single parcel that can be received to this warehouse.
     *
     * @return int
     */
    public function getMaxLengthPlaceRecipient()
    {
        return $this->getData(self::MAX_LENGTH_PLACE_RECIPIENT);
    }

    /**
     * Sets the recipient's maximum length for a single parcel, measured in millimeters.
     *
     * @param int $maxLengthPlaceRecipient
     * @return Warehouse
     */
    public function setMaxLengthPlaceRecipient($maxLengthPlaceRecipient)
    {
        return $this->setData(self::MAX_LENGTH_PLACE_RECIPIENT);
    }

    /**
     * Gets the recipient's maximum width for a single parcel that can be received to this warehouse.
     *
     * @return int
     */
    public function getMaxWidthPlaceRecipient()
    {
        return $this->getData(self::MAX_WIDTH_PLACE_RECIPIENT);
    }

    /**
     * Sets the recipient's maximum width for a single parcel, measured in millimeters.
     *
     * @param int $maxWidthPlaceRecipient
     * @return Warehouse
     */
    public function setMaxWidthPlaceRecipient($maxWidthPlaceRecipient)
    {
        return $this->setData(self::MAX_WIDTH_PLACE_RECIPIENT, $maxWidthPlaceRecipient);
    }

    /**
     * Gets the recipient's maximum height for a single parcel that can be received to this warehouse.
     *
     * @return int
     */
    public function getMaxHeightPlaceRecipient()
    {
        return $this->getData(self::MAX_HEIGHT_PLACE_RECIPIENT);
    }

    /**
     * Sets the recipient's maximum height for a single parcel, measured in millimeters.
     *
     * @param int $maxHeightPlaceRecipient
     * @return Warehouse
     */
    public function setMaxHeightPlaceRecipient($maxHeightPlaceRecipient)
    {
        return $this->setData(self::MAX_HEIGHT_PLACE_RECIPIENT, $maxHeightPlaceRecipient);
    }

    /**
     * Gets the max cost for one shipment to this warehouse.
     *
     * @return int
     */
    public function getMaxCostPlace()
    {
        return $this->getData(self::MAX_COST_PLACE);
    }

    /**
     * Sets the max cost for one shipment to this warehouse.
     *
     * @param int $maxCostPlace
     * @return Warehouse
     */
    public function setMaxCostPlace($maxCostPlace)
    {
        return $this->setData(self::MAX_COST_PLACE, $maxCostPlace);
    }

    /**
     * Gets the max declared cost for one shipment to this warehouse.
     *
     * @return int
     */
    public function getMaxDeclaredCostPlace()
    {
        return $this->getData(self::MAX_DECLARED_COST_PLACE);
    }

    /**
     * Sets the max declared cost for one shipment to this warehouse.
     *
     * @param int $maxDeclaredCostPlace
     * @return Warehouse
     */
    public function setMaxDeclaredCostPlace($maxDeclaredCostPlace)
    {
        return $this->setData(self::MAX_DECLARED_COST_PLACE, $maxDeclaredCostPlace);
    }

    /**
     * Gets the working schedule of the warehouse.
     *
     * @return string[]
     */
    public function getWorkSchedule()
    {
        return $this->getData(self::WORK_SCHEDULE);
    }

    /**
     * Sets the working schedule of the warehouse. Returns an array with working days and times.
     *
     * @param string[] $workSchedule
     * @return Warehouse
     */
    public function setWorkSchedule($workSchedule)
    {
        return $this->setData(self::WORK_SCHEDULE, $workSchedule);
    }
}
