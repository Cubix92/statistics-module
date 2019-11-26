<?php

namespace Statistics;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'api' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/v1',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'statistics' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/statistics',
                            'defaults' => [
                                'controller' => Presentation\Api\StatisticsController::class,
                            ]
                        ],
                    ]
                ]
            ],
        ]
    ],
    'console' => [
        'router' => [
            'routes' => [
                'synchronize-advertisers' => [
                    'options' => [
                        'route' => 'synchronize advertisers',
                        'defaults' => [
                            'controller' => Presentation\Console\AdvertiserSynchronizeController::class,
                            'action' => 'synchronize'
                        ],
                    ],
                ],
                'synchronize-campaigns' => [
                    'options' => [
                        'route' => 'synchronize campaigns',
                        'defaults' => [
                            'controller' => Presentation\Console\CampaignSynchronizeController::class,
                            'action' => 'synchronize'
                        ],
                    ],
                ],
                'synchronize-banners' => [
                    'options' => [
                        'route' => 'synchronize banners',
                        'defaults' => [
                            'controller' => Presentation\Console\BannerSynchronizeController::class,
                            'action' => 'synchronize'
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Presentation\Api\StatisticsController::class => Presentation\Factory\StatisticsControllerFactory::class,
            Presentation\Console\AdvertiserSynchronizeController::class => Presentation\Factory\AdvertiserSynchronizeControllerFactory::class,
            Presentation\Console\CampaignSynchronizeController::class => Presentation\Factory\CampaignSynchronizeControllerFactory::class,
            Presentation\Console\BannerSynchronizeController::class => Presentation\Factory\BannerSynchronizeControllerFactory::class
        ],
    ],
    'service_manager' => [
        'factories' => [
            Application\CommandBus::class => Application\Factory\CommandBusFactory::class,
            Application\QueryBus::class => Application\Factory\QueryBusFactory::class,
            Application\Query\ListStatisticsHandler::class => Application\Factory\Query\ListStatisticsHandlerFactory::class,
            Application\Command\SynchronizeAdvertiserHandler::class => Application\Factory\Command\SynchronizeAdvertiserHandlerFactory::class,
            Application\Command\SynchronizeCampaignHandler::class => Application\Factory\Command\SynchronizeCampaignHandlerFactory::class,
            Application\Command\SynchronizeBannerHandler::class => Application\Factory\Command\SynchronizeBannerHandlerFactory::class,
            Application\Command\CreateStatisticsHandler::class => Application\Factory\Command\CreateStatisticsHandlerFactory::class,
            Application\Listener\AuthListener::class => Application\Factory\Listener\AuthListenerFactory::class,
            Domain\Service\AdvertiserServiceInterface::class => Infrastructure\Factory\AdvertiserServiceFactory::class,
            Domain\Service\CampaignServiceInterface::class => Infrastructure\Factory\CampaignServiceFactory::class,
            Domain\Service\BannerServiceInterface::class => Infrastructure\Factory\BannerServiceFactory::class,
            Infrastructure\Service\AuthServiceInterface::class => Infrastructure\Factory\AuthSynchronizeServiceFactory::class,
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Domain']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Domain' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
    ]
];
