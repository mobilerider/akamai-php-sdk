<?php

namespace Akamai\Sdk\Service;


use Akamai\Sdk\Repository\PAPI\PropertyRepository;
use Mr\Bootstrap\Interfaces\HttpDataClientInterface;
use Mr\Bootstrap\Service\BaseHttpService;
use Akamai\Sdk\Model\PAPI\Contract;
use Akamai\Sdk\Repository\PAPI\ContractRepository;
use Assert\Assertion;
use Akamai\Sdk\Repository\PAPI\ProductRepository;

class PAPIService extends BaseHttpService
{
    protected $contractId;
    protected $groupId;

    /**
     * Contracts
     *
     * @var Contract[]
     */
    protected $contracts;

    public function __construct(HttpDataClientInterface $client, array $options = [])
    {
        parent::__construct($client, $options);

        $this->contractId = $options['contractId'] ?? null;
        $this->groupId = $options['groupId'] ?? null;
    }

    /**
     * List of contracts
     *
     * @param boolean $refresh
     * 
     * @return Contract[]
     */
    public function getContracts($refresh = false)
    {
        if (is_null($this->contracts) || $refresh) {
            $this->contracts = $this->_get(
                ContractRepository::class
            )->all();
        }

        return $this->contracts;
    }

    public function getProducts($contractId = null)
    {
        $contractId = $contractId ?? $this->contractId;

        Assertion::notEmpty($contractId, 'Contract id cannot be empty');

        return $this->_get(
            ProductRepository::class
        )->all(
            ['contractId' => $contractId]
        );
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