<?php

namespace Akamai\Sdk\Http;

use Mr\Bootstrap\Data\JsonEncoder;
use Mr\Bootstrap\Interfaces\HttpDataClientInterface;
use Mr\Bootstrap\Traits\HttpDataClient;
use Mr\Bootstrap\Interfaces\ArrayEncoder;

class Client extends \Akamai\Open\EdgeGrid\Client implements HttpDataClientInterface
{
    use HttpDataClient;

    public function __construct(
        array $config = [], $authentication = null, ArrayEncoder $encoder = null
    ) {
        parent::__construct($config, $authentication);

        if (! $encoder) {
            $encoder = new JsonEncoder();
        }

        $this->setDataEncoder($encoder);
    }
}