<?php

namespace Akamai\Sdk\Repository\MSL;

use Akamai\Sdk\Model\MSL\Origin;
use Akamai\Sdk\Repository\AbstractAkamaiRepository;

abstract class QOSRepository extends AbstractAkamaiRepository
{
    protected $version = 'v2';

    public function getResourcePath()
    {
        $resource = $this->getResource();

        return sprintf(
            "config-media-live/%s/msl-origin/%s",
            $this->version,
            mr_plural($resource)
        );
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        return $data;
    }
}
