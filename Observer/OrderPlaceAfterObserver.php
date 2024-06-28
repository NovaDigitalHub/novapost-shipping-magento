<?php

namespace Novapost\Shipping\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Novapost\Shipping\Helper\Data as ConfigHelper;
use Novapost\Shipping\Model\Service\Transfer;
use Novapost\Shipping\Logger\Logger;

class OrderPlaceAfterObserver implements ObserverInterface
{
    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * @var Transfer
     */
    private $transfer;

    /**
     * @var RateRequest
     */
    private $rateRequest;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * OrderPlaceAfterObserver constructor.
     *
     * @param ConfigHelper $configHelper
     * @param Transfer $transfer
     * @param RateRequest $rateRequest
     * @param Logger $logger
     */
    public function __construct(
        ConfigHelper $configHelper,
        Transfer $transfer,
        RateRequest $rateRequest,
        Logger $logger
    ) {
        $this->configHelper = $configHelper;
        $this->transfer = $transfer;
        $this->rateRequest = $rateRequest;
        $this->logger = $logger;
    }

    /**
     * Executes operations related to Nova Post shipping upon order placement.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            $quote = $observer->getEvent()->getQuote();

            $shippingMethod = $order->getShippingMethod();
            $isNovaPostShippingMethod = $this->configHelper->isNovapostShippingMethod($shippingMethod);
            if (!$order || !$order->getId() || $order->getIsVirtual() || !$isNovaPostShippingMethod) {
                $this->logger->debug('order error');
                return;
            }

            $auth = $this->transfer->auth();
            if ($auth->getData('errors')) {
                $this->logger->debug('Nova post auth error');
                return;
            }

            $jwt = $auth->getData('jwt');
            $ratesBody = $this->configHelper->getExchangeRequestBody($quote->getShippingAddress()->getCountryId());
            $rates = $this->transfer->getExchangeRates($jwt, $ratesBody);
            $params = $this->configHelper->getDocumentsRequestBody($this->rateRequest, $quote, $rates, $shippingMethod, $order->getId());
            $documentData = $this->transfer->setShipmentDocument($jwt, $params);
            if ($documentData->getData() && $documentData->getData('responseCode') == 201) {
                $number = $documentData->getData('number');
                $order->setData('novapost_number', $number);
                $order->save();
            }

        } catch (\Exception $e) {
            $this->logger->debug('Error while sending order data: ' . $e->getMessage());
        }
    }
}
