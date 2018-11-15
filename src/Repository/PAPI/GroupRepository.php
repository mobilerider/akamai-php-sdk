<?php

namespace Akamai\Sdk\Repository\PAPI;

use Akamai\Sdk\Model\PAPI\Group;

class GroupRepository extends PAPIBaseRepository
{
    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Group::class;
    }
}
