<?php

namespace Akamai\Sdk\Repository\MSL\v3;

use Mr\Bootstrap\Interfaces\HttpDataClientInterface;

use Akamai\Sdk\Repository\AbstractAkamaiRepository;
use Akamai\Sdk\Model\MSL\v3\CpCodeMSLv3;

class CpCodeRepositoryMSLv3 extends AbstractAkamaiRepository
{
    protected $version = 'v1';

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return CpCodeMSLv3::class;
    }

    public function getResourcePath()
    {
        return sprintf(
            "/config-media-live/%s/live/utils/cpcode",
            $this->version
        );
    }

    public function parseMany(array $data, array &$metadata = [])
    {
        $reporting = $data['reporting-cpcode-list']['cpcode-list'];

        foreach ($reporting as &$item) {
            $item['__type'] = 'report';
        }

        $netstorage = $data['netstorage-cpcode-list']['cpcode-list'];

        foreach ($netstorage as &$item) {
            $item['__type'] = 'ns';
        }

        return array_merge($reporting, $netstorage);
    }

    /**
     * @inheritDoc
     */
    public function get($id, $modifiers = [], $asArray = false)
    {
        throw new \Exception('Not available');
    }

    /**
     * @inheritDoc
     */
    public function one($filters = [], $asArray = false)
    {
        throw new \Exception('Not available');
    }
}
