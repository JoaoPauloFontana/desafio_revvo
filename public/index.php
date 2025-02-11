<?php

require_once __DIR__ . '/../vendor/autoload.php';

use RevvoApi\Router;

header('Content-Type: application/json');

$router = new Router();
echo $router->handle();
