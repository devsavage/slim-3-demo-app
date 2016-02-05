<?php
session_start();
define('INC_ROOT', dirname(__DIR__));

require_once INC_ROOT . '/vendor/autoload.php';

$site = new \Savage\Site(new \Slim\Container(
  include '../config/container.config.php'
));

$site->add($site->getContainer()->csrf);
$site->add(new \Savage\Http\Middleware\CsrfMiddleware($site));

require 'routes.php';

$site->getContainer()->db->bootEloquent();