<?php

namespace Novapost\Shipping\Block\Checkout;

class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{

    /**
     * @var \Novapost\Shipping\Api\SettlementRepositoryInterface
     */
    private $settlementRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * LayoutProcessor constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Novapost\Shipping\Api\SettlementRepositoryInterface $settlementRepository
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Novapost\Shipping\Api\SettlementRepositoryInterface $settlementRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->settlementRepository = $settlementRepository;
        $this->checkoutSession = $checkoutSession;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function process($jsLayout)
    {
        $settlements = [];

        if ($this->checkoutSession->getQuote()->getShippingAddress()->getNpSettlementExternalId()) {
            $externalId = $this->checkoutSession->getQuote()->getShippingAddress()->getNpSettlementExternalId();
            $settlement = $this->settlementRepository->getByExternalId($externalId);

            if ($settlement->getData('external_id')) {
                $settlements[] = [
                    'value' => $settlement->getData('external_id'),
                    'label' => $settlement->getData('name'),
                ];
            }
        } else {
            $settlements[] = [
                'value' => '',
                'label' => '- ' . __(''),
            ];
        }

        if (!isset($jsLayout['components']['checkoutProvider']['dictionaries']['city'])) {
            $jsLayout['components']['checkoutProvider']['dictionaries']['city'] = $settlements;
        }

        return $jsLayout;
    }
}
