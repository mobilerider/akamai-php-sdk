<?php

namespace Akamai\Sdk\Repository\MSL\v3;

use Mr\Bootstrap\Interfaces\HttpDataClientInterface;

use Akamai\Sdk\Repository\AbstractAkamaiRepository;
use Akamai\Sdk\Model\MSL\v3\StreamMSLv3;
use Akamai\Sdk\Model\MSL\v3\EventMSLv3;

class EventRepositoryMSLv3 extends AbstractAkamaiRepository
{
    protected $version = 'v1';
    /**
     * Parent stream
     *
     * @var StreamMSLv3
     */
    protected $stream;

    public function __construct(HttpDataClientInterface $client, array $options = [])
    {
        parent::__construct($client, $options);

        if (empty($options['stream'])) {
            throw new \Exception('Stream required');
        }

        $this->stream = $options['stream'];
    }

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return EventMSLv3::class;
    }

    public function getResourcePath()
    {
        return sprintf(
            "/config-media-live/%s/live/%s/stream/%s/event",
            $this->version,
            $this->stream->getDomain()->getHostName(),
            $this->stream->id
        );
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        if (! $data || ! isset($data[$this->getResource()])) {
            return [];
        }

        $items = $data[$this->getResource()];

        // When XML format if only one item our decoder returns 
        // the item directly inside the `resource` key instead
        // of a list of items as for when count is > 1
        if (! is_numeric(key($items))) {
            return [$items];
        }

        return $items;
    }
}
