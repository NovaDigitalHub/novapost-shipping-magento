<?php
declare(strict_types=1);

namespace Novapost\Shipping\Controller\Place;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\HTTP\Client\Curl;
use Novapost\Shipping\Model\Service\Transfer;

class Index implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Curl
     */
    private $curl;

    /**
     * @var Transfer
     */
    private $transfer;

    /**
     * @param RequestInterface $request
     * @param ScopeConfigInterface $scopeConfig
     * @param Curl $curl
     * @param Transfer $transfer
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        RequestInterface $request,
        ScopeConfigInterface $scopeConfig,
        Curl $curl,
        Transfer $transfer,
        JsonFactory $resultJsonFactory
    ) {
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
        $this->curl = $curl;
        $this->transfer = $transfer;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Gets the address based on the Nova Post service.
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = [];
        $address = $this->getAddress();
        $countryCode = $this->getCountryCode();
        $result = $this->resultJsonFactory->create();

        try {
            $auth = $this->transfer->auth();
            if ($auth->getData('errors')) {
                $message = __('Sorry, a technical error occurred!
                    Please try again later or contact with administration');

                return $result->setData(['error' => true, 'message' => $message]);
            }

            $jwt = $auth->getData('jwt');
            $params = [
                'countryCode' => $countryCode,
                'address' => $address
            ];

            $addresses = $this->transfer->getAddressesData($jwt, $params);
            if (isset($addresses['errors'])) {
                $data[] = [
                    'id' => 0,
                    'text' => $addresses['errors']['errorMessage']
                ];
            } elseif (isset($addresses['items'])) {
                $data[] = [
                    'id' => 0,
                    'text' => __('Choose Address')
                ];

                foreach ($addresses['items'] as $k => $item) {
                    $data[] = [
                        'id' => $item['formattedAddress'],
                        'text' => $item['formattedAddress'],
                        'zip_code' => $item['zipCode'] ?? '',
                        'city' => $item['city'] ?? '',
                        'street' => $item['street'] ?? '',
                        'building' => $item['building'] ?? ''
                    ];
                }
            }

        } catch (\Exception $e) {
            $data[] = [
                'id' => 0,
                'text' => __('Sorry, a technical error occurred!
                    Please try again later or contact with administration')
            ];
        }

        return $result->setData($data);
    }

    /**
     * Returns Address if provided or null
     *
     * @return string
     */
    private function getAddress(): ?string
    {
        return $this->request->getParam('address', '') ?? '';
    }

    /**
     * Returns Country code if provided or null
     *
     * @return string
     */
    private function getCountryCode(): ?string
    {
        return $this->request->getParam('country_id', '') ?? '';
    }
}
