<?php

namespace Akamai\Sdk\Service;

use Mr\Bootstrap\Service\BaseHttpService;
use Akamai\Sdk\Model\QOS\ReportPack;
use Akamai\Sdk\Model\QOS\DataStore;
use Akamai\Sdk\Model\QOS\DataSource;
use Akamai\Sdk\Repository\QOS\ReporPackRepository;
use Akamai\Sdk\Repository\QOS\DataSourceRepository;
use Akamai\Sdk\Repository\QOS\DataStoreRepository;

class QosService extends BaseHttpService
{
    const DATE_FORMAT = 'm/d/Y:H:i';

    public function getReportPacks()
    {
        return $this->_get(
            ReportPackRepository::class
        )->all();
    }

    public function getReportPack($id)
    {
        return $this->_get(
            ReportPackRepository::class
        )->get($id);
    }
    public function getDataStores($reportId)
    {
        return $this->_get(
            DataStoreRepository::class
        )->get($reportId);
    }
    public function getDataSources($reportId)
    {
        return $this->_get(
            DataSourceRepository::class
        )->get($reportId);
    }
    public function getData($reportId, \DateTime $startDate, \DateTime $endDate, array $dimensions, array $metrics, array $params = [])
    {
        $repository = $this->getRepository(DataRepository::class);
        $startDate = $this->prepareDateParam($startDate);
        $endDate = $this->prepareDateParam($endDate);
        $dimensions = implode(',', $dimensions);
        $metrics = implode(',', $metrics);

        return $repository->get(
            $reportId,
            [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'dimensions' => $dimensions,
                'metrics' => $metrics,
                'params' => $params,

            ]
        );
    }
    public function prepareDateParam(\DateTime $date)
    {
        $utcTz = new \DateTimeZone('UTC');
        return $date->setTimezone($utcTz)->format(self::DATE_FORMAT);
    }
}
