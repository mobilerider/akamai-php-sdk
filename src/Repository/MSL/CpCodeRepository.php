<?php

namespace Akamai\Sdk\Repository\MSL;

use Akamai\Sdk\Model\MSL\CpCode;

class CpCodeRepository extends MSLRepository
{
    protected $version = 'v2';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return CpCode::class;
    }
}
