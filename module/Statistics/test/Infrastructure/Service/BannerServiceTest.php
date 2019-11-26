<?php

declare(strict_types=1);

namespace StatisticsTest\Application\Command;

use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Domain\Service\BannerServiceInterface;
use Statistics\Domain\Service\CampaignServiceInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BannerServiceTest extends AbstractHttpControllerTestCase
{
    /** @var AdvertiserServiceInterface $advertiserService */
    private $advertiserService;

    /** @var CampaignServiceInterface $campaignService */
    private $campaignService;

    /** @var BannerServiceInterface $bannerService */
    private $bannerService;

    protected function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../../config/application.config.php',
            $configOverrides
        ));

        $this->advertiserService = $this->getApplicationServiceLocator()->get(AdvertiserServiceInterface::class);
        $this->campaignService = $this->getApplicationServiceLocator()->get(CampaignServiceInterface::class);
        $this->bannerService = $this->getApplicationServiceLocator()->get(BannerServiceInterface::class);

        parent::setUp();
    }

    public function testBannerServiceGetDataCorrectly()
    {
        $advertiserRow = current($this->advertiserService->fetchAdvertisers());
        $campaignRow = current($this->campaignService->fetchCampaigns((int) $advertiserRow['clientid']));
        $bannerRow = current($this->bannerService->fetchBanners((int) $campaignRow['campaignid']));

        $this->assertIsString($bannerRow['bannerid']);
        $this->assertIsString($bannerRow['description']);
    }
}