<?php

/**
 * @noinspection PhpUnusedPrivateFieldInspection
 */

declare(strict_types=1);

namespace DImarkov\Application\Config\Environment;

use MyCLabs\Enum\Enum;

/**
 * @method static self DOCKER_PATH()
 * @method static self APPLICATION_PATH()
 * @method static self APP_ENV()
 *
 * @psalm-immutable
 */
final class EnvironmentVariableEnum extends Enum
{
    // docker
    private const DOCKER_PATH = 'DOCKER_PATH';
    private const APPLICATION_PATH = 'APPLICATION_PATH';

    // application
    private const APP_ENV = 'APP_ENV';

    /**
     * {@inheritdoc}
     */
    public function getValue(): string
    {
        /** @psalm-var string */
        return parent::getValue();
    }
}
