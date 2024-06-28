<?php

namespace Novapost\Shipping\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResourceConnection;
use Novapost\Shipping\Model\Service\Transfer;
use Novapost\Shipping\Model\Repository\Warehouse as WarehouseRepository;

class Warehouse extends Action
{

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var Transfer
     */
    private $transfer;

    /**
     * @var Http
     */
    private $request;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var WarehouseRepository
     */
    private $warehouseRepository;

    /**
     * Warehouse constructor.
     *
     * @param Action\Context $context
     * @param JsonFactory $jsonFactory
     * @param Http $request
     * @param Transfer $transfer
     * @param ResourceConnection $resourceConnection
     * @param WarehouseRepository $warehouseRepository
     */
    public function __construct(
        Action\Context $context,
        JsonFactory $jsonFactory,
        Http $request,
        Transfer $transfer,
        ResourceConnection $resourceConnection,
        WarehouseRepository $warehouseRepository
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->transfer = $transfer;
        $this->request = $request;
        $this->resourceConnection = $resourceConnection;
        $this->warehouseRepository = $warehouseRepository;

        parent::__construct($context);
    }

    /**
     * Retrieves data about the process of parsing information.
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $result = $this->jsonFactory->create();

        $auth = $this->transfer->auth();
        if ($auth->getData('errors')) {
            $message = __('Warehouse not loaded. Errors:'). PHP_EOL;

            foreach ($auth->getData('errors') as $key => $error) {
                $message .= $key . ' ' . $error . PHP_EOL;
            }

            return $result->setData(['error' => true, 'message' => $message]);
        }

        $page = $this->request->getParam('page') ?? 1;
        $params = [
            'limit' => 100,
            'page' => $page
        ];

        if ($page == 1) {
            $tables = ['np_area', 'np_region', 'np_settlement', 'np_warehouse'];
            $connection = $this->resourceConnection->getConnection();
            foreach ($tables as $table) {
                $connection->truncateTable($table);
            }
        }

        $jwt = $auth->getData('jwt');
        $warehouses = $this->transfer->loadDivisions($jwt, $params);
        if (!$warehouses || empty($warehouses['items'])) {
            $result->setData(
                [
                    'error' => true,
                    'message' => __('Warehouses not loaded from NP')
                ]
            );
        } elseif (isset($warehouses['errors'])) {
            $errors = implode(',', $warehouses['errors']);

            $result->setData(
                [
                    'error' => true,
                    'message' => __('Warehouses not loaded from NP' . $errors)
                ]
            );
        } else {
            $resultSync = $this->transfer->importData($warehouses['items']);
            $result->setData(
                [
                    'result' => $resultSync,
                    'currentPage' => $warehouses['current_page'],
                    'pages' => $warehouses['pages'],
                    'message' => __('Successfully')
                ]
            );
        }
        return $result;
    }
}
