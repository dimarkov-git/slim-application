<?php

declare(strict_types=1);

namespace DImarkov\Application\Test\Unit\Config\Environment;

use DImarkov\Application\Config\Environment\EnvironmentVariableEnum;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversDefaultClass \DImarkov\Application\Config\Environment\EnvironmentVariableEnum
 */
final class EnvironmentVariableEnumTest extends TestCase
{
    public function testEnum(): void
    {
        self::assertEquals('APP_ENV', EnvironmentVariableEnum::APP_ENV()->getValue());
    }
}
