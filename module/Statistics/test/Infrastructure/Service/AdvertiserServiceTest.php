<?php

declare(strict_types=1);

namespace StatisticsTest\Application\Command;

use Statistics\Domain\Service\AdvertiserServiceInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AdvertiserServiceTest extends AbstractHttpControllerTestCase
{
    /** @var AdvertiserServiceInterface $advertiserService */
    private $advertiserService;

    protected function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../../config/application.config.php',
            $configOverrides
        ));

        $this->advertiserService = $this->getApplicationServiceLocator()->get(AdvertiserServiceInterface::class);

        parent::setUp();
    }

    public function testAdvertiserServiceGetDataCorrectly()
    {
        $advertiserRow = current($this->advertiserService->fetchAdvertisers());

        $this->assertIsString($advertiserRow['clientid']);
        $this->assertIsString($advertiserRow['clientname']);
    }
}