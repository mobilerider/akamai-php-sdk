<?php

namespace Akamai\Sdk\Service;

use Mr\Bootstrap\Service\BaseHttpService;
use Akamai\Sdk\Repository\NS\UploadAccountRepository;
use Akamai\Sdk\Model\NS\UploadAccount;
use Akamai\Sdk\Repository\NS\StorageGroupRepository;
use Akamai\Sdk\Model\NS\StorageGroup;

class NSService extends BaseHttpService
{
    /**
     * Returns upload accounts
     *
     * @return UploadAccount[]
     */
    public function getUploadAccounts()
    {
        return $this->_get(
            UploadAccountRepository::class
        )->all();
    }

    public function createUploadAccount(
        StorageGroup $sg, $identifier, $email, $readOnly = false, array $data = []
    ) {
        $acc = $this->_get(
            UploadAccountRepository::class
        )->create($data);

        $acc->id($identifier);
        $acc->email = $email;
        $acc->setStorageGroup($sg, $readOnly);

        return $acc;
    }

    public function createSshAccount(
        StorageGroup $sg,
        $identifier,
        $email,
        $sshKey,
        $readOnly = false
    ) {
        $acc = $this->createUploadAccount(
            $sg,
            $identifier,
            $email,
            $readOnly
        );

        $acc->addSshKey($sshKey);

        return $acc;
    }

    /**
     * Returns storage groups
     *
     * @return StorageGroup[]
     */
    public function getStorageGroups($cpCode = null, $purpose = null)
    {
        $filters = [];

        if ($cpCode) {
            $filters["cpcodeId"] = $cpCode;
        }

        if ($purpose) {
            $filters["storageGroupPurpose"] = $purpose;
        }

        return $this->_get(
            StorageGroupRepository::class
        )->all($filters);
    }
}