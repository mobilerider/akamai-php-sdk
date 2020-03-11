<?php

namespace Akamai\Sdk\Repository\MSL;

use Akamai\Sdk\Model\MSL\Origin;

class OriginRepository extends MSLRepository
{
    protected $version = 'v2';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Origin::class;
    }
}
