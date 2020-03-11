<?php

namespace Akamai\Sdk\Repository\CCU;

use Akamai\Sdk\Model\CCU\Invalidation;
use Akamai\Sdk\Repository\AbstractAkamaiRepository;
use Mr\Bootstrap\Model\BaseModel;

class InvalidationRepository extends AbstractAkamaiRepository
{
    protected $version = 'v3';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Invalidation::class;
    }

    protected function getModelUri(BaseModel $model)
    {
        $resource = $this->getResourcePath();
        $uri = sprintf(
            "%s/%s/%s",
            $resource,
            $model->target,
            $model->network
        );

        if ($model->isNew()) {
            $uri .= "/{$model->id}";
        }
        
        return $uri;
    }

    public function getResourcePath()
    {
        $resource = $this->getResource();

        return sprintf(
            "/ccu/%s/%s",
            $this->version,
            $resource
        );
    }
}
