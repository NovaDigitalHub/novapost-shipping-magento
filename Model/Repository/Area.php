<?php

namespace Novapost\Shipping\Model\Repository;

use Magento\Framework\Exception\CouldNotSaveException;
use Novapost\Shipping\Api\Data\AreaInterface;
use Novapost\Shipping\Api\AreaRepositoryInterface;
use Novapost\Shipping\Model\ResourceModel\Area as AreaResource;
use Novapost\Shipping\Model\AreaFactory;
use Novapost\Shipping\Model\ResourceModel\Area\CollectionFactory as AreaCollectionFactory;

class Area implements AreaRepositoryInterface
{

    /**
     * @var AreaResource
     */
    private $areaResource;

    /**
     * @var areaFactory
     */
    private $areaFactory;

    /**
     * @var AreaCollectionFactory
     */
    private $areaCollection;

    /**
     * Area constructor.
     *
     * @param AreaResource $areaResource
     * @param AreaFactory $areaFactory
     * @param AreaCollectionFactory $areaCollection
     */
    public function __construct(
        AreaResource $areaResource,
        AreaFactory $areaFactory,
        AreaCollectionFactory $areaCollection
    ) {
        $this->areaResource = $areaResource;
        $this->areaFactory = $areaFactory;
        $this->areaCollection = $areaCollection;
    }

    /**
     * Save Area.
     *
     * @param AreaInterface $area
     * @return Area|void
     * @throws CouldNotSaveException
     */
    public function save(AreaInterface $area)
    {
        try {
            $this->areaResource->save($area);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save Source'), $exception);
        }
    }

    /**
     * List items that are assigned the specified name.
     *
     * @param string $name
     * @return false|Area|string
     */
    public function getList($name = '')
    {
        $collection = $this->areaCollection->create();

        if ($name) {
            $collection->addFieldToFilter(
                ['name'],
                [
                    ['like' => $name . '%']
                ]
            );
        }

        $data[] = ['id' => 0, 'text' => __('Choose area')];

        if ($collection && $collection->getSize()) {
            foreach ($collection->getItems() as $item) {
                $data[] = ['id' => $item->getExternalId(), 'text' => $item->getName()];
            }
        }
        return json_encode($data);
    }

    /**
     * Gets data from the established variable.
     *
     * @param string $value
     * @param mixed $field
     * @return \Novapost\Shipping\Model\Area
     */
    public function getByField($value, $field)
    {
        $object = $this->areaFactory->create();
        $this->areaResource->load($object, $value, $field);
        return $object;
    }
}
