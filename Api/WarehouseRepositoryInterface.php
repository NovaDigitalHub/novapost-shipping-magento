<?php

namespace Novapost\Shipping\Api;

use Novapost\Shipping\Api\Data\WarehouseInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Novapost\Shipping\Api\Data\WarehouseSearchResultsInterface;

/**
 * Warehouse Interface
 * @api
 * @since 1.0.0
 */
interface WarehouseRepositoryInterface
{

    const NP_ID                                        = 'id';
    const NP_NAME                                      = 'name';
    const NP_SHORTNAME                                 = 'shortName';
    const NP_SOURCE                                    = 'source';
    const NP_EXTERNAL_ID                               = 'externalId';
    const NP_COUNTRY_CODE                              = 'countryCode';
    const NP_ADDRESS                                   = 'address';
    const NP_NUMBER                                    = 'number';
    const NP_STATUS                                    = 'status';
    const NP_CUSTOMER_SERVICE_AVAILABLE                = 'customerServiceAvailable';
    const NP_DIVISION_CATEGORY                         = 'divisionCategory';
    const NP_SETTLEMENT_REF                            = 'SettlementRef';
    const NP_PUBLIC_PHONES                             = 'publicPhones';
    const NP_INTERNAL_PHONES                           = 'internalPhones';
    const NP_RESPONSIBLE_PERSON                        = 'responsiblePerson';
    const NP_PARTNER                                   = 'partner';
    const NP_OWNER_DIVISION                            = 'ownerDivision';
    const NP_LONGITUDE                                 = 'longitude';
    const NP_LATITUDE                                  = 'latitude';
    const NP_DISTANCE                                  = 'distance';
    const NP_MAX_WEIGHT_PLACE_SENDER                   = 'maxWeightPlaceSender';
    const NP_MAX_LENGTH_PLACE_SENDER                   = 'maxLengthPlaceSender';
    const NP_MAX_WIDTH_PLACE_SENDER                    = 'maxWidthPlaceSender';
    const NP_MAX_HEIGHT_PLACE_SENDER                   = 'maxHeightPlaceSender';
    const NP_MAX_WEIGHT_PLACE_RECIPIENT                = 'maxWeightPlaceRecipient';
    const NP_MAX_LENGTH_PLACE_RECIPIENT                = 'maxLengthPlaceRecipient';
    const NP_MAX_WIDTH_PLACE_RECIPIENT                 = 'maxWidthPlaceRecipient';
    const NP_MAX_HEIGHT_PLACE_RECIPIENT                = 'maxHeightPlaceRecipient';
    const NP_MAX_COST_PLACE                            = 'maxCostPlace';
    const NP_MAX_DECLARED_COST_PLACE                   = 'maxDeclaredCostPlace';
    const NP_WORK_SCHEDULE                             = 'workSchedule';
    const NP_FULL_ADDRESS                              = 'fullAddress';
    const NP_ZIPCODE                                   = 'zipcode';

    const NP_ENTITY_FIELD                              = 'external_id';

    const NP_SETTLEMENT_NAME                           = 'settlement';
    const NP_REGION_NAME                               = 'region';
    const NP_ARIA_NAME                                 = 'parent';

    /**
     * Save Warehouse.
     *
     * @param WarehouseInterface $warehouse
     * @return $this
     */
    public function save(WarehouseInterface $warehouse);

    /**
     * List items that are assigned the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return WarehouseSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * List items that are assigned to a specified settlement.
     *
     * @param int $settlementId
     * @param bool $source
     * @return false|string
     */
    public function getJsListBySettlementId(int $settlementId = 0, bool $source = false);
}
