<?php

declare(strict_types=1);

namespace StatisticsTest\Application\Command;

use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Domain\Service\CampaignServiceInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class CampaignServiceTest extends AbstractHttpControllerTestCase
{
    /** @var AdvertiserServiceInterface $advertiserService */
    private $advertiserService;

    /** @var CampaignServiceInterface $campaignService */
    private $campaignService;

    protected function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../../config/application.config.php',
            $configOverrides
        ));

        $this->advertiserService = $this->getApplicationServiceLocator()->get(AdvertiserServiceInterface::class);
        $this->campaignService = $this->getApplicationServiceLocator()->get(CampaignServiceInterface::class);

        parent::setUp();
    }

    public function testAdvertiserServiceGetDataCorrectly()
    {
        $advertiserRow = current($this->advertiserService->fetchAdvertisers());
        $campaignRow = current($this->campaignService->fetchCampaigns((int) $advertiserRow['clientid']));

        $this->assertIsString($campaignRow['campaignid']);
        $this->assertIsString($campaignRow['campaignname']);
    }
}