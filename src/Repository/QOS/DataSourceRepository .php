<?php

namespace Akamai\Sdk\Repository\QOS;

use Akamai\Sdk\Model\MSL\DataSource;

class DataSourceRepository extends QOSRepository
{
    protected $version = 'v2';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return DataSource::class;
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        return $data[mr_plural($this->getResource())];
    }
}
