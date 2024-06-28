<?php

namespace Novapost\Shipping\Model\Carrier;

use Magento\Framework\DataObject;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Psr\Log\LoggerInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Checkout\Model\Session;
use Magento\Store\Model\StoreManagerInterface;
use Novapost\Shipping\Model\ResourceModel\Settlement as SettlementResourceModel;
use Novapost\Shipping\Model\ResourceModel\Warehouse as WarehouseResourceModel;
use Novapost\Shipping\Model\Service\Transfer;
use Novapost\Shipping\Helper\Data as ConfigHelper;

class NovapostWarehouse extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'novapost';

    /**
     * @var string
     */
    protected $_title = '';

    /**
     * @var bool
     */
    protected $_isFixed = false;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ResultFactory
     */
    private $rateResultFactory;

    /**
     * @var MethodFactory
     */
    private $rateMethodFactory;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var Transfer
     */
    private $transfer;

    /**
     * @var SettlementResourceModel
     */
    private $settlementResourceModel;

    /**
     * @var WarehouseResourceModel
     */
    private $warehouseResourceModel;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * NovapostWarehouse constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param Session $checkoutSession
     * @param StoreManagerInterface $storeManager
     * @param Transfer $transfer
     * @param SettlementResourceModel $settlementResourceModel
     * @param WarehouseResourceModel $warehouseResourceModel
     * @param ConfigHelper $configHelper
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        Transfer $transfer,
        SettlementResourceModel $settlementResourceModel,
        WarehouseResourceModel $warehouseResourceModel,
        ConfigHelper $configHelper,
        array $data = []
    ) {
        $this->rateMethodFactory = $rateMethodFactory;
        $this->rateResultFactory = $rateResultFactory;
        $this->checkoutSession = $checkoutSession;
        $this->storeManager = $storeManager;
        $this->transfer = $transfer;
        $this->settlementResourceModel = $settlementResourceModel;
        $this->warehouseResourceModel = $warehouseResourceModel;
        $this->configHelper = $configHelper;

        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * Collect and get rates.
     *
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool|null
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->isActive()) {
            return false;
        }

        $auth = $this->transfer->auth();
        if ($auth->getData('errors')) {
            return false;
        }

        $result = $this->rateResultFactory->create();
        $this->_title = $this->getConfigData('title');

        $methods = $this->getAllowedMethods();

        foreach ($methods as $method) {
            $title = $this->getConfigData($method . '_title');
            $price = $this->getConfigData($method . '_price');

            $jwt = $auth->getData('jwt');

            $ratesBody = $this->configHelper->getExchangeRequestBody();
            $rates = $this->transfer->getExchangeRates($jwt, $ratesBody);
            $params = $this->configHelper->getRequestBody($request, $this->checkoutSession, $rates, $method);
            $deliveryData = $this->transfer->getDeliveryCost($jwt, $params);
            $currentRate = $this->configHelper->getCurrentRate($rates);
            if ($deliveryData->getData() && $deliveryData->getData('responseCode') == 200) {
                $deliveryPrice = 0;
                foreach ($deliveryData->getData('services') as $service) {
                    $deliveryPrice += $service['cost'] ? $service['cost'] / $currentRate : 0;
                }

                $price = $deliveryPrice;
            }

            $appendData = $this->setMethod($method, $title, $price);
            $result->append($appendData);
        }

        return $result;
    }

    /**
     * Gets allowed methods.
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return explode(',', $this->getConfigData('allowed_methods'));
    }

    /**
     * Sets method.
     *
     * @param string $name
     * @param string $title
     * @param int|float $price
     * @return mixed
     */
    private function setMethod($name, $title, $price)
    {
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $methodWarehouse */
        $method = $this->rateMethodFactory->create();
        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->_title);
        $method->setMethod($name);
        $method->setMethodTitle($title);
        $method->setPrice($price);

        return $method;
    }
}
