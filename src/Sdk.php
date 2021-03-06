<?php

namespace Akamai\Sdk;

use Mr\Bootstrap\Container;

use Akamai\Open\EdgeGrid\Authentication;
use Akamai\Sdk\Http\Client;
use Akamai\Sdk\Model\PAPI\CustomBehavior;
use Akamai\Sdk\Model\PAPI\Group;
use Akamai\Sdk\Model\MSL\Stream;
use Akamai\Sdk\Model\MSL\v3\DomainMSLv3;
use Akamai\Sdk\Model\MSL\v3\StreamMSLv3;
use Akamai\Sdk\Model\MSL\v3\EventMSLv3;
use Akamai\Sdk\Repository\PAPI\CustomBehaviorRepository;
use Akamai\Sdk\Repository\PAPI\GroupRepository;
use Akamai\Sdk\Repository\MSL\StreamRepository;
use Akamai\Sdk\Service\PAPIService;
use GuzzleHttp\HandlerStack;
use Mr\Bootstrap\Data\JsonEncoder;
use Mr\Bootstrap\Interfaces\ContainerAccessorInterface;
use Mr\Bootstrap\Traits\ContainerAccessor;
use Akamai\Sdk\Repository\MSL\v3\StreamRepositoryMSLv3;
use Akamai\Sdk\Repository\MSL\v3\DomainRepositoryMSLv3;
use Akamai\Sdk\Repository\MSL\v3\EventRepositoryMSLv3;
use Mr\Bootstrap\Data\XmlEncoder;
use Akamai\Sdk\Service\MSLv3Service;
use Akamai\Sdk\Repository\PAPI\ContractRepository;
use Akamai\Sdk\Model\PAPI\Contract;
use Akamai\Sdk\Repository\PAPI\ProductRepository;
use Akamai\Sdk\Model\PAPI\Product;
use Akamai\Sdk\Http\AkamaiQueryBuilder;
use Akamai\Sdk\Model\CCU\DeleteCCU;
use Akamai\Sdk\Model\CCU\Invalidation;
use Akamai\Sdk\Repository\MSL\v3\CpCodeRepositoryMSLv3;
use Akamai\Sdk\Model\MSL\v3\CpCodeMSLv3;
use Akamai\Sdk\Service\MSLService;
use Akamai\Sdk\Repository\NS\UploadAccountRepository;
use Akamai\Sdk\Model\NS\UploadAccount;
use Akamai\Sdk\Service\NSService;
use Akamai\Sdk\Repository\NS\StorageGroupRepository;
use Akamai\Sdk\Model\NS\StorageGroup;
use Akamai\Sdk\Repository\MSL\CpCodeRepository;
use Akamai\Sdk\Model\MSL\CpCode;
use Akamai\Sdk\Repository\MSL\MSLContractRepository;
use Akamai\Sdk\Model\MSL\MSLContract;
use Akamai\Sdk\Repository\MSL\OriginRepository;
use Akamai\Sdk\Model\MSL\Origin;
use Akamai\Sdk\Repository\PAPI\PropertyRepository;
use Akamai\Sdk\Repository\PAPI\CpCodeRepository as PAPICpCodeRepository;
use Akamai\Sdk\Model\PAPI\Property;
use Akamai\Sdk\Model\PAPI\CpCode as PAPICpCode;
use Akamai\Sdk\Repository\CCU\InvalidationRepository;
use Akamai\Sdk\Service\FastPurgeService;
use Akamai\Sdk\Service\QosService;
use Akamai\Sdk\Repository\QOS\DataRepository;
use Akamai\Sdk\Repository\QOS\DataStoreRepository;
use Akamai\Sdk\Repository\QOS\DataSourceRepository;
use Akamai\Sdk\Repository\QOS\ReportPackRepository;
use Akamai\Sdk\Repository\QOS\QOSRepository;
use Akamai\Sdk\Model\QOS\Data;
use Akamai\Sdk\Model\QOS\DataSource;
use Akamai\Sdk\Model\QOS\DataReportPack;
use Akamai\Sdk\Model\QOS\DataStore;
use Akamai\Sdk\Model\QOS\ReportPack;
use Akamai\Sdk\Repository\CCU\DeleteCCURepository;

/**
 * SDK
 *
 * @method static PAPIService      getPAPIService($contractId = null, $groupId = null)
 * @method static NSService        getNSService()
 * @method static MSLService       getMSLService()
 * @method static MSLv3Service     getMSLv3Service()
 * @method static QosService       getQosService()
 * @method static FastPurgeService getFastPurgeService()
 * @method static Contract[]       getContracts
 * @method static Stream[]         getStreams
 * @method static Group[]          getGroups
 * @method static CustomBehavior[] getCustomBehaviors
 *
 * Class Sdk
 * @package AwsElemental\Sdk
 */
class Sdk implements ContainerAccessorInterface
{
    use ContainerAccessor;

    const API_VERSION = 'v1';

    private static $instance;


    private function __construct($host, $token, $secret, $accessToken, array $httpOptions = [])
    {
        $auth = new Authentication();
        $auth->setAuth($token, $secret, $accessToken);

        // Create default handler with all the default middlewares
        $stack = HandlerStack::create();

        $httpDefaultOptions = [
            'base_uri' => $host,
            // 'handler' => $stack,
            'timeout' => '120.0',
            'debug' => true
        ];

        $httpRestOptions = $httpDefaultOptions + array_merge_recursive(
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ],
            $httpOptions
        );

        $httpXmlOptions = $httpDefaultOptions + array_merge_recursive(
            [
                'headers' => [
                    'Accept' => 'application/xml',
                    'Content-Type' => 'application/xml'
                ]
            ],
            $httpOptions
        );

        $repositoryOptions = [
            'queryBuilderClass' => AkamaiQueryBuilder::class
        ];

        $definitions = [
            JsonEncoder::class => [
                'single' => false,
                'class' => JsonEncoder::class
            ],
            XmlEncoder::class => [
                'single' => false,
                'class' => XmlEncoder::class
            ],
            'http_json_client' => [
                'single' => true,
                'class' => Client::class,
                'arguments' => [
                    $httpRestOptions,
                    $auth,
                    \mr_srv_arg(JsonEncoder::class)
                ]
            ],
            'http_xml_client' => [
                'single' => true,
                'class' => Client::class,
                'arguments' => [
                    $httpXmlOptions,
                    $auth,
                    \mr_srv_arg(XmlEncoder::class)
                ]
            ],
            // Services
            PAPIService::class => [
                'single' => true,
                'class' => PAPIService::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => []
                ]
            ],
            MSLService::class => [
                'single' => true,
                'class' => MSLService::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_xml_client'),
                    'options' => []
                ]
            ],
            MSLv3Service::class => [
                'single' => true,
                'class' => MSLv3Service::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_xml_client'),
                    'options' => []
                ]
            ],
            NSService::class => [
                'single' => true,
                'class' => NSService::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => []
                ]
            ],
            FastPurgeService::class => [
                'single' => true,
                'class' => FastPurgeService::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => []
                ]
            ],
            QosService::class => [
                'single' => true,
                'class' => QosService::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => []
                ]
            ],
            // Repositories
            ContractRepository::class => [
                'single' => true,
                'class' => ContractRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            ProductRepository::class => [
                'single' => true,
                'class' => ProductRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            PropertyRepository::class => [
                'single' => true,
                'class' => PropertyRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            PAPICpCodeRepository::class => [
                'single' => true,
                'class' => PAPICpCodeRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            // MSL
            StreamRepository::class => [
                'single' => true,
                'class' => StreamRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            CpCodeRepository::class => [
                'single' => true,
                'class' => CpCodeRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            MSLContractRepository::class => [
                'single' => true,
                'class' => MSLContractRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            OriginRepository::class => [
                'single' => true,
                'class' => OriginRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            GroupRepository::class => [
                'single' => true,
                'class' => GroupRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            CustomBehaviorRepository::class => [
                'single' => true,
                'class' => CustomBehaviorRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            DomainRepositoryMSL::class => [
                'single' => true,
                'class' => DomainRepositoryMSL::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            DomainRepositoryMSLv3::class => [
                'single' => true,
                'class' => DomainRepositoryMSLv3::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_xml_client'),
                    'options' => $repositoryOptions
                ]
            ],
            CpCodeRepositoryMSLv3::class => [
                'single' => true,
                'class' => CpCodeRepositoryMSLv3::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_xml_client'),
                    'options' => $repositoryOptions
                ]
            ],
            StreamRepositoryMSLv3::class => [
                'single' => false,
                'class' => StreamRepositoryMSLv3::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_xml_client'),
                    'options' => $repositoryOptions
                ]
            ],
            EventRepositoryMSLv3::class => [
                'single' => false,
                'class' => EventRepositoryMSLv3::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_xml_client'),
                    'options' => $repositoryOptions
                ]
            ],
            UploadAccountRepository::class => [
                'single' => false,
                'class' => UploadAccountRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            StorageGroupRepository::class => [
                'single' => false,
                'class' => StorageGroupRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            // CCU
            InvalidationRepository::class => [
                'single' => true,
                'class' => InvalidationRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            DeleteCCURepository::class => [
                'single' => true,
                'class' => DeleteCCURepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            //QOS
            QOSRepository::class => [
                'single' => true,
                'class' => QOSRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            ReportPackRepository::class => [
                'single' => true,
                'class' => ReportPackRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            DataSourceRepository::class => [
                'single' => true,
                'class' => DataSourceRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            DataRepository::class => [
                'single' => true,
                'class' => DataRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            DataStoreRepository::class => [
                'single' => true,
                'class' => DataStoreRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_json_client'),
                    'options' => $repositoryOptions
                ]
            ],
            // Models
            Contract::class => [
                'single' => false,
                'class' => Contract::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(ContractRepository::class),
                    'data' => []
                ]
            ],
            Product::class => [
                'single' => false,
                'class' => Product::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(ProductRepository::class),
                    'data' => []
                ]
            ],
            Property::class => [
                'single' => false,
                'class' => Property::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(PropertyRepository::class),
                    'data' => []
                ]
            ],
            PAPICpCode::class => [
                'single' => false,
                'class' => PAPICpCode::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(PAPICpCodeRepository::class),
                    'data' => []
                ]
            ],
            // MSL
            Stream::class => [
                'single' => false,
                'class' => Stream::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(StreamRepository::class),
                    'data' => []
                ]
            ],
            CpCode::class => [
                'single' => false,
                'class' => CpCode::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(CpCodeRepository::class),
                    'data' => []
                ]
            ],
            MSLContract::class => [
                'single' => false,
                'class' => MSLContract::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(MSLContractRepository::class),
                    'data' => []
                ]
            ],
            Origin::class => [
                'single' => false,
                'class' => Origin::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(OriginRepository::class),
                    'data' => []
                ]
            ],
            Group::class => [
                'single' => false,
                'class' => Group::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(GroupRepository::class),
                    'data' => []
                ]
            ],
            CustomBehavior::class => [
                'single' => false,
                'class' => CustomBehavior::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(CustomBehaviorRepository::class),
                    'data' => []
                ]
            ],
            DomainMSLv3::class => [
                'single' => false,
                'class' => DomainMSLv3::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(DomainRepositoryMSLv3::class),
                    'data' => []
                ]
            ],
            CpCodeMSLv3::class => [
                'single' => false,
                'class' => CpCodeMSLv3::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(CpCodeRepositoryMSLv3::class),
                    'data' => []
                ]
            ],
            StreamMSLv3::class => [
                'single' => false,
                'class' => StreamMSLv3::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(StreamRepositoryMSLv3::class),
                    'data' => []
                ]
            ],
            EventMSLv3::class => [
                'single' => false,
                'class' => EventMSLv3::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(EventRepositoryMSLv3::class),
                    'data' => []
                ]
            ],
            UploadAccount::class => [
                'single' => false,
                'class' => UploadAccount::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(UploadAccountRepository::class),
                    'data' => []
                ]
            ],
            StorageGroup::class => [
                'single' => false,
                'class' => StorageGroup::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(StorageGroupRepository::class),
                    'data' => []
                ]
            ],
            //CCU
            Invalidation::class => [
                'single' => false,
                'class' => Invalidation::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(InvalidationRepository::class),
                    'data' => []
                ]
            ],
            DeleteCCU::class => [
                'single' => false,
                'class' => DeleteCCU::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(DeleteCCURepository::class),
                    'data' => []
                ]
            ],
            //QOS
            Data::class => [
                    'single' => false,
                    'class' => Data::class,
                    'arguments' => [
                        'repository' => \mr_srv_arg(DataRepository::class),
                        'data' => []
                    ]
            ],
            DataSource::class => [
                'single' => false,
                'class' => DataSource::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(DataSourceRepository::class),
                    'data' => []
                ]
            ],
            DataStore::class => [
                'single' => false,
                'class' => DataStore::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(QOSRepository::class),
                    'data' => []
                ]
            ],
            ReportPack::class => [
                'single' => false,
                'class' => ReportPack::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(ReportPackRepository::class),
                    'data' => []
                ]
            ],
        ];

        $this->container = new Container($definitions);
    }

    protected static function create($host, $token, $secret, $accessToken, array $httpOptions = [])
    {
        self::$instance = new self($host, $token, $secret, $accessToken, $httpOptions);
    }

    public static function setCredentials(
        $host,
        $token,
        $secret,
        $accessToken,
        array $httpOptions = []
    ) {
        self::create($host, $token, $secret, $accessToken, $httpOptions);
    }

    protected static function getInstance()
    {
        if (!self::$instance) {
            throw new \RuntimeException('You need to set credentials');
        }

        return self::$instance;
    }

    /**
     * Return SDK global container
     *
     * @return Container
     */
    public static function getGlobalContainer()
    {
        return self::getInstance()->getContainer();
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = self::getInstance();

        $name = '_' . $name;

        return call_user_func_array([$instance, $name], $arguments);
    }

    protected function _getPAPIService($contractId = null, $groupId = null)
    {
        return $this->_get(
            PAPIService::class,
            ["options" => compact('contractId', 'groupId')]
        );
    }

    protected function _getMSLv3Service()
    {
        return $this->_get(MSLv3Service::class);
    }

    protected function _getMSLService()
    {
        return $this->_get(MSLService::class);
    }

    protected function _getNSService()
    {
        return $this->_get(NSService::class);
    }

    protected function _getFastPurgeService()
    {
        return $this->_get(FastPurgeService::class);
    }
    protected function _getQosService()
    {
        return $this->_get(QosService::class);
    }

    /**
     * @return Contract[]
     */
    protected function _getContracts()
    {
        return $this->_get(ContractRepository::class)->all();
    }

    /**
     * @return Stream[]
     */
    protected function _getStreams()
    {
        return $this->_get(StreamRepository::class)->all();
    }

    protected function _getGroups()
    {
        return $this->_get(GroupRepository::class)->all();
    }

    protected function _getCustomBehaviors()
    {
        return $this->_get(CustomBehaviorRepository::class)->all();
    }
}
