<?php

declare(strict_types=1);

namespace DImarkov\Application\Domain\User;

use DImarkov\Application\Domain\DomainException\DomainRecordNotFoundException;

class UserNotFoundException extends DomainRecordNotFoundException
{
    /** @var string */
    public $message = 'The user you requested does not exist.';
}
