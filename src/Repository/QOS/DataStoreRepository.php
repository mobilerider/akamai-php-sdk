<?php

namespace Akamai\Sdk\Repository\QOS;

use Akamai\Sdk\Model\QOS\DataStore;

class DataStoreRepository extends QOSRepository
{
    protected $version = 'v2';

    /**
    * @return mixed
    */
    public function getModelClass()
    {
        return DataStore::class;
    }
  
}
