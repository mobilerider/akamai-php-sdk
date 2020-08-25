<?php

namespace Akamai\Sdk\Repository\QOS;

use Akamai\Sdk\Model\MSL\ReportPack;

class ReportpackRepository extends QOSRepository
{
    protected $version = 'v2';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return ReportPack::class;
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        return $data[mr_plural($this->getResource())];
    }
}
