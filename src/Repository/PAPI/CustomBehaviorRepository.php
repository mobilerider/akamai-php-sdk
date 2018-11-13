<?php

namespace Akamai\Sdk\Repository\PAPI;

use Akamai\Sdk\Model\PAPI\Group;
use Akamai\Sdk\Repository\AbstractAkamaiRepository;

class CustomBehaviorRepository extends AbstractAkamaiRepository
{
    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Group::class;
    }

    public function getResourcePath()
    {
        return "papi/{$this->apiVersion}/custom-behaviors";
    }
}
