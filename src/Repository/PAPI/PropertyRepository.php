<?php

namespace Akamai\Sdk\Repository\PAPI;

use Akamai\Sdk\Model\PAPI\Group;
use Akamai\Sdk\Model\PAPI\Property;
use Akamai\Sdk\Repository\AbstractAkamaiRepository;

class PropertyRepository extends AbstractAkamaiRepository
{
    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Property::class;
    }

    public function getResourcePath()
    {
        return "papi/{$this->apiVersion}/properties";
    }
}
