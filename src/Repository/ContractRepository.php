<?php

namespace Akamai\Sdk\Repository;

use Akamai\Sdk\Model\Contract;

class ContractRepository extends AbstractAkamaiRepository
{
    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Contract::class;
    }

    public function getResourcePath()
    {
        return "contract-api/{$this->apiVersion}/contracts";
    }
}
