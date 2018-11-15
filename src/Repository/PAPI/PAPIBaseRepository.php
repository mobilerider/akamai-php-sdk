<?php

namespace Akamai\Sdk\Repository\PAPI;

use Akamai\Sdk\Repository\AbstractAkamaiRepository;

abstract class PAPIBaseRepository extends AbstractAkamaiRepository
{
    const PAPI_API_VERSION = 'v0';

    public function getResourcePath()
    {
        $modelClass = $this->getModelClass();

        return sprintf(
            'papi/%s/%s',
            self::PAPI_API_VERSION,
            mr_plural($modelClass::getResource())
        );
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        return $data[mr_plural($this->getResource())]['items'];
    }
}