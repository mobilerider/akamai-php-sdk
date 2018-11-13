<?php

namespace Akamai\Sdk\Service;


use Akamai\Sdk\Repository\PAPI\PropertyRepository;
use Mr\Bootstrap\Interfaces\HttpDataClientInterface;
use Mr\Bootstrap\Service\BaseHttpService;

class PAPIService extends BaseHttpService
{
    protected $contractId;
    protected $groupId;

    public function __construct(HttpDataClientInterface $client, array $options = [])
    {
        parent::__construct($client, $options);

        $this->contractId = $options['contractId'] ?? null;
        $this->groupId = $options['groupId'] ?? null;
    }

    public function getContracts()
    {
        return $this->getRepository(Contra::class);
    }

    public function getProperties($filters)
    {
        $repository = $this->getRepository(PropertyRepository::class);

        $fq = $repository->resolveFilterQuery($filters);

        if ($this->contractId) {
            $fq->where('contractId', $this->contractId);
        }

        if ($this->groupId) {
            $fq->where('groupId', $this->groupId);
        }

        return $repository->all($fq);
    }
}