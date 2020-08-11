<?php

declare(strict_types=1);

namespace DImarkov\Application\Config\Environment;

use Zakirullin\Mess\Mess;

final class Environment
{
    public function getApplicationEnvironment(): EnvironmentEnum
    {
        return new EnvironmentEnum(
            $this->getEnvironmentStorage()[EnvironmentVariableEnum::APP_ENV()->getValue()]->getAsString()
        );
    }

    private function getEnvironmentStorage(): Mess
    {
        return new Mess($_ENV);
    }
}
