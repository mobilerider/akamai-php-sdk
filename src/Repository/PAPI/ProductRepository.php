<?php

namespace Akamai\Sdk\Repository\PAPI;

use Mr\Bootstrap\Interfaces\HttpDataClientInterface;
use Mr\Bootstrap\Model\PAPI\Contract;
use Akamai\Sdk\Model\PAPI\Product;

class ProductRepository extends PAPIBaseRepository
{
    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return Product::class;
    }
}
