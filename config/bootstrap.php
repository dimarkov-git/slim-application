<?php

declare(strict_types=1);

use DImarkov\Application\Config\Environment\EnvironmentEnum;
use DImarkov\Application\Config\Environment\EnvironmentVariableEnum;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

function initializeEnvironments(): Dotenv
{
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
    $dotenv->required(EnvironmentVariableEnum::APP_ENV()->getValue())->allowedValues(EnvironmentEnum::values());

    return $dotenv;
}

initializeEnvironments();
