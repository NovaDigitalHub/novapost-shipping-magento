<?php
namespace Novapost\Shipping\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Settlement search criteria results Interface
 * @api
 * @since 1.0.0
 */
interface SettlementSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get settlement list.
     *
     * @return \Novapost\Shipping\Api\Data\SettlementInterface[]
     */
    public function getItems();

    /**
     * Set settlement list.
     *
     * @param \Novapost\Shipping\Api\Data\SettlementInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
