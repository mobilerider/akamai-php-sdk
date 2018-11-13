<?php

namespace Akamai\Sdk\Repository;

use Akamai\Sdk\Model\Contract;
use Mr\Bootstrap\Repository\BaseRepository;

abstract class AbstractAkamaiRepository extends BaseRepository
{
    public function parseOne(array $data, array &$metadata = [])
    {
        return $data;
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        return $data[mr_plural($this->getResource())];
    }
}
