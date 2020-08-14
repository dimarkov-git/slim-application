<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions(
        [
            LoggerInterface::class => function (ContainerInterface $c): LoggerInterface {
                /** @var array $settings */
                $settings = $c->get('settings');

                /** @var array $loggerSettings */
                $loggerSettings = $settings['logger'];
                $logger = new Logger((string) $loggerSettings['name']);

                $processor = new UidProcessor();
                $logger->pushProcessor($processor);

                $handler = new StreamHandler((string) $loggerSettings['path'], (int) $loggerSettings['level']);
                $logger->pushHandler($handler);

                return $logger;
            },
        ]
    );
};
