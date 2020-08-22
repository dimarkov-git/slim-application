<?php

declare(strict_types=1);

namespace DImarkov\Application\Test\Unit\Domain\User;

use DImarkov\Application\Domain\User\User;
use JsonException;
use PHPUnit\Framework\TestCase as AbstractTestCase;

/**
 * @internal
 * @coversDefaultClass \DImarkov\Application\Domain\User\User
 */
final class UserTest extends AbstractTestCase
{
    /**
     * @return array[]
     * @psalm-return list<array{0: int, 1: string, 2: string, 3: string}>
     */
    public function userProvider(): array
    {
        return [
            [1, 'bill.gates', 'Bill', 'Gates'],
            [2, 'steve.jobs', 'Steve', 'Jobs'],
            [3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'],
            [4, 'evan.spiegel', 'Evan', 'Spiegel'],
            [5, 'jack.dorsey', 'Jack', 'Dorsey'],
        ];
    }

    /**
     * @dataProvider userProvider
     * @param int $id
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     */
    public function testGetters(int $id, string $username, string $firstName, string $lastName): void
    {
        $user = new User($id, $username, $firstName, $lastName);

        self::assertEquals($id, $user->getId());
        self::assertEquals($username, $user->getUsername());
        self::assertEquals($firstName, $user->getFirstName());
        self::assertEquals($lastName, $user->getLastName());
    }

    public function testPropertyMutations(): void
    {
        $user = new User(100, 'Τάχιστη', 'first', 'last');

        self::assertEquals('τάχιστη', $user->getUsername());
        self::assertEquals('First', $user->getFirstName());
        self::assertEquals('Last', $user->getLastName());
    }

    /**
     * @dataProvider userProvider
     * @param int $id
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @throws JsonException
     */
    public function testJsonSerialize(int $id, string $username, string $firstName, string $lastName): void
    {
        $user = new User($id, $username, $firstName, $lastName);

        $expectedPayload = \json_encode(
            [
                'id' => $id,
                'username' => $username,
                'firstName' => $firstName,
                'lastName' => $lastName,
            ],
            \JSON_THROW_ON_ERROR
        );

        self::assertEquals($expectedPayload, \json_encode($user, \JSON_THROW_ON_ERROR));
    }
}
