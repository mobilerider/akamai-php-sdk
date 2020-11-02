<?php

namespace Akamai\Sdk\Service;

use Akamai\Sdk\Model\CCU\DeleteCCU;
use Akamai\Sdk\Model\CCU\Invalidation;
use Mr\Bootstrap\Service\BaseHttpService;

class FastPurgeService extends BaseHttpService
{
    /**
     * Returns created invalidation
     *
     * @return Invalidation
     */
    public function invalidateByUrl(array $urls)
    {
        $invalidate = $this->_get(
            Invalidation::class
        );
        $invalidate->fill(["objects" => $urls]);
        $invalidate->save();

        return $invalidate;
    }

    /**
     * Returns created CCU delete request
     *
     * @return Invalidation
     */
    public function deleteByUrl(array $urls)
    {
        $deleteCCU = $this->_get(
            DeleteCCU::class
        );
        $deleteCCU->fill(["objects" => $urls]);
        $deleteCCU->save();

        return $deleteCCU;
    }
}