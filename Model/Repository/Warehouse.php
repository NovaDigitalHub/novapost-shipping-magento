<?php

namespace Novapost\Shipping\Model\Repository;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\App\RequestInterface;
use Novapost\Shipping\Api\Data\WarehouseInterface;
use Novapost\Shipping\Api\WarehouseRepositoryInterface;
use Novapost\Shipping\Api\Data\WarehouseSearchResultsInterfaceFactory;
use Novapost\Shipping\Model\ResourceModel\Warehouse as WarehouseResource;
use Novapost\Shipping\Model\WarehouseFactory;
use Novapost\Shipping\Model\ResourceModel\Warehouse\CollectionFactory as WarehouseCollectionFactory;
use Magento\Checkout\Model\Session;

class Warehouse implements WarehouseRepositoryInterface
{
    /**
     * @var WarehouseResource
     */
    private $warehouseResource;

    /**
     * @var WarehouseFactory
     */
    private $warehouseFactory;

    /**
     * @var WarehouseCollectionFactory
     */
    private $warehouseCollectionFactory;

    /**
     * @var WarehouseSearchResultsInterfaceFactory
     */
    private $warehouseSearchResultFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * Warehouse constructor.
     *
     * @param WarehouseResource $warehouseResource
     * @param WarehouseFactory $warehouseFactory
     * @param WarehouseCollectionFactory $warehouseCollectionFactory
     * @param WarehouseSearchResultsInterfaceFactory $warehouseSearchResultsFactory
     * @param Session $checkoutSession
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ScopeConfigInterface $scopeConfig
     * @param RequestInterface $request
     */
    public function __construct(
        WarehouseResource $warehouseResource,
        WarehouseFactory $warehouseFactory,
        WarehouseCollectionFactory $warehouseCollectionFactory,
        WarehouseSearchResultsInterfaceFactory $warehouseSearchResultsFactory,
        Session $checkoutSession,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionProcessorInterface $collectionProcessor,
        ScopeConfigInterface $scopeConfig,
        RequestInterface $request
    ) {
        $this->warehouseResource = $warehouseResource;
        $this->warehouseFactory = $warehouseFactory;
        $this->warehouseCollectionFactory = $warehouseCollectionFactory;
        $this->warehouseSearchResultFactory = $warehouseSearchResultsFactory;
        $this->checkoutSession = $checkoutSession;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
    }

    /**
     * Save Warehouse.
     *
     * @param WarehouseInterface $warehouse
     * @return WarehouseResource
     * @throws CouldNotSaveException
     */
    public function save(WarehouseInterface $warehouse)
    {
        try {
            return $this->warehouseResource->save($warehouse);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save Source'), $exception);
        }
    }

    /**
     * List items that are assigned the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Novapost\Shipping\Api\Data\WarehouseSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->warehouseCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResult = $this->warehouseSearchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * List items that are assigned to a specified settlement.
     *
     * @param int $settlementId
     * @param bool $source
     * @return false|string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getJsListBySettlementId($settlementId = 0, $source = false)
    {
        $collection = $this->warehouseCollectionFactory->create();
        $collection->addFieldToFilter('settlement_id', ['eq' => $settlementId]);

        if ($source) {
            $collection->addFieldToFilter('source', ['eq' => 'NPAX']);
        }

        if ($this->checkoutSession->getQuote()->getItems()) {
            $maxWeight = 0;
            foreach ($this->checkoutSession->getQuote()->getItems() as $item) {
                if ($item->getWeight() > $maxWeight) {
                    $maxWeight = (int)$item->getWeight() * 1000;
                }
            }

            if ($maxWeight) {
                $collection->addFieldToFilter('max_weight_place_sender', ['gt' => $maxWeight]);
            }
        }

        $data[] = [
            'id' => 0,
            'postcode' => '',
            'text' => __('Choose warehouse')
        ];

        if ($collection && $collection->getSize()) {
            foreach ($collection->getItems() as $item) {
                $data[] = [
                    'id' => $item->getDivisionId(),
                    'postcode' => $item->getPostcode(),
                    'text' => $item->getName()
                ];
            }
        }

        return json_encode($data);
    }

    /**
     * Gets data from the established variable.
     *
     * @param string $value
     * @param mixed $field
     * @return \Novapost\Shipping\Model\Warehouse
     */
    public function getByField($value, $field)
    {
        $object = $this->warehouseFactory->create();
        $this->warehouseResource->load($object, $value, $field);
        return $object;
    }
}
