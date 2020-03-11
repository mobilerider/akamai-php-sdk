<?php

namespace Akamai\Sdk\Service;


use Akamai\Sdk\Repository\PAPI\PropertyRepository;
use Mr\Bootstrap\Interfaces\HttpDataClientInterface;
use Mr\Bootstrap\Service\BaseHttpService;
use Akamai\Sdk\Model\PAPI\Contract;
use Akamai\Sdk\Model\PAPI\Group;
use Akamai\Sdk\Repository\PAPI\ContractRepository;
use Assert\Assertion;
use Akamai\Sdk\Repository\PAPI\ProductRepository;
use Akamai\Sdk\Repository\PAPI\GroupRepository;
use Akamai\Sdk\Repository\PAPI\CpCodeRepository;

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

    /**
     * Gropus
     *
     * @var Group[]
     */
    protected $groups;

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

    /**
     * List of groups
     *
     * @param boolean $refresh
     * 
     * @return Group[]
     */
    public function getGroups($refresh = false)
    {
        if (is_null($this->groups) || $refresh) {
            $this->groups = $this->_get(
                GroupRepository::class
            )->all();
        }

        return $this->groups;
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

    public function getProperties($contractId = null, $groupId = null)
    {
        $repository = $this->getRepository(PropertyRepository::class);

        return $repository->all(
            [
                'contractId' => $contractId ?: $this->contractId,
                'groupId' => $groupId ?: $this->groupId
            ]
        );
    }

    public function getProperty($id, $contractId = null, $groupId = null)
    {
        $repository = $this->getRepository(PropertyRepository::class);

        return $repository->get(
            $id,
            [
                'contractId' => $contractId ?: $this->contractId,
                'groupId' => $groupId ?: $this->groupId
            ]
        );
    }

    public function getCpCodes($contractId = null, $groupId = null)
    {
        $repository = $this->getRepository(CpCodeRepository::class);

        return $repository->all(
            [
                'contractId' => $contractId ?: $this->contractId,
                'groupId' => $groupId ?: $this->groupId
            ]
        );
    }

    /**
     * Returns a new instance of CpCode model
     *
     * @param array $data
     * 
     * @return CpCode
     */
    public function createCpCode(array $data, $contractId = null, $groupId = null)
    {
        $contractId = $contractId ?: $this->contractId;
        $groupId = $groupId ?: $this->groupId;

        return $this->_get(
            CpCodeRepository::class, [
                "options" => compact("contractId", "groupId")
            ]
        )->create($data);
    }
}