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
      'url' => 'http://127.0.0.1/demoapp/public',
    ],

    'user' => function() {
      return new \Savage\Http\Auth\User;
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

    //Error Handling
    'notAllowedHandler' => function($c) {
        return function ($request, $response, $methods) use ($c) {
            return $c['response']
                ->withStatus(405)->withRedirect($c['router']->pathFor('home'));
        };
    },

    'errorHandler' => function($c) {
        return function ($request, $response, $methods) use ($c) {
            return $c['response']->withStatus(500)->withHeader('Content-Type', 'text/html')->write('Something went wrong!');
        };
    },

    'notFoundHandler' => function($c) {
        return function ($request, $response) use ($c) {
            return $c['response']
                ->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->write(str_replace($c['settings']['url'], '', $request->getUri()) . " was not found on this server.");
        };
    },
];
