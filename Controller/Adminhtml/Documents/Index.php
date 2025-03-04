<?php

namespace Novapost\Shipping\Controller\Adminhtml\Documents;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Message\ManagerInterface;
use Novapost\Shipping\Model\Service\Transfer;
use Novapost\Shipping\Helper\Data as ConfigHelper;

class Index extends Action
{
    /**
     * @var
     */
    private $resultRawFactory;

    /**
     * @var Transfer
     */
    private $transfer;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param RawFactory $resultRawFactory
     * @param ManagerInterface $messageManager
     * @param Transfer $transfer
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        ManagerInterface $messageManager,
        Transfer $transfer,
        ConfigHelper $configHelper
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->messageManager = $messageManager;
        $this->transfer = $transfer;
        $this->configHelper = $configHelper;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Exception
     */
    public function execute()
    {

        $auth = $this->transfer->auth();
        if ($auth->getData('errors')) {
            $this->messageManager->addErrorMessage(__('Auth error'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('sales/order');

            return $resultRedirect;
        }

        $trackNumber = $this->getRequest()->getParam('number');

        $jwt = $auth->getData('jwt');
        $params = $this->configHelper->getPrintDocumentsRequestBody($trackNumber);
        $printData = $this->transfer->getPrintDocuments($jwt, $params);
        if ($printData->getData() && $printData->getData('responseCode') == 200) {
            $result = $this->resultRawFactory->create();
            $result->setHeader('Content-Type', 'application/pdf');
            $result->setHeader('Content-Disposition', 'attachment; filename="' . $trackNumber . '.pdf"');
            $result->setContents($printData->getData('body'));

            return $result;
        }

    }

    /**
     * Check Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Novapost_Shipping::documents');
    }
}
