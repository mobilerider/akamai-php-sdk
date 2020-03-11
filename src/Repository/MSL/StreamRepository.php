<?php

namespace Akamai\Sdk\Repository\MSL;

use Akamai\Sdk\Model\MSL\Stream;

class StreamRepository extends MSLRepository
{
    protected $version = 'v2';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Stream::class;
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        return $data[mr_plural($this->getResource())];
    }
}
