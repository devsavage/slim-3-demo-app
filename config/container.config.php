<?php

return [
    'settings' => [
      'displayErrorDetails' => true,
      'viewTemplatesDirectory' => '../resources/views',
      'mysql' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'username' => 'dev',
        'password' => 'dev',
        'database' => 'demoapp',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
      ],
      'auth' => [
        'session' => 'user_id',
        'remember' => 'REM_TOKEN',
      ],
    ],

    'user' => function() {
      return new \Savage\Http\Auth\User;
    },

    'validator' => function($c) {
      return new \Savage\Http\Validation\Validator($c['user'], $c['util']);
    },

    'util' => function() {
      return new \Savage\Http\Util\Utils;
    },

    'flash' => function() {
      return new \Slim\Flash\Messages;
    },

    'csrf' => function () {
      return new \Slim\Csrf\Guard;
    },

    'view' => function($c) {
      $view = new \Slim\Views\Twig($c['settings']['viewTemplatesDirectory']);

      $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
      ));

      $view->addExtension(new \Savage\Extension\TwigExtension);

      $view->getEnvironment()->addGlobal('flash', $c['flash']);

      return $view;
    },

    'db' => function($c) {
      $capsule = new Illuminate\Database\Capsule\Manager;

      $capsule->addConnection([
        'driver' => $c['settings']['mysql']['driver'],
        'host' => $c['settings']['mysql']['host'],
        'database' => $c['settings']['mysql']['database'],
        'username' => $c['settings']['mysql']['username'],
        'password' => $c['settings']['mysql']['password'],
        'charset' => $c['settings']['mysql']['charset'],
        'collation' => $c['settings']['mysql']['collation']
      ], 'default');

      return $capsule;
    },
];
