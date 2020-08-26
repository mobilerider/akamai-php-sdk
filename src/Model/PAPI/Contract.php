<?php

namespace Akamai\Sdk\Model\PAPI;

use Akamai\Sdk\Repository\PAPI\ProductRepository;

class Contract extends \Mr\Bootstrap\Model\BaseModel
{
    protected static $idFieldName = 'contractId';

    /**
     * List of products
     *
     * @var Product[]
     */
    protected $products;

    public static function getResource()
    {
        return 'contract';
    }

    public function getProducts()
    {
        if (is_null($this->products)) {
            $this->products = $this->_get(
                ProductRepository::class
            )->all(
                ['contractId' => $this->id]
            );
        }

        return $this->products;
    }
}
