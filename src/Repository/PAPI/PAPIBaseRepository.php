<?php

namespace Akamai\Sdk\Repository\PAPI;

use Akamai\Sdk\Repository\AbstractAkamaiRepository;
use Mr\Bootstrap\Interfaces\HttpDataClientInterface;
use Mr\Bootstrap\Model\BaseModel;
use SebastianBergmann\CodeCoverage\RuntimeException;

abstract class PAPIBaseRepository extends AbstractAkamaiRepository
{
    const PAPI_API_VERSION = 'v1';

    protected $contractId;
    protected $groupId;

    public function __construct(HttpDataClientInterface $client, array $options = [])
    {
        parent::__construct($client, $options);
        
        $this->contractId = $options['contractId'] ?? null;
        $this->groupId = $options['groupId'] ?? null;
    }

    public function getResourcePath()
    {
        $modelClass = $this->getModelClass();

        return sprintf(
            'papi/%s/%s',
            self::PAPI_API_VERSION,
            mr_plural($modelClass::getResource())
        );
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        return $data[mr_plural($this->getResource())]['items'];
    }

    protected function validateDependencies()
    {
        if (! $this->contractId) {
            throw new \RuntimeException("Missing contract ID");
        }

        if (! $this->groupId) {
            throw new \RuntimeException("Missing group ID");
        }
    }

    public function persist(BaseModel $model, array $modifiers = [])
    {
        $this->validateDependencies();

        $modifiers = [
            "contractId" => $this->contractId,
            "groupId" => $this->groupId
        ] + $modifiers;

        return parent::persist($model, $modifiers);
    }
}