<?php

namespace Novapost\Shipping\Model;

use Psr\Log\LoggerInterface;
use Novapost\Shipping\Model\Service\Transfer;

class Cron
{

    /**
     * @var Transfer
     */
    private $transfer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Cron constructor.
     *
     * @param LoggerInterface $logger
     * @param Transfer $transfer
     */
    public function __construct(
        LoggerInterface $logger,
        Transfer $transfer
    ) {
        $this->logger = $logger;
        $this->transfer = $transfer;
    }

    /**
     * Import data from NOVA POST.
     */
    public function execute()
    {
        $auth = $this->transfer->auth();

        if ($auth->getData('errors')) {
            $this->logger->warning('Nova post auth error');
            return;
        }

        $jwt = $auth->getData('jwt');
        $params = [
            'limit' => 100,
            'page' => 1
        ];

        $warehouses = $this->transfer->loadDivisions($jwt, $params);
        if (!isset($warehouses['errors']) && !empty($warehouses['items'])) {
            $pages = $warehouses['pages'];
            $this->transfer->importData($warehouses['items']);
            $this->logger->info('Pages:' . $pages);
            for ($i = 5; $i < $pages; $i += 5) {
                $params['page'] = $i;
                $divisions = $this->transfer->loadDivisions($jwt, $params);
                $this->logger->info('current page:' . $divisions['current_page']);
                if (!isset($warehouses['errors'])) {
                    $this->transfer->importData($divisions['items']);
                }
            }
        }
    }
}
