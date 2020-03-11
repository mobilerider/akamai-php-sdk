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

    public function parseOne(array $data, array &$metadata = [])
    {
        $res = mr_plural($this->getResource());

        if (! isset($data[$res]["items"][0])) {
            return null;
        }
        
        return $data[$res]["items"][0];
    }   
}
