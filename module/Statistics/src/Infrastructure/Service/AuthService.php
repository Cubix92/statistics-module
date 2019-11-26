<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Service;

use Zend\Http\Client;
use Zend\Json\Json;

class AuthService implements AuthServiceInterface
{
    protected $username;

    protected $password;

    protected $endpoint;

    public function __construct(string $endpoint, string $username, string $password)
    {
        $this->endpoint = $endpoint;
        $this->username = $username;
        $this->password = $password;
    }

    public function getToken(): string
    {
        $client = (new Client)
            ->setMethod('POST')
            ->setAuth($this->username, $this->password)
            ->setUri($this->endpoint);

        $client->send();

        $response = Json::decode($client->getResponse()->getBody());

        if ($response->status !== 'ok') {
            throw new InvalidAuthorizationException('Invalid authorization.');
        }

        return $response->token;
    }
}