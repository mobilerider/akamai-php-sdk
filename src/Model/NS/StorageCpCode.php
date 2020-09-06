<?php

namespace Akamai\Sdk\Model\NS;

use Mr\Bootstrap\Repository\BaseRepository;
use Mr\Bootstrap\Traits\GetSet;

class StorageCpCode
{
    use GetSet; 

    protected static $idFieldName = "cpcodeId";

    public function __construct(BaseRepository $_ = null, array $data = [])
    {
        $this->fill($data, true);
    }

    public function id()
    {
        return $this->get(static::$idFieldName);
    }
}