<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Service;

use Zend\Http\Client;
use Zend\Json\Json;

abstract class AbstractSynchronizeService
{
    protected $authService;

    protected $endpoint;

    public function __construct(AuthServiceInterface $authService, string $endpoint)
    {
        $this->authService = $authService;
        $this->endpoint = $endpoint;
    }

    protected function fetchData(string $endpoint): array
    {
        $token =  $this->authService->getToken();

        $client = (new Client)
            ->setMethod('GET')
            ->setUri($endpoint);

        $client->setHeaders(['Authorization' => "Bearer {$token}"]);
        $client->send();

        return Json::decode($client->getResponse()->getBody(), Json::TYPE_ARRAY);
    }
}