<?php

namespace Novapost\Shipping\Helper;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Novapost\Shipping\Model\ResourceModel\Settlement as SettlementResourceModel;
use Novapost\Shipping\Model\ResourceModel\Warehouse as WarehouseResourceModel;

class Data extends AbstractHelper
{
    const XML_CONFIG_API_URL        = 'carriers/novapost/api_url';
    const XML_CONFIG_API_KEY        = 'carriers/novapost/api_key';
    const XML_CONFIG_COUNTRY        = 'carriers/novapost/country_code';
    const XML_CONFIG_SETTLEMENT     = 'carriers/novapost/settlement';
    const XML_CONFIG_DIVISION_ID    = 'carriers/novapost/division_id';
    const XML_CONFIG_WIDTH          = 'carriers/novapost/width';
    const XML_CONFIG_DEPTH          = 'carriers/novapost/depth';
    const XML_CONFIG_HEIGHT         = 'carriers/novapost/height';
    const XML_CONFIG_PAYER_CONTACT  = 'carriers/novapost/payer_contact_number';
    const XML_CONFIG_COMPANY_TIN    = 'carriers/novapost/company_tin';
    const XML_CONFIG_COMPANY_NAME   = 'carriers/novapost/company_name';
    const XML_CONFIG_PHONE          = 'carriers/novapost/phone';
    const XML_CONFIG_EMAIL          = 'carriers/novapost/email';
    const XML_CONFIG_NAME           = 'carriers/novapost/name';
    const XML_CONFIG_SENDER_TYPE    = 'carriers/novapost/sender_type';
    const XML_CONFIG_CITY           = 'carriers/novapost/city';
    const XML_CONFIG_REGION         = 'carriers/novapost/region';
    const XML_CONFIG_STREET         = 'carriers/novapost/street';
    const XML_CONFIG_POSTCODE       = 'carriers/novapost/postCode';
    const XML_CONFIG_BUILDING       = 'carriers/novapost/building';
    const XML_CONFIG_FLAT           = 'carriers/novapost/flat';
    const XML_CONFIG_BLOCK          = 'carriers/novapost/block';
    const XML_CONFIG_NOTE           = 'carriers/novapost/note';
    const XML_CONFIG_MEASUREMENT    = 'carriers/novapost/measurement';
    const XML_CONFIG_PARCEL_DESC    = 'carriers/novapost/parcel_description';

    const NP_SERVER_TYPE_PROD       = 'https://api.novapost.com/v.1.0/';
    const NP_SERVER_TYPE_SAND       = 'https://api-stage.novapost.pl/v.1.0/';

    const CODE_WAREHOUSE            = 'novapost_shipping_warehouse';
    const FULL_CODE_WAREHOUSE       = 'novapost_novapost_shipping_warehouse';
    const CODE_COURIER              = 'novapost_shipping_courier';
    const FULL_CODE_COURIER         = 'novapost_novapost_shipping_courier';
    const DEFAULT_STATUS            = 'ReadyToShip';
    const DEFAULT_PAYER             = 'Sender';
    const DEFAULT_INCOTERM          = 'DAP';
    const SENDER_TYPE_OFFICE        = '2';
    const SENDER_TYPE_ADDRESS       = '1';
    const MEASUREMENT_PIECES        = 'pieces';
    const MEASUREMENT_KILOGRAMS     = 'kilograms';
    const MEASUREMENT_METERS        = 'meters';
    const DEFAULT_CARGO_CATEGORY    = 'parcel';
    const DEFAULT_PARCEL_DESCRIPTION = 'Clothers';
    const DEFAULT_DOCUMENT_TYPE     = 'marking';
    const DEFAULT_DOCUMENT_SIZE     = 'size_A4';

    /**
     * @var array
     */
    private $rates = [];

    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var SettlementResourceModel
     */
    private $settlementResourceModel;

    /**
     * @var WarehouseResourceModel
     */
    private $warehouseResourceModel;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $_storeManager
     * @param TimezoneInterface $timezone
     * @param SettlementResourceModel $settlementResourceModel
     * @param WarehouseResourceModel $warehouseResourceModel
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $_storeManager,
        TimezoneInterface $timezone,
        SettlementResourceModel $settlementResourceModel,
        WarehouseResourceModel $warehouseResourceModel
    ) {
        $this->_storeManager = $_storeManager;
        $this->timezone = $timezone;
        $this->settlementResourceModel = $settlementResourceModel;
        $this->warehouseResourceModel = $warehouseResourceModel;
        parent::__construct($context);
    }

    /**
     * Gets system configuration by field path.
     *
     * @param mixed $field
     * @param int|null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        $storeId = $storeId ? $storeId : $this->getSiteStoreId();
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Gets current store id.
     *
     * @return int|null
     */
    public function getSiteStoreId()
    {
        try {
            return $this->_storeManager->getStore()->getId();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Gets api URL.
     *
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->getConfigValue(self::XML_CONFIG_API_URL);
    }

    /**
     * Gets api KEY.
     *
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->getConfigValue(self::XML_CONFIG_API_KEY);
    }

    /**
     * Gets sender country.
     *
     * @return mixed
     */
    public function getCountry()
    {
        return $this->getConfigValue(self::XML_CONFIG_COUNTRY);
    }

    /**
     * Gets sender settlement.
     *
     * @return mixed
     */
    public function getSettlement()
    {
        return $this->getConfigValue(self::XML_CONFIG_SETTLEMENT);
    }

    /**
     * Gets sender division.
     *
     * @return mixed
     */
    public function getDivision()
    {
        return $this->getConfigValue(self::XML_CONFIG_DIVISION_ID);
    }

    /**
     * Gets request body for calculation.
     *
     * @param RateRequest $rateRequest
     * @param Session $checkoutSession
     * @param DataObject $rates
     * @param string $method
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRequestBody(RateRequest $rateRequest, Session $checkoutSession, $rates, $method)
    {
        $this->setRates($rates);
        $quote = $checkoutSession->getQuote();
        $request = new DataObject();
        $request->addData(
            [
                'status' => self::DEFAULT_STATUS,
                'payerType' => self::DEFAULT_PAYER,
                'invoice' => $this->getInvoice($quote),
                'parcels' => $this->getParcels($quote),
                'sender' => $this->getSender(),
                'recipient' => $this->getRecipient($rateRequest, $quote, $method)
            ]
        );

        return $request->getData();
    }

    /**
     * Gets request body for documents.
     *
     * @param RateRequest $rateRequest
     * @param Quote $quote
     * @param DataObject $rates
     * @param string $method
     * @param int $orderId
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDocumentsRequestBody(RateRequest $rateRequest, Quote $quote, $rates, $method, $orderId)
    {
        $this->setRates($rates);
        $request = new DataObject();
        $request->addData(
            [
                'status' => self::DEFAULT_STATUS,
                'clientOrder' => $orderId,
             //   'payerContractNumber' => $this->getConfigValue(self::XML_CONFIG_PAYER_CONTACT),
                'note' => '',
                'payerType' => self::DEFAULT_PAYER,
                'invoice' => $this->getInvoice($quote),
                'parcels' => $this->getParcels($quote),
                'sender' => $this->getSender(),
                'recipient' => $this->getRecipient($rateRequest, $quote, $method)
            ]
        );

        return $request->getData();
    }

    /**
     * Gets request body for exchange rates.
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getExchangeRequestBody()
    {
        $currentDate = $this->timezone->date();
        $request = new DataObject();
        $request->addData(
            [
                'amount' => 1,
                'countryCode' => $this->getConfigValue(self::XML_CONFIG_COUNTRY),
                'currencyCode' => $this->_storeManager->getStore()->getCurrentCurrency()->getCode(),
                'date' => $currentDate->format('Y-m-d\T00:00:00.000000\Z')
            ]
        );

        return $request->getData();
    }

    /**
     * Gets request body for print documents.
     *
     * @param string $numbers
     * @return mixed
     */
    public function getPrintDocumentsRequestBody($numbers)
    {
        $request = new DataObject();
        $request->addData(
            [
                'numbers[]' => $numbers,
                'type' => self::DEFAULT_DOCUMENT_TYPE,
                'printSizeType' => self::DEFAULT_DOCUMENT_SIZE,
                'copies' => 1
            ]
        );

        return $request->getData();
    }

    /**
     * Gets current rate
     *
     * @param $rates
     * @return float|int
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentRate($rates)
    {
        return $this->setRates($rates);
    }

    /**
     * Checks if the given shipping method is a Nova post shipping method.
     *
     * @param string $shippingMethod
     * @return bool
     */
    public function isNovapostShippingMethod($shippingMethod)
    {
        return strpos($shippingMethod, self::CODE_WAREHOUSE) !== false ||
            strpos($shippingMethod, self::CODE_COURIER) !== false;
    }

    /**
     * Gets invoice data.
     *
     * @param Quote $quote
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getInvoice(Quote $quote)
    {
        $invoice = new DataObject();

        $totalCostUsd = $this->getAmountByCurrencyCode(
            $quote->getSubtotalWithDiscount(),
            'USD'
        );
        $totalCostEur = $this->getAmountByCurrencyCode(
            $quote->getSubtotalWithDiscount(),
            'EUR'
        );

        $invoice->addData(
            [
                'incoterm' => self::DEFAULT_INCOTERM,
                'currencyCode' => $this->_storeManager->getStore()->getCurrentCurrency()->getCode(),
                'totalCost' => (float)$quote->getSubtotalWithDiscount(),
                'totalCostUsd' => $totalCostUsd,
                'totalCostEur' => $totalCostEur,
                'items' => $this->getItems($quote)
            ]
        );

        return $invoice->getData();
    }

    /**
     * Gets items from quote.
     *
     * @param Quote $quote
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getItems(Quote $quote)
    {
        $items = [];

        $quoteItems = $quote->getAllVisibleItems();
        foreach ($quoteItems as $item) {

            $costUsd = $this->getAmountByCurrencyCode($item->getPrice(), 'USD');
            $costEur = $this->getAmountByCurrencyCode($item->getPrice(), 'EUR');
            $measurementCode = $this->getConfigValue(self::XML_CONFIG_MEASUREMENT) ?: self::MEASUREMENT_PIECES;

            $data = new DataObject();
            $items[] = $data->addData(
                [
                    'id' => $item->getProduct()->getId(),
                    'hsCode' => '000000000',
                    'name' => $item->getName(),
                    'nameEng' => $item->getName(),
                    'amount' => $item->getQty(),
                    'cost' => $item->getPrice(),
                    'costUsd' => $costUsd,
                    'costEur' => $costEur,
                    'measurementCode' => $measurementCode
                ]
            )->getData();

        }

        return $items;
    }

    /**
     * Gets parcels.
     *
     * @param Quote $quote
     * @return array
     */
    private function getParcels(Quote $quote)
    {
        $parcels = [];

        $quoteItems = $quote->getAllVisibleItems();
        foreach ($quoteItems as $k => $item) {

            $actualWeight = (int)($item->getWeight() > 0 ? $item->getWeight() * 1000 : 2000);
            $parcelDescription = $this->getConfigValue(self::XML_CONFIG_PARCEL_DESC)
                ?: self::DEFAULT_PARCEL_DESCRIPTION;

            $data = new DataObject();
            $parcels[] = $data->addData(
                [
                    'cargoCategory' => self::DEFAULT_CARGO_CATEGORY,
                    'parcelDescription' => $parcelDescription,
                    'insuranceCost' => $item->getPrice(),
                    'rowNumber' => $k+1,
                    'width' => (int)$this->getConfigValue(self::XML_CONFIG_WIDTH),
                    'length' => (int)$this->getConfigValue(self::XML_CONFIG_DEPTH),
                    'height' => (int)$this->getConfigValue(self::XML_CONFIG_HEIGHT),
                    'actualWeight' => (int)$actualWeight,
                    'volumetricWeight' => $this->getVolumetricWeight()
                ]
            )->getData();

        }

        return $parcels;
    }

    /**
     * Gets Recipient.
     *
     * @param RateRequest $rateRequest
     * @param Quote $quote
     * @param string $method
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getRecipient(RateRequest $rateRequest, Quote $quote, $method)
    {
        $recipient = new DataObject();
        $recipient->addData(
            [
                'countryCode' => $quote->getShippingAddress()->getCountryId(),
                'email' => $quote->getShippingAddress()->getEmail(),
                'name' => $quote->getShippingAddress()->getName(),
                'phone' => $this->getFormattedTelephone($quote->getShippingAddress()->getTelephone())
            ]
        );

        switch ($method) {
            case self::CODE_WAREHOUSE:
            case self::FULL_CODE_WAREHOUSE:
                $settlementId = $quote->getShippingAddress()->getNpSettlementExternalId();
                if (!$settlementId) {
                    $cityName = '';
                    if ($rateRequest->getOrigCity()) {
                        $cityName = $rateRequest->getOrigCity();
                    } elseif ($rateRequest->getDestCity()) {
                        $cityName = $rateRequest->getDestCity();
                    }

                    $settlementId = $this->settlementResourceModel->getSettlementIdByName($cityName);
                }
                $recipient->setData('settlementId', $settlementId);

                $warehouseId = $quote->getShippingAddress()->getNpWarehouseExternalId();
                if (!$warehouseId) {
                    $streetName = '';
                    if ($rateRequest->getDestStreet()) {
                        $streetName = $rateRequest->getDestStreet();
                    }

                    $warehouseId =
                        $this->warehouseResourceModel->getWarehouseIdByNameAndSettlement($streetName, $settlementId);
                }
                $recipient->setData('divisionNumber', $warehouseId);
                $recipient->setData('divisionId', $warehouseId);
                break;
            case self::CODE_COURIER:
            case self::FULL_CODE_COURIER:
                $recipient->setData('addressParts', $this->getRecipientAddress($quote));
                break;
        }

        return $recipient->getData();
    }

    /**
     * Gets recipient address.
     *
     * @param Quote $quote
     * @return mixed
     */
    private function getRecipientAddress(Quote $quote)
    {
        $combinedStreet = $quote->getShippingAddress()->getStreet();

        $street = $quote->getShippingAddress()->getNpGeoAddress();
        if (!$street) {
            $street = $combinedStreet[0] ?? '';
        }

        $building = $quote->getShippingAddress()->getNpGeoAddressBuilding();
        if (!$building) {
            $building = $combinedStreet[1] ?? '';
        }

        $address = new DataObject();
        $address->addData(
            [
                'city' => $quote->getShippingAddress()->getCity(),
                'region' => $quote->getShippingAddress()->getRegion(),
                'street' => $street,
                'building' => $building,
                'postCode' => $quote->getShippingAddress()->getPostcode()
            ]
        );

        return $address->getData();
    }

    /**
     * Gets Sender.
     *
     * @return mixed
     */
    private function getSender()
    {
        $sender = new DataObject();

        $sender->addData(
            [
                'companyTin' => $this->getConfigValue(self::XML_CONFIG_COMPANY_TIN),
                'companyName' => $this->getConfigValue(self::XML_CONFIG_COMPANY_NAME),
                'countryCode' => $this->getConfigValue(self::XML_CONFIG_COUNTRY),
                'phone' => $this->getFormattedTelephone($this->getConfigValue(self::XML_CONFIG_PHONE)),
                'email' => $this->getConfigValue(self::XML_CONFIG_EMAIL),
                'name' => $this->getConfigValue(self::XML_CONFIG_NAME)
            ]
        );

        if ($this->getConfigValue(self::XML_CONFIG_SENDER_TYPE) == self::SENDER_TYPE_ADDRESS) {
            $sender->addData(
                [
                    'divisionId' => null,
                    'addressParts' => $this->getSenderAddress()
                ]
            );
        } else {
            $sender->addData(
                [
                    'divisionNumber' => $this->getConfigValue(self::XML_CONFIG_DIVISION_ID),
                    'divisionId' => $this->getConfigValue(self::XML_CONFIG_DIVISION_ID)
                ]
            );
        }

        return $sender->getData();
    }

    /**
     * Gets sender address.
     *
     * @return mixed
     */
    private function getSenderAddress()
    {
        $address = new DataObject();
        $address->addData(
            [
                'city' => $this->getConfigValue(self::XML_CONFIG_CITY),
                'region' => $this->getConfigValue(self::XML_CONFIG_REGION),
                'street' => $this->getConfigValue(self::XML_CONFIG_STREET),
                'postCode' => $this->getConfigValue(self::XML_CONFIG_POSTCODE),
                'building' => $this->getConfigValue(self::XML_CONFIG_BUILDING),
                'flat' => $this->getConfigValue(self::XML_CONFIG_FLAT),
                'block' => $this->getConfigValue(self::XML_CONFIG_BLOCK),
                'note' => $this->getConfigValue(self::XML_CONFIG_NOTE)
            ]
        );

        return $address->getData();
    }

    /**
     * Gets volumetric weight.
     *
     * @return float|int
     */
    private function getVolumetricWeight()
    {
        $width = (int)($this->getConfigValue(self::XML_CONFIG_WIDTH) ?? 100);
        $length = (int)($this->getConfigValue(self::XML_CONFIG_DEPTH) ?? 200);
        $height = (int)($this->getConfigValue(self::XML_CONFIG_HEIGHT) ?? 300);

        return ($width * $length * $height) / 5000;
    }

    /**
     * Sets currency conversion rates.
     *
     * @param DataObject $rates
     * @return float
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function setRates($rates)
    {
        $conversionRates = [];
        $conversionRates[$this->_storeManager->getStore()->getCurrentCurrency()->getCode()] = 1;

        if (!isset($rates['requestcurrency'])) {
            return 0;
        }

        $mainCurrencyCode = $rates['maincurrency']['currencyCode'];
        $mainCurrencyAmount = $rates['maincurrency']['amount'];
        $conversionRates[$mainCurrencyCode] = $mainCurrencyAmount;

        foreach ($rates['convertedcurrencies'] as $currency) {
            $valueCurrencyCode = $currency['currencyCode'];
            $currencyAmount = $currency['amount'];
            $conversionRates[$valueCurrencyCode] = $currencyAmount / 1;
        }

        $this->rates = $conversionRates;

        return $rates['maincurrency']['amount'] ?? 1;
    }

    /**
     * Converts an amount from the current currency to a specified currency.
     *
     * @param float|int $currentAmount
     * @param string $code
     * @return float|int
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getAmountByCurrencyCode($currentAmount, $code) {
        $currencyCode = $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
        $priceInMainCurrency = $currentAmount / $this->rates[$currencyCode];

        return $priceInMainCurrency * $this->rates[$code];
    }

    /**
     * Formats a telephone number by removing all characters except digits and the plus sign.
     *
     * @param $telephone
     * @return string|string[]|null
     */
    private function getFormattedTelephone($telephone)
    {
        return preg_replace('/[^\d+]/', '', $telephone);
    }
}
