<?php

declare(strict_types=1);

namespace Statistics\Presentation\Api;

use Statistics\Application\Command\CreateStatisticsCommand;
use Statistics\Domain\Exception\StatisticsDuplicateException;
use Statistics\Application\CommandBus;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class StatisticsController extends AbstractRestfulController
{
    public $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function getList()
    {
        $this->getResponse()->setStatusCode(Response::STATUS_CODE_405);
        return $this->getResponse()->setContent('Method not allowed.');
    }

    public function get($id)
    {
        $this->getResponse()->setStatusCode(Response::STATUS_CODE_405);
        return $this->getResponse()->setContent('Method not allowed.');
    }

    public function create($data)
    {
        $response = [];

        foreach($data as $date => $bannerData) {
            foreach($bannerData as $bannerId => $bannerStats ) {
                $createStatisticsCommand = new CreateStatisticsCommand(
                    new \DateTime($date),
                    $bannerId,
                    $bannerStats['clicks'],
                    $bannerStats['unique_clicks'],
                    $bannerStats['impressions'],
                    $bannerStats['unique_impressions']
                );

                try {
                    $this->commandBus->execute($createStatisticsCommand);

                    $response[$date] = [
                        'status' => 'OK',
                        'message' => null
                    ];
                } catch(StatisticsDuplicateException $e) {
                    $response[$date] = [
                        'status' => 'DUPLICATE',
                        'message' => $e->getMessage()
                    ];
                } catch(\Exception $e) {
                    $response[$date] = [
                        'status' => 'ERROR',
                        'message' => $e->getMessage()
                    ];
                }
            }
        }

        return new JsonModel($response);
    }

    public function update($id, $data)
    {
        $this->getResponse()->setStatusCode(Response::STATUS_CODE_405);
        return $this->getResponse()->setContent('Method not allowed.');
    }

    public function delete($id)
    {
        $this->getResponse()->setStatusCode(Response::STATUS_CODE_405);
        return $this->getResponse()->setContent('Method not allowed.');
    }
}