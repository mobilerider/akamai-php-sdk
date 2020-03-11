<?php

namespace Akamai\Sdk\Repository\PAPI;

use Akamai\Sdk\Model\PAPI\CpCode;

class CpCodeRepository extends PAPIBaseRepository
{
    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return CpCode::class;
    }
}
