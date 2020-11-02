<?php

namespace Akamai\Sdk\Repository\QOS;

use Akamai\Sdk\Model\QOS\Data;

class DataRepository extends QOSRepository
{
    protected $version = 'v2';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Data::class;
    }
    
    public function getResourcePath()
    {
        $resource = $this->getResource();

        return sprintf(
            "media-analytics/%s/qos-monitor/%s",
            $this->version,
            $resource
        );
    }

    public function parseMany(array $data, array &$metadata = [])
    {mr_dd($data);
        return $data;
    }
}
