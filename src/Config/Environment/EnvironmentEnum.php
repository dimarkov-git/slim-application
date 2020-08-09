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
 */
final class EnvironmentEnum extends Enum
{
    private const PROD = 'prod';
    private const DEV = 'dev';
    private const TEST = 'test';

    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function getValue(): string
    {
        return parent::getValue();
    }
}
