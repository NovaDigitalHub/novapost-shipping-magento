<?php

namespace Novapost\Shipping\Api;

use Novapost\Shipping\Api\Data\AreaInterface;

/**
 * Area Interface
 *
 * @api
 * @since 1.0.0
 */
interface AreaRepositoryInterface
{
    const NP_ID                                 = 'id';
    const NP_EXTERNAL_ID                        = 'external_id';
    const NP_NAME                               = 'name';

    const NP_COUNTRY_CODE                       = 'countryCode';

    /**
     * Save Area
     *
     * @param AreaInterface $area
     * @return $this
     */
    public function save(AreaInterface $area);

    /**
     * List items that are assigned to a specified country.
     *
     * @param string $name
     * @return $this
     */
    public function getList($name = '');
}
