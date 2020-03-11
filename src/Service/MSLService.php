<?php

namespace Akamai\Sdk\Service;

use Mr\Bootstrap\Service\BaseHttpService;
use Akamai\Sdk\Model\MSL\Stream;
use Akamai\Sdk\Model\MSL\CpCode;
use Akamai\Sdk\Model\MSL\Origin;
use Akamai\Sdk\Model\MSL\MSLContract;
use Akamai\Sdk\Repository\MSL\StreamRepository;
use Akamai\Sdk\Repository\MSL\CpCodeRepository;
use Akamai\Sdk\Repository\MSL\MSLContractRepository;
use Akamai\Sdk\Repository\MSL\OriginRepository;

class MSLService extends BaseHttpService
{
    /**
     * Returns MSL streams
     *
     * @return Stream[]
     */
    public function getStreams()
    {
        return $this->_get(
            StreamRepository::class
        )->all();
    }

    /**
     * Returns MSL stream given its ID
     *
     * @return Stream
     */
    public function getStream($id)
    {
        return $this->_get(
            StreamRepository::class
        )->get($id);
    }

    /**
     * Returns MSL origins
     *
     * @return Origin[]
     */
    public function getOrigins()
    {
        return $this->domains = $this->_get(
            OriginRepository::class
        )->all();
    }

    /**
     * Returns MSL cpCodes
     *
     * @param string $type Specify cpcode type INGEST or DELIVERY.
     *
     * @return CpCode[]
     */
    public function getCpCodes($type = CpCode::TYPE_INGEST, $unused = null)
    {
        $filters = compact("type");

        if (! is_null($unused)) {
            $filters["unused"] = $unused ? "true" : "false";
        }

        return $this->_get(
            CpCodeRepository::class
        )->all($filters);
    }

    /**
     * Returns MSL Contracts
     *
     * @return MSLContract[]
     */
    public function getContracts()
    {
        return $this->_get(
            MSLContractRepository::class
        )->all();
    }

    public function createCpCode($data)
    {
        return $this->_get(
            CpCodeRepository::class
        )->create($data);
    }

    public function createOrigin($data)
    {
        return $this->_get(
            OriginRepository::class
        )->create($data);
    }
}