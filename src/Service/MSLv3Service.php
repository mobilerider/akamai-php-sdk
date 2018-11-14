<?php

namespace Akamai\Sdk\Service;

use Mr\Bootstrap\Service\BaseHttpService;
use Akamai\Sdk\Repository\MSL\v3\DomainRepositoryMSLv3;
use Akamai\Sdk\Model\MSL\v3\DomainMSLv3;

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

    /**
     * Returns domain that matches given cp code
     * It retrieves all the domains and does the
     * search locally
     *
     * @param int $cpCode Cp code
     * 
     * @return DomainMSLv3|null
     */
    public function findDomainByCpCodeMSLv3($cpCode)
    {
        if (! $cpCode || ! is_numeric($cpCode)) {
            throw new \InvalidArgumentException('Invalid cp code');
        }

        $domains = $this->getDomainsMSLv3();

        for ($i = count($domains) - 1; $i >= 0; $i--) {
            $domain = $domains[$i];

            if ($domain->getCpCode() == $cpCode) {
                return $domain;
            }
        }

        return null;
    }
}