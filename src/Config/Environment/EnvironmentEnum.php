<?php

/**
 * @noinspection PhpUnusedPrivateFieldInspection
 */

declare(strict_types=1);

namespace DImarkov\Application\Config\Environment;

use MyCLabs\Enum\Enum;

/**
 * @method static self PROD()
 * @method static self DEV()
 * @method static self TEST()
 *
 * @psalm-immutable
 * @extends Enum<string>
 */
final class EnvironmentEnum extends Enum
{
    private const PROD = 'prod';
    private const DEV = 'dev';
    private const TEST = 'test';

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getValue(): string
    {
        return (string) parent::getValue();
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-pure
     * @psalm-return array<string, string>
     */
    public static function toArray(): array
    {
        /** @psalm-var array<string, string> */
        return parent::toArray();
    }
}
