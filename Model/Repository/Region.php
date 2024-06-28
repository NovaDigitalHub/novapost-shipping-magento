<?php

namespace Novapost\Shipping\Model\Repository;

use Magento\Framework\Exception\CouldNotSaveException;
use Novapost\Shipping\Api\Data\RegionInterface;
use Novapost\Shipping\Api\RegionRepositoryInterface;
use Novapost\Shipping\Model\ResourceModel\Region as RegionResource;
use Novapost\Shipping\Model\RegionFactory;
use Novapost\Shipping\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;

class Region implements RegionRepositoryInterface
{

    /**
     * @var RegionResource
     */
    private $regionResource;

    /**
     * @var RegionFactory
     */
    private $regionFactory;

    /**
     * @var RegionCollectionFactory
     */
    private $regionCollection;

    /**
     * Region constructor.
     *
     * @param RegionResource $regionResource
     * @param RegionFactory $regionFactory
     * @param RegionCollectionFactory $regionCollection
     */
    public function __construct(
        RegionResource $regionResource,
        RegionFactory $regionFactory,
        RegionCollectionFactory $regionCollection
    ) {
        $this->regionResource = $regionResource;
        $this->regionFactory = $regionFactory;
        $this->regionCollection = $regionCollection;
    }

    /**
     * Save region.
     *
     * @param RegionInterface $region
     * @return mixed|void
     */
    public function save(RegionInterface $region)
    {
        try {
            $this->regionResource->save($region);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save Source'), $exception);
        }
    }

    /**
     * List items that are assigned the specified name.
     *
     * @param string $name
     * @return false|Region|string
     */
    public function getList($name = '')
    {
        $collection = $this->regionCollection->create();

        if ($name) {
            $collection->addFieldToFilter(
                ['name'],
                [
                    ['like' => $name . '%']
                ]
            );
        }

        $data[] = ['id' => 0, 'text' => __('Choose region')];

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
     * @return \Novapost\Shipping\Model\Region
     */
    public function getByField($value, $field)
    {
        $object = $this->regionFactory->create();
        $this->regionResource->load($object, $value, $field);
        return $object;
    }
}
