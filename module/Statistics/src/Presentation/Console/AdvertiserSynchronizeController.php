<?php

declare(strict_types=1);

namespace Statistics\Presentation\Console;

use Statistics\Application\Command\SynchronizeAdvertiserCommand;
use Statistics\Application\CommandBus;
use Statistics\Domain\Service\AdvertiserServiceInterface;
use Zend\Mvc\Console\Controller\AbstractConsoleController;

class AdvertiserSynchronizeController extends AbstractConsoleController
{
    public $commandBus;

    public $advertiserService;

    public function __construct(CommandBus $commandBus, AdvertiserServiceInterface $advertiserService)
    {
        $this->commandBus = $commandBus;
        $this->advertiserService = $advertiserService;
    }

    public function synchronizeAction(): void
    {
        $rows = $this->advertiserService->fetchAdvertisers();

        foreach ($rows as $row) {
            $synchronizeAdvertiserCommand = new SynchronizeAdvertiserCommand((int) $row['clientid'], $row['clientname']);
            $this->commandBus->execute($synchronizeAdvertiserCommand);
        }
    }
}