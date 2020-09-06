<?php

namespace Akamai\Sdk\Model\NS;

use Exception;

class UploadAccount extends \Mr\Bootstrap\Model\BaseModel
{
    protected static $idFieldName = "uploadAccountId";

    public static function getResource()
    {
        return 'upload-account';
    }

    public function isNew()
    {
        return !$this->has('lastModifiedDate');
    }

    public function isReady()
    {
        return $this->getStatus() == "ACTIVE" && !$this->isPending();
    }

    public function isPending()
    {
        return $this->hasPendingPropagation;
    }

    public function getStatus()
    {
        return $this->uploadAccountStatus;
    }

    public function getFTPKeys()
    {
        return isset($this->keys["ftp"]) && count($this->keys["ftp"]) 
            ? $this->keys["ftp"] : [];
    }

    public function setStorageGroup(StorageGroup $storageGroup, $readOnly = false)
    {
        $this->storageGroupId = $storageGroup->id();

        $cpCodes = $storageGroup->getStorageCpCodes();
        $rootDir = "/";

        if ($cpCodes) {
            $cpCode = $cpCodes[0];
            $rootDir .= $cpCode->id();
        }

        $this->accessConfig = [
            'chrootDirectory' => $rootDir,
            'loginDirectory' => $rootDir,
            'hasReadOnlyAccess' => $readOnly,
            "cpcodes" => array_map(
                function ($c) {
                    return [
                        "cpcodeId" => $c->id()
                    ];
                },
                $cpCodes
            )
        ];
    }

    public function addSshKey($key, $comments = "Added ssh key")
    {
        if (!$key) {
            throw new Exception("Key cannot be empty");
        }

        $key = trim($key);

        $keys = $this->keys ?: [];
        $sshKeys = $keys["ssh"] ?? [];
        $sshKeys[] = compact("key", "comments");
        $keys["ssh"] = $sshKeys;

        $this->keys = $keys;
    }

    public function addFtpCredentials(
        $username,
        $password,
        $comments = "Added ftp credentials"
    ) {
        if (!$username || !$password) {
            throw new Exception("Username or password cannot be empty");
        }

        $keys = $this->keys ?: [];

        $keys["ftp"] = [
            $username => $password,
            "comments" => $comments
        ];

        $this->keys = $keys;
    }
}