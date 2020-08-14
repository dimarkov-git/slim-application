<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use DImarkov\Application\Domain\User\UserRepository;
use DImarkov\Application\Infrastructure\Persistence\User\InMemoryUserRepository;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder): void {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions(
        [
            UserRepository::class => autowire(InMemoryUserRepository::class),
        ]
    );
};
