<?php

declare(strict_types=1);

namespace Statistics\Application\Factory\Command;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Statistics\Application\Command\SynchronizeAdvertiserHandler;
use Statistics\Domain\Model\Advertiser;
use Statistics\Domain\Repository\AdvertiserRepository;
use Zend\ServiceManager\Factory\FactoryInterface;

class SynchronizeAdvertiserHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SynchronizeAdvertiserHandler
    {
        /** @var AdvertiserRepository $advertiserRepository */
        $advertiserRepository = $container->get(EntityManager::class)->getRepository(Advertiser::class);

        return new SynchronizeAdvertiserHandler($advertiserRepository);
    }
}