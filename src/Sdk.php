<?php

namespace Akamai\Sdk;

use Akamai\Open\EdgeGrid\Authentication;
use Akamai\Sdk\Http\Client;
use Akamai\Sdk\Model\PAPI\CustomBehavior;
use Akamai\Sdk\Model\PAPI\Group;
use Akamai\Sdk\Model\Stream;
use Akamai\Sdk\Model\MSL\v3\DomainMSLv3;
use Akamai\Sdk\Model\MSL\v3\StreamMSLv3;
use Akamai\Sdk\Model\MSL\v3\EventMSLv3;
use Akamai\Sdk\Repository\PAPI\CustomBehaviorRepository;
use Akamai\Sdk\Repository\PAPI\GroupRepository;
use Akamai\Sdk\Repository\StreamRepository;
use Akamai\Sdk\Service\PAPIService;
use GuzzleHttp\HandlerStack;
use Mr\Bootstrap\Container;
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
use Akamai\Sdk\Repository\MSL\v3\CpCodeRepositoryMSLv3;
use Akamai\Sdk\Model\MSL\v3\CpCodeMSLv3;


/**
 * SDK
 * 
 * @method static PAPIService      getPAPIService($contractId = null, $groupId = null)
 * @method static MSLv3Service     getMSLv3Service()
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
        $auth = new Authentication;
        $auth->setAuth($token, $secret, $accessToken);

        // Create default handler with all the default middlewares
        $stack = HandlerStack::create();

        $httpDefaultOptions = [
            'base_uri' => $host,
            'handler' => $stack,
            'timeout' => '60.0'
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
            'http_rest_client' => [
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
                    'client' => \mr_srv_arg('http_rest_client'),
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
            // Repositories
            ContractRepository::class => [
                'single' => true,
                'class' => ContractRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_rest_client'),
                    'options' => $repositoryOptions
                ]
            ],
            ProductRepository::class => [
                'single' => true,
                'class' => ProductRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_rest_client'),
                    'options' => $repositoryOptions
                ]
            ],
            StreamRepository::class => [
                'single' => true,
                'class' => StreamRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_rest_client'),
                    'options' => $repositoryOptions
                ]
            ],
            GroupRepository::class => [
                'single' => true,
                'class' => GroupRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_rest_client'),
                    'options' => $repositoryOptions
                ]
            ],
            CustomBehaviorRepository::class => [
                'single' => true,
                'class' => CustomBehaviorRepository::class,
                'arguments' => [
                    'client' => \mr_srv_arg('http_rest_client'),
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
            Stream::class => [
                'single' => false,
                'class' => Stream::class,
                'arguments' => [
                    'repository' => \mr_srv_arg(StreamRepository::class),
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
            ]
        ];

        $this->container = new Container($definitions);
    }

    protected static function create($host, $token, $secret, $accessToken, array $httpOptions = [])
    {
        self::$instance = new self($host, $token, $secret, $accessToken, $httpOptions);
    }

    public static function setCredentials($host, $token, $secret, $accessToken, 
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

    public static function __callStatic($name, $arguments)
    {
        $instance = self::getInstance();

        $name = '_' . $name;

        return call_user_func_array([$instance, $name], $arguments);
    }

    protected function _getPAPIService($contractId = null, $groupId = null)
    {
        return $this->_get(PAPIService::class, compact('contractId', 'groupId'));
    }

    protected function _getMSLv3Service($contractId = null, $groupId = null)
    {
        return $this->_get(MSLv3Service::class);
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
