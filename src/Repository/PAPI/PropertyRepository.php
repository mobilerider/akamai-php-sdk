<?php

namespace Akamai\Sdk\Repository\PAPI;

use Akamai\Sdk\Model\PAPI\Property;

class PropertyRepository extends PAPIBaseRepository
{
    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Property::class;
    }
}
