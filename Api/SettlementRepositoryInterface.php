<?php

namespace Novapost\Shipping\Api;

use Novapost\Shipping\Api\Data\SettlementInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Settlement CRUD interface.
 *
 * @api
 * @since 1.0.0
 */
interface SettlementRepositoryInterface
{
    const NP_ID                                 = 'id';
    const NP_EXTERNAL_ID                        = 'external_id';
    const NP_NAME                               = 'name';
    const NP_COUNTRY_CODE                       = 'countryCode';

    const NP_SETTLEMENT_NAME                    = 'settlement';
    const NP_REGION_NAME                        = 'region';

    /**
     * Save Settlement.
     *
     * @param SettlementInterface $settlement
     * @return $this
     */
    public function save(SettlementInterface $settlement);

    /**
     * List items that are assigned the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return $this
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * List items that are assigned to a specified country.
     *
     * @param string $countryCode
     * @return string
     */
    public function getJsListByCountry(string $countryCode = '');

    /**
     * Get the specified item.
     *
     * @param string $item
     * @return string
     */
    public function getItem(string $item = '');

    /**
     * Get a settlement by externalId.
     *
     * @param string $externalId
     * @return \Novapost\shipping\Api\Data\SettlementInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByExternalId(string $externalId);
}
