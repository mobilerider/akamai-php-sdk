<?php

namespace Akamai\Sdk\Model\NS;

class StorageGroup extends \Mr\Bootstrap\Model\BaseModel
{
    protected static $idFieldName = "storageGroupId";

    public static function getResource()
    {
        return 'storage-group';
    }

    public function getStorageCpCodes()
    {
        if (!$this->cpcodes || !is_array($this->cpcodes)) {
            return [];
        }

        return array_map(
            function ($c) {
                return new StorageCpCode(null, $c);
            },
            $this->cpcodes
        );
    }
}