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
class EnvironmentEnum extends Enum
{
    private const PROD = 'prod';
    private const DEV = 'dev';
    private const TEST = 'test';

    public function getValue(): string
    {
        return (string) parent::getValue();
    }
}
