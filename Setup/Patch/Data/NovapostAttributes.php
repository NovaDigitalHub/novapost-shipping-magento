<?php

namespace Novapost\Shipping\Setup\Patch\Data;

use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Psr\Log\LoggerInterface;

class NovapostAttributes implements DataPatchInterface, PatchVersionInterface
{

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * NovapostAttributes constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param CustomerSetupFactory $customerSetupFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $customerSetupFactory,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        try {
            $this->moduleDataSetup->startSetup();
            /** @var CustomerSetup $customerSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

            $attributesInfo = [
                'np_settlement_external_id' => [
                    'label' => 'Settlement Id',
                    'type'             => 'varchar',
                    'input'            => 'text',
                    'visible'          => true,
                    'required'         => false,
                    'user_defined'     => true,
                    'system'           => false,
                    'group'            => 'General',
                    'global'           => true,
                    'visible_on_front' => false,
                ],
                'np_warehouse_external_id' => [
                    'label' => 'Warehouse Id',
                    'type'             => 'varchar',
                    'input'            => 'text',
                    'visible'          => true,
                    'required'         => false,
                    'user_defined'     => true,
                    'system'           => false,
                    'group'            => 'General',
                    'global'           => true,
                    'visible_on_front' => false,
                ],
                'np_geo_address' => [
                    'label' => 'Google api address',
                    'type'             => 'varchar',
                    'input'            => 'text',
                    'visible'          => true,
                    'required'         => false,
                    'user_defined'     => true,
                    'system'           => false,
                    'group'            => 'General',
                    'global'           => true,
                    'visible_on_front' => false,
                ],
            ];

            foreach ($attributesInfo as $attributeCode => $attributeParams) {
                $customerSetup->addAttribute('customer_address', $attributeCode, $attributeParams);
                $currentAttribute = $customerSetup->getEavConfig()->getAttribute('customer_address', $attributeCode);
                $currentAttribute->setData(
                    'used_in_forms',
                    ['adminhtml_customer_address']
                );
                $currentAttribute->save();
            }

            $this->moduleDataSetup->endSetup();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * Performs deletion of created attributes.
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $attributesInfo = [
            'np_settlement_external_id',
            'np_warehouse_external_id',
            'np_geo_address',
        ];

        foreach ($attributesInfo as $attributeCode) {
            $customerSetup->removeAttribute('customer_address', $attributeCode);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public static function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }
}
