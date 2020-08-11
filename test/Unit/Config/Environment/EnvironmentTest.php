<?php

declare(strict_types=1);

namespace DImarkov\Application\Test\Unit\Config\Environment;

use DImarkov\Application\Config\Environment\Environment;
use DImarkov\Application\Config\Environment\EnvironmentEnum;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @internal
 * @coversDefaultClass \DImarkov\Application\Config\Environment\Environment
 */
final class EnvironmentTest extends TestCase
{
    public function testGetEnvironment(): void
    {
        $_ENV['APP_ENV'] = 'test';
        self::assertTrue(EnvironmentEnum::TEST()->equals((new Environment())->getApplicationEnvironment()));
    }

    public function testGetEnvironmentFail(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $_ENV['APP_ENV'] = 500;
        self::assertTrue(EnvironmentEnum::TEST()->equals((new Environment())->getApplicationEnvironment()));
    }

    public function testIsApplicationDebug(): void
    {
        $_ENV['APP_DEBUG'] = 1;
        self::assertTrue((new Environment())->isApplicationDebug());

        $_ENV['APP_DEBUG'] = 'true';
        self::assertTrue((new Environment())->isApplicationDebug());

        $_ENV['APP_DEBUG'] = 0;
        self::assertFalse((new Environment())->isApplicationDebug());

        $_ENV['APP_DEBUG'] = 'false';
        self::assertFalse((new Environment())->isApplicationDebug());
    }
}
