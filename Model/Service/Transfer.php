<?php

namespace Novapost\Shipping\Model\Service;

use Laminas\Http\Exception\RuntimeException;
use Magento\Framework\DataObject;
use Magento\Framework\HTTP\Client\Curl;
use Novapost\Shipping\Logger\Logger;
use Novapost\Shipping\Model\Warehouse;
use Novapost\Shipping\Model\Settlement;
use Novapost\Shipping\Model\Region;
use Novapost\Shipping\Model\Area;
use Novapost\Shipping\Model\Repository\Warehouse as warehouseRepository;
use Novapost\Shipping\Model\Repository\Settlement as settlementRepository;
use Novapost\Shipping\Model\Repository\Region as regionRepository;
use Novapost\Shipping\Model\Repository\Area as areaRepository;
use Novapost\Shipping\Helper\Data;

class Transfer
{
    const HEADER                    = 'content-type: application/json';
    const PAGINATION_PAGE_SIZE      = 100;

    const NP_AUTH_URL               = 'clients/authorization';
    const NP_DIVISION_METHOD        = 'divisions';
    const NP_VERIFY_ADDRESS         = 'shipments/places';
    const NP_SHIPMENTS_CALCULATIONS = 'shipments/calculations';
    const NP_SHIPMENTS_DOCUMENTS    = 'shipments';
    const NP_EXCHANGE_RATES         = 'exchange-rates/conversion';
    const NP_SHIPMENTS_PRINT        = 'shipments/print';

    /**
     * @var Data
     */
    private $configHelper;

    /**
     * @var Curl
     */
    private $curl;

    /**
     * @var warehouseRepository
     */
    private $warehouseRepository;

    /**
     * @var settlementRepository
     */
    private $settlementRepository;

    /**
     * @var regionRepository
     */
    private $regionRepository;

    /**
     * @var areaRepository
     */
    private $areaRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Transfer constructor.
     *
     * @param Data $configHelper
     * @param Curl $curl
     * @param warehouseRepository $warehouseRepository
     * @param settlementRepository $settlementRepository
     * @param regionRepository $regionRepository
     * @param areaRepository $areaRepository
     * @param Logger $logger
     */
    public function __construct(
        Data $configHelper,
        Curl $curl,
        warehouseRepository $warehouseRepository,
        settlementRepository $settlementRepository,
        regionRepository $regionRepository,
        areaRepository $areaRepository,
        Logger $logger
    ) {
        $this->configHelper = $configHelper;
        $this->curl = $curl;
        $this->warehouseRepository = $warehouseRepository;
        $this->settlementRepository = $settlementRepository;
        $this->regionRepository = $regionRepository;
        $this->areaRepository = $areaRepository;
        $this->logger = $logger;
    }

    /**
     * Post/Get request into gateway
     *
     * @param string $method
     * @param string $auth
     * @param array $request
     * @param string $type
     * @return DataObject
     */
    public function getRequest($method, $auth, $request, $type = 'GET')
    {
        $result = new DataObject();

        $this->curl->addHeader('System', 'magento');
        if ($auth) {
            $this->curl->addHeader('Authorization', $auth);
        }

        if ($method == self::NP_SHIPMENTS_PRINT) {
            $this->curl->removeHeader('Content-Type');
            $this->curl->addHeader('Accept', 'application/pdf');
        } else {
            $this->curl->addHeader('Content-Type', 'application/json');
            $this->curl->addHeader('Accept', 'application/json');
        }
        $this->curl->setTimeout(10);

        if ($type == 'GET') {
            $requestData = http_build_query($this->sanitizeRequestData($request));
            $this->curl->get($this->configHelper->getApiUrl() . $method . '?' . $requestData);
        } else {
            $this->curl->post($this->configHelper->getApiUrl() . $method, json_encode($this->sanitizeRequestData($request)));
        }

        $response = $this->curl->getBody();
        try {
            $responseArray = json_decode($response, true);

            if (is_array($responseArray)) {
                $result->setData(array_change_key_case($responseArray, CASE_LOWER));
                $result->setData('responseCode', $this->curl->getStatus());
            } else {
                $result->addData(
                    [
                        'body' => $response,
                        'responseCode' => $this->curl->getStatus(),
                    ]
                );
            }
        } catch (RuntimeException $e) {
            $result->addData(
                [
                    'responseCode'        => $e->getCode(),
                    'responseReasonText' => $e->getMessage()
                ]
            );
            throw $e;
        }

        return $result;
    }

    /**
     * Authenticates with the remote API.
     *
     * @return DataObject
     * @throws \Exception
     */
    public function auth()
    {
        $result = null;

        try {
            $result = $this->getRequest(
                self::NP_AUTH_URL,
                $this->configHelper->getApiKey(),
                [ 'apiKey' => $this->configHelper->getApiKey() ]
            );
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }

    /**
     * Loads divisions data.
     *
     * @param string $jwt
     * @param array $params
     * @param int $step
     * @return array|DataObject
     * @throws \Exception
     */
    public function loadDivisions($jwt, $params = [], $step = 1)
    {
        $result = null;
        try {
            $result = $this->getRequest(self::NP_DIVISION_METHOD, $jwt, $params);

            if (isset($result['errors'])) {
                return $result;
            }
            $resultData = [];
            $resultData['items'] = isset($result['items']) ? $result['items'] : $result;
            $resultData['pages'] = $result['last_page'];
            $resultData['current_page'] = $params['page'];
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        if ($this->issetNextPage($result, $params['page']) && $step <= 5) {
            $params['page']++;
            $step++;
            $data = $this->loadDivisions($jwt, $params, $step);

            $resultData['items'] = array_merge($resultData['items'], $data['items']);
        }

        return $resultData;
    }

    /**
     * Imports warehouse data.
     *
     * @param array $warehouses
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function importData(array $warehouses = [])
    {
        if (empty($warehouses)) {
            return false;
        }

        foreach ($warehouses as $warehouseData) {
            $warehouse = $this->warehouseRepository->getByField(
                $warehouseData[warehouseRepository::NP_EXTERNAL_ID],
                warehouseRepository::NP_ENTITY_FIELD
            );

            if ($this->isWarehouseUnchanged($warehouse, $warehouseData)) {
                continue;
            }

            $this->updateWarehouse($warehouse, $warehouseData);
            $this->saveWarehouseWithRelatedData($warehouse, $warehouseData);
        }

        return true;
    }

    /**
     * Checks if the warehouse data is unchanged.
     *
     * @param warehouse $warehouse
     * @param array $warehouseData
     * @return bool
     */
    private function isWarehouseUnchanged(Warehouse $warehouse, $warehouseData)
    {
        return $warehouse->getExternalId() == $warehouseData[warehouseRepository::NP_EXTERNAL_ID] &&
            $warehouse->getShortName() == $warehouseData[warehouseRepository::NP_SHORTNAME];
    }

    /**
     * Updates the warehouse with new data.
     *
     * @param Warehouse $warehouse
     * @param array $warehouseData
     */
    private function updateWarehouse(Warehouse $warehouse, $warehouseData)
    {
        $warehouse->setDivisionId($warehouseData[warehouseRepository::NP_ID])
            ->setExternalId($warehouseData[warehouseRepository::NP_EXTERNAL_ID])
            ->setName($warehouseData[warehouseRepository::NP_NAME])
            ->setShortName($warehouseData[warehouseRepository::NP_SHORTNAME])
            ->setSource($warehouseData[warehouseRepository::NP_SOURCE])
            ->setCountryCode($warehouseData[warehouseRepository::NP_COUNTRY_CODE])
            ->setAddress($warehouseData[warehouseRepository::NP_ADDRESS])
            ->setNumber($warehouseData[warehouseRepository::NP_NUMBER])
            ->setStatus($warehouseData[warehouseRepository::NP_STATUS])
            ->setCustomerServiceAvailable($warehouseData[warehouseRepository::NP_CUSTOMER_SERVICE_AVAILABLE])
            ->setDivisionCategory($warehouseData[warehouseRepository::NP_DIVISION_CATEGORY])
            ->setPublicPhones(json_encode($warehouseData[warehouseRepository::NP_PUBLIC_PHONES]))
            ->setInternalPhones(json_encode($warehouseData[warehouseRepository::NP_INTERNAL_PHONES]))
            ->setResponsiblePerson($warehouseData[warehouseRepository::NP_RESPONSIBLE_PERSON])
            ->setPartner(json_encode($warehouseData[warehouseRepository::NP_PARTNER]))
            ->setOwnerDivision(json_encode($warehouseData[warehouseRepository::NP_OWNER_DIVISION]))
            ->setLatitude($warehouseData[warehouseRepository::NP_LATITUDE])
            ->setLongitude($warehouseData[warehouseRepository::NP_LONGITUDE])
            ->setDistance($warehouseData[warehouseRepository::NP_DISTANCE])
            ->setPostcode($warehouseData[warehouseRepository::NP_FULL_ADDRESS][warehouseRepository::NP_ZIPCODE] ?? '')
            ->setMaxWeightPlaceSender($warehouseData[warehouseRepository::NP_MAX_WEIGHT_PLACE_SENDER])
            ->setMaxLengthPlaceSender($warehouseData[warehouseRepository::NP_MAX_LENGTH_PLACE_SENDER])
            ->setMaxWidthPlaceSender($warehouseData[warehouseRepository::NP_MAX_WIDTH_PLACE_SENDER])
            ->setMaxHeightPlaceSender($warehouseData[warehouseRepository::NP_MAX_HEIGHT_PLACE_SENDER])
            ->setMaxWeightPlaceRecipient($warehouseData[warehouseRepository::NP_MAX_WEIGHT_PLACE_RECIPIENT])
            ->setMaxLengthPlaceRecipient($warehouseData[warehouseRepository::NP_MAX_LENGTH_PLACE_RECIPIENT])
            ->setMaxWidthPlaceRecipient($warehouseData[warehouseRepository::NP_MAX_WIDTH_PLACE_RECIPIENT])
            ->setMaxHeightPlaceRecipient($warehouseData[warehouseRepository::NP_MAX_HEIGHT_PLACE_RECIPIENT])
            ->setMaxCostPlace($warehouseData[warehouseRepository::NP_MAX_COST_PLACE])
            ->setMaxDeclaredCostPlace($warehouseData[warehouseRepository::NP_MAX_DECLARED_COST_PLACE])
            ->setWorkSchedule(json_encode($warehouseData[warehouseRepository::NP_WORK_SCHEDULE]));
    }

    /**
     * Saves the warehouse and its related data.
     *
     * @param Warehouse $warehouse
     * @param array $warehouseData
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    private function saveWarehouseWithRelatedData(Warehouse $warehouse, $warehouseData)
    {
        $settlementData = $warehouseData[warehouseRepository::NP_SETTLEMENT_NAME];
        $settlementId = $settlementData[settlementRepository::NP_ID];
        $warehouse->setSettlementId($settlementId);

        $settlement = $this->settlementRepository->getByField($settlementId, settlementRepository::NP_EXTERNAL_ID);
        if ($settlement->getEntityId() === null) {
            $this->updateSettlement($settlement, $settlementData, $warehouseData);
        }

        $this->warehouseRepository->save($warehouse);
    }

    /**
     * Updates the settlement with new data and saves it along with related region and area.
     *
     * @param Settlement $settlement
     * @param array $settlementData
     * @param array $warehouseData
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    private function updateSettlement(Settlement $settlement, $settlementData, $warehouseData)
    {
        $settlement->setExternalId($settlementData[settlementRepository::NP_ID])
            ->setName($settlementData[settlementRepository::NP_NAME])
            ->setCountryCode($warehouseData[warehouseRepository::NP_COUNTRY_CODE]);

        $regionData = $settlementData[warehouseRepository::NP_REGION_NAME];
        $regionId = $regionData[regionRepository::NP_ID];
        $region = $this->regionRepository->getByField($regionId, regionRepository::NP_EXTERNAL_ID);
        if ($region->getEntityId() === null) {
            $this->updateRegion($region, $regionData, $warehouseData);
        }

        $settlement->setRegionId($regionId);
        $this->settlementRepository->save($settlement);
    }

    /**
     * Updates the region with new data and saves it along with related area.
     *
     * @param Region $region
     * @param array $regionData
     * @param array $warehouseData
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    private function updateRegion(Region $region, $regionData, $warehouseData)
    {
        $region->setExternalId($regionData[regionRepository::NP_ID])
            ->setName($regionData[regionRepository::NP_NAME]);

        if (isset($regionData[warehouseRepository::NP_ARIA_NAME])) {
            $areaData = $regionData[warehouseRepository::NP_ARIA_NAME];
            $areaId = $areaData[areaRepository::NP_ID];
            $area = $this->areaRepository->getByField($areaId, areaRepository::NP_EXTERNAL_ID);
            if ($area->getEntityId() === null) {
                $this->updateArea($area, $areaData, $warehouseData);
            }

            $region->setAreaId($areaId);
        }

        $this->regionRepository->save($region);
    }

    /**
     * Updates the area with new data and saves it.
     *
     * @param Area $area
     * @param array $areaData
     * @param array $warehouseData
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    private function updateArea(Area $area, $areaData, $warehouseData)
    {
        $area->setExternalId($areaData[areaRepository::NP_ID])
            ->setName($areaData[areaRepository::NP_NAME])
            ->setCountryCode($warehouseData[areaRepository::NP_COUNTRY_CODE]);

        $this->areaRepository->save($area);
    }

    /**
     * Retrieves address data.
     *
     * @param string $jwt
     * @param array $params
     * @return DataObject
     * @throws \Exception
     */
    public function getAddressesData($jwt, $params)
    {
        $result = null;
        try {
            $result = $this->getRequest(self::NP_VERIFY_ADDRESS, $jwt, $params, 'POST');
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }

    /**
     * Gets the delivery cost
     *
     * @param string $jwt
     * @param array $params
     * @return DataObject
     * @throws \Exception
     */
    public function getDeliveryCost($jwt, $params)
    {
        $result = null;
        try {
            $result = $this->getRequest(self::NP_SHIPMENTS_CALCULATIONS, $jwt, $params, 'POST');
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }

    /**
     * Create shipment document.
     *
     * @param string $jwt
     * @param array $params
     * @return DataObject
     * @throws \Exception
     */
    public function setShipmentDocument($jwt, $params)
    {
        $result = null;
        try {
            $result = $this->getRequest(self::NP_SHIPMENTS_DOCUMENTS, $jwt, $params, 'POST');
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }

    /**
     * Gets the exchange rates.
     *
     * @param $jwt
     * @param $params
     * @return DataObject
     * @throws \Exception
     */
    public function getExchangeRates($jwt, $params)
    {
        try {
            $result = $this->getRequest(self::NP_EXCHANGE_RATES, $jwt, $params, 'POST');
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }

    /**
     * Gets the print documents.
     *
     * @param $jwt
     * @param $params
     * @return DataObject
     * @throws \Exception
     */
    public function getPrintDocuments($jwt, $params)
    {
        try {
            $result = $this->getRequest(self::NP_SHIPMENTS_PRINT, $jwt, $params);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }

    /**
     * Checks if there is a next page of data.
     *
     * @param array $data
     * @param int $currentPage
     * @return bool
     */
    private function issetNextPage($data = [], $currentPage = 1)
    {
        return isset($data['last_page']) && $currentPage < $data['last_page'];
    }

    /**
     * Sanitize request data
     *
     * @param array $data
     * @return array
     */
    private function sanitizeRequestData($data)
    {
        $requestData = [];
        foreach ($data as $k => $v) {
            if (is_string($v) && (strpos($v, '&') !== false || strpos($v, '=') !== false)) {
                $requestData[$k . '[' . strlen($v) . ']'] = $v;
            } else {
                $requestData[$k] = $v;
            }
        }
        return $requestData;
    }
}
