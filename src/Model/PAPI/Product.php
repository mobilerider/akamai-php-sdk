<?php

namespace Akamai\Sdk\Model\PAPI;

class Product extends \Mr\Bootstrap\Model\BaseModel
{
    static protected $idFieldName = 'productId';

    public static function getResource()
    {
        return 'product';
    }
}