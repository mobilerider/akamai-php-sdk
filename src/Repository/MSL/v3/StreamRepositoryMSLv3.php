<?php

namespace Akamai\Sdk\Repository\MSL\v3;

use Mr\Bootstrap\Interfaces\HttpDataClientInterface;

use Akamai\Sdk\Repository\AbstractAkamaiRepository;
use Akamai\Sdk\Model\MSL\v3\StreamMSLv3;
use Akamai\Sdk\Model\MSL\v3\DomainMSLv3;
use Akamai\Sdk\Sdk;

class StreamRepositoryMSLv3 extends AbstractAkamaiRepository
{
    protected $version = 'v1';
    /**
     * Parent domain object
     *
     * @var DomainMSLv3
     */
    protected $domain;

    public function __construct(HttpDataClientInterface $client, array $options = [])
    {
        parent::__construct($client, $options);

        if (empty($options['domain'])) {
            throw new \Exception('Domain required');
        }

        $this->domain = $options['domain'];

        if (! $this->domain->getHostName()) {
            throw new \Exception(
                'Hostname is empty and it\'s needed for streams access'
            );
        }
    }

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return StreamMSLv3::class;
    }

    public function getResourcePath()
    {
        return sprintf(
            "/config-media-live/%s/live/%s/stream",
            $this->version,
            $this->domain->getHostName()
        );
    }

    public function create($data = [])
    {
        $model = parent::create($data);

        $model->setDomain($this->domain);

        return $model;
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        $items = $data[$this->getResource()];

        // When XML format if only one item our decoder returns 
        // the item directly inside the `resource` key instead
        // of a list of items as for when count is > 1
        if (! is_numeric(key($items))) {
            return [$items];
        }

        return $items;
    }

    public static function createInstance($domain)
    {
        if (is_string($domain)) {
            // TODO: must be better way
            // We are creating empty / stalled domain for no reason
            $domainRepo = Sdk::getGlobalContainer()->get(
                DomainRepositoryMSLv3::class
            );

            $domain = new DomainMSLv3(
                $domainRepo,
                [
                    'configuration-details' => [
                        'hostname' => $domain
                    ]
                ]
            );
        }

        return Sdk::getGlobalContainer()->get(
            StreamRepositoryMSLv3::class,
            ['options' => compact('domain')]
        );
    }

    public static function getStreams($domain)
    {
        return static::createInstance($domain)->all();
    }

    public static function getStream($domain, $id)
    {
        return static::createInstance($domain)->get($id);
    }

    /**
     * Returns a MSLv3 stream by given id
     *
     * @param string $id Stream id
     * 
     * @return StreamMSLv3
     */
    public function getStreamMSLv3($id)
    {
        return $this->getStreamRepository()->get($id);
    }
}