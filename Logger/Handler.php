<?php

namespace Novapost\Shipping\Logger;

use Monolog\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * The logger type for Nova Post shipping.
     *
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * The file path for the Nova Post shipping log.
     *
     * @var string
     */
    protected $fileName = '/var/log/novapost_shipping.log';
}
