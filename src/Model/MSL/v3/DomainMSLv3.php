<?php

namespace Akamai\Sdk\Model\MSL\v3;

use Akamai\Sdk\Repository\MSL\v3;
use Akamai\Sdk\Repository\MSL\v3\StreamRepositoryMSLv3;
use Akamai\Sdk\Model\MSL\v3\StreamMSLv3;

class DomainMSLv3 extends \Mr\Bootstrap\Model\BaseModel
{
    /**
     * Stream MSLv3 repository for this domain
     *
     * @var StreamRepositoryMSLv3
     */
    protected $streamRepository;

    /**
     * @inheritDoc
     */
    public static function getResource()
    {
        return 'domain';
    }

    /**
     * Returns a new stream repository instance associated 
     * with this domain
     *
     * @return StreamRepositoryMSLv3
     */
    protected function getStreamRepository()
    {
        if (is_null($this->streamRepository)) {
            $options = [
                'domain' => $this
            ];

            $this->streamRepository = $this->_get(
                StreamRepositoryMSLv3::class, compact('options')
            );
        }

        return $this->streamRepository;
    }

    /**
     * Returns configuration reporting cp code
     *
     * @return int
     */
    public function getName()
    {
        return $this->{'configuration-details'}['configuration-name'];
    }

    /**
     * Returns configuration reporting cp code
     *
     * @return int
     */
    public function getCpCode()
    {
        return $this->{'configuration-details'}['reporting-cpcode'];
    }

    public function getHostName()
    {
        return $this->{'configuration-details'}['hostname'];
    }

    /**
     * Return domain name property from archive configuration
     *
     * @return string
     */
    public function getArchiveDomainName()
    {
        return $this->{'archive-configuration'}['domain-name'];
    }

    /**
     * Return streams created from this domain configuration
     *
     * @return StreamMSLv3[]
     */
    public function getStreams()
    {
        return $this->getStreamRepository()->all();
    }
}