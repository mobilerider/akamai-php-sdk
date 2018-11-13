<?php

namespace Akamai\Sdk\Model\MSL\v3;

use Akamai\Sdk\Repository\MSL\v3\EventRepositoryMSLv3;

class StreamMSLv3 extends \Mr\Bootstrap\Model\BaseModel
{
    static protected $idFieldName = 'stream-id';

    /**
     * Parent domain
     *
     * @var DomainMSLv3
     */
    protected $domain;

    /**
     * Event MSLv3 repository for this stream
     *
     * @var EventRepositoryMSLv3
     */
    protected $eventRepository;
    /**
     * List of events
     *
     * @var EventMSLv3
     */
    protected $events;

    public static function getResource()
    {
        return 'stream';
    }

    public function setDomain(DomainMSLv3 $domain)
    {
        $this->domain = $domain;
    }

    /**
     * Return parent domain
     *
     * @return DomainMSLv3
     */
    public function getDomain()
    {
        return $this->domain;
    }

    protected function getEventRepository()
    {
        if (is_null($this->eventRepository)) {
            $options = [
                'stream' => $this
            ];

            $this->eventRepository = $this->_get(
                EventRepositoryMSLv3::class, compact('options')
            );
        }

        return $this->eventRepository;
    }

    /**
     * Return list of events for this stream
     *
     * @return EventMSLv3[]
     */
    public function getEvents()
    {
        if (is_null($this->events)) {
            $this->events = $this->getEventRepository()->all();
        }

        return $this->events;
    }
}