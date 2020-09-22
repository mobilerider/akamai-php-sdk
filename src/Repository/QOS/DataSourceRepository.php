<?php

namespace Akamai\Sdk\Repository\QOS;

use Akamai\Sdk\Model\QOS\DataSource;

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
}
