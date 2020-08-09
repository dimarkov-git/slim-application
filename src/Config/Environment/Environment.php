<?php

declare(strict_types=1);

namespace DImarkov\Application\Config\Environment;

final class Environment
{
    public function getEnvironment(): EnvironmentEnum
    {
        return new EnvironmentEnum((string) ($_ENV[EnvironmentVariableEnum::APP_ENV()->getValue()] ?? ''));
    }
}
