<?php

namespace Akamai\Sdk\Repository\MSL\v3;

use Akamai\Sdk\Repository\AbstractAkamaiRepository;
use Akamai\Sdk\Model\MSL\v3\DomainMSLv3;

class DomainRepositoryMSLv3 extends AbstractAkamaiRepository
{
    protected $version = 'v1';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return DomainMSLv3::class;
    }

    public function getResourcePath()
    {
        return "/config-media-live/{$this->version}/live";
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        if (! $data) {
            return [];
        }

        $items = $data[$this->getResource()];

        // When XML format if only one item our decoder returns 
        // the item directly inside the `resource` key instead
        // of a list of items as for when count is > 1
        if (! is_numeric(key($items))) {
            return [$items];
        }

        return $items;
    }
}
