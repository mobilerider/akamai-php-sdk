<?php

namespace Akamai\Sdk\Model\MSL\v3;

class EventMSLv3 extends \Mr\Bootstrap\Model\BaseModel
{
    const EVENT_DATE_FORMAT = 'd/m/Y h:i:s A';

    public static function getResource()
    {
        return 'event';
    }

    /**
     * Return start time of the event
     *
     * @return \DateTime
     */
    public function getArchiveStartTime()
    {
        $value = $this->{'primary-event'}['archive-start-time'];

        return $value ? 
            \DateTime::createFromFormat(
                self::EVENT_DATE_FORMAT, 
                $value,
                new \DateTimeZone('UTC')
            ) : null;
    }

    /**
     * Return end time of the event
     *
     * @return \DateTime
     */
    public function getArchiveEndTime()
    {
        $value = $this->{'primary-event'}['archive-end-time'];

        return $value ? 
            \DateTime::createFromFormat(
                self::EVENT_DATE_FORMAT, 
                $value,
                new \DateTimeZone('UTC')
            ) : null;
    }
}