<?php

namespace Novapost\Shipping\Model\Source;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Novapost\Shipping\Model\Repository\Settlement as SettlementRepository;
use Novapost\Shipping\Model\ResourceModel\Settlement\CollectionFactory as SettlementCollectionFactory;
use Novapost\Shipping\Helper\Data as ConfigHelper;

class Settlement implements \Magento\Shipping\Model\Carrier\Source\GenericInterface
{
    /**
     * @var SettlementCollectionFactory
     */
    private $settlementCollectionFactory;

    /**
     * @var SettlementRepository
     */
    private $settlementRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var configHelper
     */
    private $configHelper;

    /**
     * Settlement constructor.
     *
     * @param SettlementCollectionFactory $cityCollectionFactory
     * @param SettlementRepository $settlementRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param configHelper $configHelper
     */
    public function __construct(
        SettlementCollectionFactory $cityCollectionFactory,
        SettlementRepository $settlementRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        configHelper $configHelper
    ) {
        $this->settlementCollectionFactory = $cityCollectionFactory;
        $this->settlementRepository = $settlementRepository;
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

        $countryCode = $this->configHelper->getCountry();
        if ($countryCode) {
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('country_code', $countryCode)->create();
            $settlements = $this->settlementRepository->getList($searchCriteria);
            foreach ($settlements->getItems() as $settlement) {
                $isActive = $settlement->getExternalId() == $this->configHelper->getSettlement();
                $options[] = [
                    'label' => $settlement->getName(),
                    'value' => $settlement->getExternalId(),
                    'active' => $isActive
                ];
            }

        }

        return $options;
    }

}
