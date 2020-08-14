<?php

declare(strict_types=1);

namespace DImarkov\Application\Domain\User;

use JsonSerializable;

class User implements JsonSerializable
{
    /** @var null|int */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /**
     * @param null|int $id
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(?int $id, string $username, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->username = \mb_strtolower($username);
        $this->firstName = \ucfirst($firstName);
        $this->lastName = \ucfirst($lastName);
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
