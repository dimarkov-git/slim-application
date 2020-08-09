<?php

declare(strict_types=1);

namespace DImarkov\Application\Test\Unit\Config\PhpCsFixer;

use DImarkov\Application\Config\PhpCsFixer\ProjectRuleSet;
use Ergebnis\PhpCsFixer\Config\RuleSet\Php73;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \DImarkov\Application\Config\PhpCsFixer\ProjectRuleSet
 */
final class ProjectRuleSetTest extends TestCase
{
    public function testName(): void
    {
        self::assertEquals('Project rule set (PHP 7.4)', $this->createProjectRuleSet()->name());
    }

    public function testRules(): void
    {
        self::assertEquals((new Php73())->rules(), $this->createProjectRuleSet()->rules());
    }

    public function testTargetPhpVersion(): void
    {
        self::assertEquals('70400', $this->createProjectRuleSet()->targetPhpVersion());
    }

    private function createProjectRuleSet(): ProjectRuleSet
    {
        return new ProjectRuleSet();
    }
}
