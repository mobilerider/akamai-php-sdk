<?php

namespace Akamai\Sdk\Service;

use Mr\Bootstrap\Service\BaseHttpService;
use Akamai\Sdk\Repository\MSL\v3\DomainRepositoryMSLv3;
use Akamai\Sdk\Model\MSL\v3\DomainMSLv3;
use Akamai\Sdk\Model\MSL\v3\CpCodeMSLv3;
use Akamai\Sdk\Model\MSL\v3\StreamMSLv3;
use Akamai\Sdk\Repository\MSL\v3\CpCodeRepositoryMSLv3;
use Akamai\Sdk\Repository\MSL\v3\StreamRepositoryMSLv3;

class MSLv3Service extends BaseHttpService
{
    /**
     * Cached domains
     *
     * @var DomainMSLv3[]
     */
    protected $domains;

    /**
     * Cached domains
     *
     * @var CpCodeMSLv3[]
     */
    protected $cpCodes;

    /**
     * Returns all MSLv3 domains
     *
     * @return DomainMSLv3[]
     */
    public function getDomainsMSLv3($refresh = false)
    {
        if (is_null($this->domains) || $refresh) {
            $this->domains = $this->_get(
                DomainRepositoryMSLv3::class
            )->all();
        }

        return $this->domains;
    }

    /**
     * Returns all MSLv3 cpCodes
     *
     * @return CpCodeMSLv3[]
     */
    public function getCpCodesMSLv3($refresh = false)
    {
        if (is_null($this->cpCodes) || $refresh) {
            $this->cpCodes = $this->_get(
                CpCodeRepositoryMSLv3::class
            )->all();
        }

        return $this->cpCodes;
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
    public function findDomainByCpCodeMSLv3($cpCode, $refresh = false)
    {
        if (! $cpCode || ! is_numeric($cpCode)) {
            throw new \InvalidArgumentException('Invalid cp code');
        }

        $domains = $this->getDomainsMSLv3($refresh);

        for ($i = count($domains) - 1; $i >= 0; $i--) {
            $domain = $domains[$i];

            if ($domain->getCpCode() == $cpCode) {
                return $domain;
            }
        }

        return null;
    }

    public function getStreamsMSLv3($domain)
    {
        return StreamRepositoryMSLv3::getStreams($domain);
    }

    /**
     * Return stream
     *
     * @param string $domain
     * @param int $id
     * 
     * @return StreamMSLv3
     */
    public function getStreamMSLv3($domain, $id)
    {
        return StreamRepositoryMSLv3::getStream($domain, $id);
    }
}