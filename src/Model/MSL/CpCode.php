<?php

namespace Akamai\Sdk\Model\MSL;

class CpCode extends \Mr\Bootstrap\Model\BaseModel
{
    const TYPE_INGEST = "INGEST";
    const TYPE_DELIVERY = "DELIVERY";

    public static function getResource()
    {
        return 'cpcode';
    }
}