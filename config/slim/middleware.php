<?php

declare(strict_types=1);

use DImarkov\Application\Application\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app): void {
    $app->add(SessionMiddleware::class);
};
