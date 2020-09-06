<?php

namespace Akamai\Sdk\Repository\NS;

use Akamai\Sdk\Model\NS\UploadAccount;
use Akamai\Sdk\Repository\AbstractAkamaiRepository;

class UploadAccountRepository extends AbstractAkamaiRepository
{
    protected $version = 'v1';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return UploadAccount::class;
    }

    public function getResourcePath()
    {
        return "storage/{$this->version}/upload-accounts";
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        return $data["items"];
    }
}
