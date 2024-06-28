<?php
namespace Novapost\Shipping\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Warehouse search criteria results Interface
 * @api
 * @since 1.0.0
 */
interface WarehouseSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get warehouse list.
     *
     * @return \Novapost\Shipping\Api\Data\WarehouseInterface[]
     */
    public function getItems();

    /**
     * Set warehouse list.
     *
     * @param \Novapost\Shipping\Api\Data\WarehouseInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
