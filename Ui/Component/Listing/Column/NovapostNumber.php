<?php

namespace Novapost\Shipping\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Block\Widget\Context as WidgetContext;
use Novapost\Shipping\Model\Service\Transfer;
use Novapost\Shipping\Helper\Data as ConfigHelper;

class NovapostNumber extends Column
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    private $widgetContext;

    /**
     * @var Transfer
     */
    private $transfer;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * __construct
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param ResultFactory $resultFactory
     * @param WidgetContext $widgetContext
     * @param Transfer $transfer
     * @param ConfigHelper $configHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ResultFactory $resultFactory,
        WidgetContext $widgetContext,
        Transfer $transfer,
        ConfigHelper $configHelper,
        array $components = [],
        array $data = []
    ) {
        $this->transfer = $transfer;
        $this->configHelper = $configHelper;
        $this->widgetContext = $widgetContext;
        $this->resultFactory = $resultFactory;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws \Exception
     */
    public function prepareDataSource(array $dataSource)
    {
        $auth = $this->transfer->auth();
        if ($auth->getData('errors')) {
            return $dataSource;
        }

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                if ($item[$fieldName]) {
                    $url = $this->widgetContext->getUrlBuilder()->getUrl(
                        'novapost/documents/index',
                        ['number' => $item['novapost_number']]
                    );
                    $item[$fieldName] = [
                        'view' => [
                            'href' => $url,
                            'label' => $item['novapost_number']
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
