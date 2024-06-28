<?php

namespace Novapost\Shipping\Model\Source;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Novapost\Shipping\Model\Repository\Warehouse as WarehouseRepository;
use Novapost\Shipping\Model\ResourceModel\Warehouse\CollectionFactory as WarehouseCollectionFactory;
use Novapost\Shipping\Helper\Data as ConfigHelper;

class Warehouse implements \Magento\Shipping\Model\Carrier\Source\GenericInterface
{
    /**
     * @var WarehouseCollectionFactory
     */
    private $warehouseCollectionFactory;

    /**
     * @var WarehouseRepository
     */
    private $warehouseRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var configHelper
     */
    private $configHelper;

    /**
     * Warehouse constructor.
     *
     * @param WarehouseCollectionFactory $cityCollectionFactory
     * @param WarehouseRepository $settlementRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param configHelper $configHelper
     */
    public function __construct(
        WarehouseCollectionFactory $cityCollectionFactory,
        WarehouseRepository $settlementRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        configHelper $configHelper
    ) {
        $this->warehouseCollectionFactory = $cityCollectionFactory;
        $this->warehouseRepository = $settlementRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->configHelper = $configHelper;
    }

    /**
     * Returns array to be used in select on back-end
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        $settlement = $this->configHelper->getSettlement();

        if ($settlement) {
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('settlement_id', $settlement)->create();

            $warehouses = $this->warehouseRepository->getList($searchCriteria);

            foreach ($warehouses->getItems() as $warehouse) {
                $isActive = $warehouse->getExternalId() == $this->configHelper->getDivision();
                $options[] = [
                    'value' => $warehouse->getDivisionId(),
                    'label' => $warehouse->getName(),
                    'active' => $isActive
                ];
            }
        }

        return $options;
    }

}
