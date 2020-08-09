<?php

declare(strict_types=1);

namespace DImarkov\Application\Test\Unit;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ExampleTest extends TestCase
{
    public function testExample(): void
    {
        $a = 2;
        $b = $a * 5;
        self::assertEquals(10, $b);
    }
}
