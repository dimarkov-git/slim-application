<?php

declare(strict_types=1);

namespace DImarkov\Application\Test\Unit\Config\Environment;

use DImarkov\Application\Config\Environment\EnvironmentEnum;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversDefaultClass \DImarkov\Application\Config\Environment\EnvironmentEnum
 */
final class EnvironmentEnumTest extends TestCase
{
    public function testEnum(): void
    {
        self::assertEquals('prod', EnvironmentEnum::PROD()->getValue());
        self::assertEquals('dev', EnvironmentEnum::DEV()->getValue());
        self::assertEquals('test', EnvironmentEnum::TEST()->getValue());
    }
}
