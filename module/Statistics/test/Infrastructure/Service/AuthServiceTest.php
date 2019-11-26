<?php

declare(strict_types=1);

namespace StatisticsTest\Application\Command;

use PHPUnit\Framework\TestCase;
use Statistics\Infrastructure\Service\AuthService;
use Statistics\Infrastructure\Service\InvalidAuthorizationException;

class AuthServiceTest extends TestCase
{
    /** @var string $endpoint */
    private $endpoint;

    /** @var string $username */
    private $username;

    /** @var string $password */
    private $password;

    protected function setUp()
    {
        $config = include __DIR__ . '/../../../../../config/autoload/local.php';

        $host = $config['adserver_synchronization']['host'];
        $this->endpoint = $host . $config['adserver_synchronization']['auth']['endpoint'];
        $this->username = $config['adserver_synchronization']['auth']['username'];
        $this->password = $config['adserver_synchronization']['auth']['password'];

        parent::setUp();
    }

    public function testAuthServiceGetTokenCorrectly()
    {
        $authService = new AuthService($this->endpoint, $this->username, $this->password);

        $token = $authService->getToken();

        $this->assertIsString($token);
    }

    public function testExceptionIsThrownWhenSendInvalidCredentials()
    {
        $authService = new AuthService($this->endpoint, 'invalid_username', 'invalid_password');

        $this->expectException(InvalidAuthorizationException::class);
        $this->expectExceptionMessage('Invalid authorization.');

        $authService->getToken();
    }
}