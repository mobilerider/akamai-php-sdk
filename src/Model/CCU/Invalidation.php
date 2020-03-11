<?php

namespace Akamai\Sdk\Model\CCU;

use Mr\Bootstrap\Repository\BaseRepository;

class Invalidation extends \Mr\Bootstrap\Model\BaseModel
{
    const TARGET_BY_URL = "url";
    const TARGET_BY_CPCODE = "cpcode";

    public $target = self::TARGET_BY_URL;
    public $network = 'production';

    protected static $idFieldName = "purgeId";

    public function __construct(BaseRepository $repository, array $data = [])
    {
        $data["objects"] = $data["objects"] ?? [];
        
        parent::__construct($repository, $data);
    }

    public static function getResource()
    {
        return 'invalidate';
    }
}