<?php

namespace Akamai\Sdk\Repository\QOS;

use Akamai\Sdk\Model\QOS\ReportPack;
use Akamai\Sdk\Repository\QOS\QOSRepository;

class ReportPackRepository extends QOSRepository
{
    protected $version = 'v2';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return ReportPack::class;
    }
    
}
