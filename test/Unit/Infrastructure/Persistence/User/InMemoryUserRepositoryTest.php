<?php

declare(strict_types=1);

namespace DImarkov\Application\Test\Unit\Infrastructure\Persistence\User;

use DImarkov\Application\Domain\User\User;
use DImarkov\Application\Domain\User\UserNotFoundException;
use DImarkov\Application\Infrastructure\Persistence\User\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as AbstractTestCase;

/**
 * @internal
 * @coversNothing
 */
final class InMemoryUserRepositoryTest extends AbstractTestCase
{
    public function testFindAll(): void
    {
        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $userRepository = new InMemoryUserRepository([1 => $user]);

        self::assertEquals([$user], $userRepository->findAll());
    }

    public function testFindAllUsersByDefault(): void
    {
        $users = [
            1 => new User(1, 'bill.gates', 'Bill', 'Gates'),
            2 => new User(2, 'steve.jobs', 'Steve', 'Jobs'),
            3 => new User(3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
            4 => new User(4, 'evan.spiegel', 'Evan', 'Spiegel'),
            5 => new User(5, 'jack.dorsey', 'Jack', 'Dorsey'),
        ];

        $userRepository = new InMemoryUserRepository();

        self::assertEquals(\array_values($users), $userRepository->findAll());
    }

    public function testFindUserOfId(): void
    {
        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $userRepository = new InMemoryUserRepository([1 => $user]);

        self::assertEquals($user, $userRepository->findUserOfId(1));
    }

    public function testFindUserOfIdThrowsNotFoundException(): void
    {
        $userRepository = new InMemoryUserRepository([]);
        $this->expectException(UserNotFoundException::class);
        $userRepository->findUserOfId(1);
    }
}
