<?php

namespace Akamai\Sdk\Repository\NS;

use Akamai\Sdk\Repository\AbstractAkamaiRepository;
use Akamai\Sdk\Model\NS\StorageGroup;

class StorageGroupRepository extends AbstractAkamaiRepository
{
    protected $version = 'v1';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return StorageGroup::class;
    }

    public function getResourcePath()
    {
        return "storage/{$this->version}/storage-groups";
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        return $data["items"];
    }
}
