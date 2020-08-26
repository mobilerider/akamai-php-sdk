<?php

namespace Akamai\Sdk\Model\QOS;

use Akamai\Sdk\Service\QosService;

class Data extends \Mr\Bootstrap\Model\BaseModel
{
    public static function getResource()
    {
        $reportId = QosService::$reportId;
        
        return "report-packs/{$reportId}/data";
    }
}
