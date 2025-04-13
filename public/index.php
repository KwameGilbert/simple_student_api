<?php
require __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

// Add routing middleware (if needed)
$app->addRoutingMiddleware();

// Simple error middleware (not for production)
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Load routes from a separate file.
(require __DIR__ . '/src/routes.php')($app);

$app->run();