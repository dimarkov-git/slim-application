<?php

declare(strict_types=1);

namespace DImarkov\Application\Application\Actions\User;

use DImarkov\Application\Application\Actions\Action;
use DImarkov\Application\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    /** @var UserRepository */
    protected $userRepository;

    /**
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     */
    public function __construct(LoggerInterface $logger, UserRepository $userRepository)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
    }
}
