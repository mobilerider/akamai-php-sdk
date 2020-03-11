<?php

namespace Akamai\Sdk\Repository\MSL;

use Akamai\Sdk\Model\MSL\MSLContract;

class MSLContractRepository extends MSLRepository
{
    protected $version = 'v2';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return MSLContract::class;
    }
}
