<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Service;

interface AuthServiceInterface
{
    public function getToken(): string;
}