<?php

namespace Akamai\Sdk\Repository\QOS;

use Akamai\Sdk\Repository\AbstractAkamaiRepository;

abstract class QOSRepository extends AbstractAkamaiRepository
{
    protected $version = 'v2';

    public function getResourcePath()
    {
        $resource = $this->getResource();

        return sprintf(
            "media-analytics/%s/qos-monitor/%s",
            $this->version,
            mr_plural($resource)
        );
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        return $data;
    }
}
