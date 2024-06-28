<?php

namespace Novapost\Shipping\Model\Repository;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Novapost\Shipping\Api\Data\SettlementInterface;
use Novapost\Shipping\Api\SettlementRepositoryInterface;
use Novapost\Shipping\Api\Data\SettlementSearchResultsInterfaceFactory;
use Novapost\Shipping\Model\ResourceModel\Settlement as SettlementResource;
use Novapost\Shipping\Model\SettlementFactory;
use Novapost\Shipping\Model\ResourceModel\Settlement\CollectionFactory as SettlementCollectionFactory;

class Settlement implements SettlementRepositoryInterface
{

    /**
     * @var SettlementResource
     */
    private $settlementResource;

    /**
     * @var SettlementFactory
     */
    private $settlementFactory;

    /**
     * @var SettlementCollectionFactory
     */
    private $settlementCollectionFactory;

    /**
     * @var SettlementSearchResultsInterfaceFactory
     */
    private $settlementSearchResultFactory;

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
     * Settlement constructor.
     * @param SettlementResource $settlementResource
     * @param SettlementFactory $settlementFactory
     * @param SettlementCollectionFactory $settlementCollectionFactory
     * @param SettlementSearchResultsInterfaceFactory $settlementSearchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        SettlementResource $settlementResource,
        SettlementFactory $settlementFactory,
        SettlementCollectionFactory $settlementCollectionFactory,
        SettlementSearchResultsInterfaceFactory $settlementSearchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionProcessorInterface $collectionProcessor,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->settlementResource = $settlementResource;
        $this->settlementFactory = $settlementFactory;
        $this->settlementCollectionFactory = $settlementCollectionFactory;
        $this->settlementSearchResultFactory = $settlementSearchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Save settlement.
     *
     * @param SettlementInterface $settlement
     * @return mixed|void
     * @throws CouldNotSaveException
     */
    public function save(SettlementInterface $settlement)
    {
        try {
            $this->settlementResource->save($settlement);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save settlement'), $exception);
        }
    }

    /**
     * List items that are assigned the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Novapost\Shipping\Api\Data\SettlementSearchResultsInterface|Settlement
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->settlementCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResult = $this->settlementSearchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * List items that are assigned to a specified country.
     *
     * @param string $countryCode
     * @return string
     */
    public function getJsListByCountry(string $countryCode = '') : string
    {
        $collection = $this->settlementCollectionFactory->create();

        if ($countryCode) {
            $collection->addFieldToFilter('country_code', $countryCode);
        }

        $data[] = ['id' => 0, 'text' => __('Choose city')];

        if ($collection && $collection->getSize()) {
            foreach ($collection->getItems() as $item) {
                $data[] = [
                    'id' => $item->getExternalId(),
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
     * @return \Novapost\Shipping\Model\Settlement
     */
    public function getByField($value, $field)
    {
        $object = $this->settlementFactory->create();
        $this->settlementResource->load($object, $value, $field);
        return $object;
    }

    /**
     * Gets externalID.
     *
     * @param string $externalId
     * @return SettlementInterface
     */
    public function getByExternalId(string $externalId)
    {
        $settlement = $this->settlementFactory->create();
        $this->settlementResource->load($settlement, $externalId, 'external_id');

        return $settlement;
    }

    /**
     * Gets settlement data that are assigned to a specified name.
     *
     * @param string $item
     * @return false|mixed|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getItem($item = '')
    {
        $result = [];
        if ($item) {
            $select = $this->settlementResource->getConnection()
                ->select()
                ->from(
                    $this->settlementResource->getMainTable(),
                    ['external_id', 'name']
                )
                ->where('name = ?', $item);
            $result = $this->settlementResource->getConnection()->fetchCol($select);
        }

        return json_encode($result);
    }
}
