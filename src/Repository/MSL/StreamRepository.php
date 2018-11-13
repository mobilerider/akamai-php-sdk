<?php

namespace Akamai\Sdk\Repository\MSL;

use Akamai\Sdk\Model\Stream;

class StreamRepository extends AbstractAkamaiRepository
{
    protected $version = 'v2';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Stream::class;
    }

    public function getResourcePath()
    {
        return "config-media-live/{$this->version}/msl-origin/streams";
    }
}
