<?php

namespace Akamai\Sdk\Service;

use Mr\Bootstrap\Service\BaseHttpService;
use Akamai\Sdk\Repository\MSL\v3\DomainRepositoryMSLv3;

class MSLv3Service extends BaseHttpService
{
    /**
     * Returns all MSLv3 domains
     *
     * @return DomainMSLv3[]
     */
    public function getDomainsMSLv3()
    {
        return $this->_get(DomainRepositoryMSLv3::class)->all();
    }

    /**
     * Returns a MSLv3 domain by given hostname
     *
     * @param string $hostname Configuration hostname
     * 
     * @return DomainMSLv3
     */
    public function getDomainMSLv3($hostname)
    {
        return $this->_get(DomainRepositoryMSLv3::class)->get($hostname);
    }
}