<?php

namespace Novapost\Shipping\Api;

use Novapost\Shipping\Api\Data\RegionInterface;

/**
 * Region Interface
 *
 * @api
 * @since 1.0.0
 */
interface RegionRepositoryInterface
{
    const NP_ID                                 = 'id';
    const NP_EXTERNAL_ID                        = 'external_id';
    const NP_NAME                               = 'name';

    const NP_AREA_NAME                          = 'area';

    /**
     * Save Region
     *
     * @param RegionInterface $region
     * @return $this
     */
    public function save(RegionInterface $region);

    /**
     * List items that are assigned to a specified country.
     *
     * @param string $name
     * @return $this
     */
    public function getList($name = '');
}
