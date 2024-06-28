<?php

namespace Novapost\Shipping\Plugin;

use Magento\Checkout\Api\Data\PaymentDetailsInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Quote\Api\CartRepositoryInterface;

use Novapost\Shipping\Helper\Data as ConfigHelper;

class ShippingInformationManagementPlugin
{
    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var null
     */
    protected $extensionAttributes = null;

    /**
     * ShippingInformationManagementPlugin constructor.
     *
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    /**
     * Adding NOVA POST fields to the shipping address
     *
     * @param ShippingInformationManagement $subject
     * @param int $cartId
     * @param ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        int $cartId,
        ShippingInformationInterface $addressInformation
    ) {

        $shippingAddress = $addressInformation->getShippingAddress();
        $extensionAttributes = $shippingAddress->getExtensionAttributes();
        $shippingAddress->setNpSettlementExternalId($extensionAttributes->getNpSettlementExternalId());
        $shippingAddress->setNpWarehouseExternalId($extensionAttributes->getNpWarehouseExternalId());
        $shippingAddress->setNpGeoAddress($extensionAttributes->getNpGeoAddress());
        $shippingAddress->setNpGeoAddressBuilding($extensionAttributes->getNpGeoAddressBuilding());
    }

    /**
     * Change street address value to geo address
     *
     * @param ShippingInformationManagement $subject
     * @param PaymentDetailsInterface $result
     * @param int $cartId
     * @param ShippingInformationInterface $addressInformation
     * @return PaymentDetailsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterSaveAddressInformation(
        ShippingInformationManagement $subject,
        PaymentDetailsInterface $result,
        int $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->cartRepository->getActive($cartId);
        $shippingAddress = $quote->getShippingAddress();

        if ($addressInformation->getShippingMethodCode() == ConfigHelper::CODE_COURIER) {
            $shippingAddress->setStreet($shippingAddress->getNpGeoAddress());
            $shippingAddress->save();
        }

        return $result;
    }
}
