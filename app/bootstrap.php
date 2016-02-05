<?php
session_start();
define('INC_ROOT', dirname(__DIR__));

require_once INC_ROOT . '/vendor/autoload.php';

$site = new \Savage\Site(new \Slim\Container(
    include '../config/container.config.php'
));

$container = $site->getContainer();

$container['validator'] = function($c) use ($site) {
    return new \Savage\Http\Validation\Validator($c['user'], $c['util'], $site->auth);
};

$site->add(new \Savage\Http\Middleware\AuthMiddleware($site));
$site->add(new \Savage\Http\Middleware\CsrfMiddleware($site));
$site->add($site->getContainer()->csrf);

require 'routes.php';

$site->getContainer()->db->bootEloquent();

$site->auth = false;
