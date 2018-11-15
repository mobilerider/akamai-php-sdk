<?php

namespace Akamai\Sdk\Repository\PAPI;

use Akamai\Sdk\Repository\PAPI\PAPIBaseRepository;
use Akamai\Sdk\Model\PAPI\Contract;

class ContractRepository extends PAPIBaseRepository
{
    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Contract::class;
    }
}
