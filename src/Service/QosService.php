<?php

namespace Akamai\Sdk\Service;

use Mr\Bootstrap\Service\BaseHttpService;
use Akamai\Sdk\Model\QOS\ReportPack;
use Akamai\Sdk\Model\QOS\DataStore;
use Akamai\Sdk\Model\QOS\DataSource;
use Akamai\Sdk\Repository\QOS\ReportPackRepository;
use Akamai\Sdk\Repository\QOS\DataSourceRepository;
use Akamai\Sdk\Repository\QOS\DataStoreRepository;
use Akamai\Sdk\Repository\QOS\DataRepository;

class QosService extends BaseHttpService
{
    public static $reportId = '';

    public function getReportPacks()
    {
        return $this->_get(
            ReportPackRepository::class
        )->all();
    }

    public function getReportPack($id)
    {
        $name = $this->_get(
            ReportPackRepository::class
        )->get($id);
    }

    public function getDataStores($reportId)
    {
        self::$reportId = $reportId;

        $name = $this->_get(
            DataStoreRepository::class,
        );
        return $name->all();
    }

    public function getDataStore($reportId, $storeId)
    {
        self::$reportId = $reportId;

        return $this->_get(
            DataStoreRepository::class
        )->get($storeId);
    }

    public function getDataSources($reportId)
    {
        self::$reportId = $reportId;

        $name = $this->_get(
            DataSourceRepository::class,
        );
        return $name->all();
    }

    public function getData($reportId, $dimensions, $metrics, $startDate, $endDate, array $params = [])
    {
        $repository = $this->getRepository(DataRepository::class);
        
        self::$reportId = $reportId;

        return $repository->all(
            array_merge(
                [
                'dimensions' => $dimensions,
                'metrics' => $metrics,
                'startDate' => $startDate,
                'endDate' => $endDate,
                ],
                $params
            )
        );
    }
}
