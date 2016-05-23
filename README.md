# Slim 3 Demo App
Well, basically this project is a basic Slim 3 application with authentication.   
I am using Eloquent ORM for the database implementation. You can read more about Eloquent ORM [here](https://laravel.com/docs/master/eloquent).  
I will continue to add more features as I see fit, soon.


## Current and Planned Features
| Feature        | Description           | Status  |
| ------------- |:-------------:| -----:|
| CSRF Protection      | Cross-Site Request Forgery protection. | Feature Complete |
| Authentication      | A fully featured authentication system. | Feature Complete |
| Direct Messaging      | A simple way to message other users. | In Progress |

## Installation
You will need to use [Composer](https://getcomposer.org/) as well as PHP 5.5 or newer.  
Then run the following in the folder **composer.json** is located.
```bash
$ composer install
```
+ This will install all of the required dependencies as well as autoload the other necessary files.  
+ You will need to update the config file to suit your needs.  You can find the config file in **config/container.config.php**  
+ You will also need to create the databases. After you setup your database info in the config, you can run the SQL files in the sql folder.  
+ Once your database is set up, you can register for an account on the site.  
+ You will manually need to give yourself administrator privileges by using a database manager, such as phpMyAdmin, and updating: **permissions->is_head_admin** and set it to **1**.  

## Configurartion
Here is an example configuration file. You will want to place this file in a **config** folder as a php file. I recommend naming it **container.config.php** so you won't need to update anything in the bootstrap file.  
You will need to update **app/bootstrap.php** with the location to your config file within the instantiation of the **Slim/App** class.  

```php
<?php

return [
    'settings' => [
      'displayErrorDetails' => true,
      'viewTemplatesDirectory' => '../resources/views',
      'mysql' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
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

    'directMessage' => function() {
      return new \Savage\Http\Auth\User\UserDirectMessages;
    },

    'directMessageResponse' => function() {
      return new \Savage\Http\Auth\User\UserDiretMessageResponses;
    },

    'util' => function() {
      return new \Savage\Http\Util\Utils;
    },

    'flash' => function() {
      return new \Slim\Flash\Messages;
    },

    'search' => function() {
		// Application ID & Admin API Key
        return new \AlgoliaSearch\Client("••••••••••", "••••••••••••••••••••••••••••••••");
    },

    'view' => function($c) {
      $view = new \Slim\Views\Twig($c['settings']['viewTemplatesDirectory'], [
          'debug' => true,
      ]);

      $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
      ));

      $view->addExtension(new \Savage\Extension\TwigExtension);
      $view->addExtension(new \Twig_Extension_Debug());

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
	// If you are in a development environment, I recommend disabling this
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

    'csrf' => function($c) {
        $guard = new \Slim\Csrf\Guard();

        $guard->setFailureCallable(function ($request, $response, $next) {
            $request = $request->withAttribute("csrf_status", false);
            if ($request->getAttribute('csrf_status') === false) {
                $data = [
                    'status' => 400,
                    'error' => 'Bad Request',
                    'message' => "Failed CSRF Check"
                ];

                return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->write(json_encode($data));
            } else {
                return $next($request, $response);
            }
        });

        return $guard;
    }
];
```

## Framework
Here is a list of most of the packages that make this app work.
+ [Slim](https://packagist.org/packages/slim/slim)
    - 3.0
   - PHP micro framework. The complete inner-workings of the site.
+ [Slim/Flash](https://packagist.org/packages/slim/flash)
    - 0.1.0
    - Extension to Slim. This allows us to show messages until we refresh a page.
    - I use a slightly modified version of this so I can flash message now for the current request. See [this](https://github.com/slimphp/Slim-Flash/pull/14) for more details.  
+ [Slim/CSRF](http://assemble.io)
    - 0.6.0
    - Extension to Slim. This allows us to implement Cross-Site Request Forgery protection.
+ [Twig](https://packagist.org/packages/slim/csrf)
    - 1.18
    - Template Engine
+ [Slim/Twig-View](https://packagist.org/packages/slim/twig-view)
    - 2.0
    - This is Slim's extension of Twig (See above)
+ [Illuminate/Database (Eloquent ORM)](https://packagist.org/packages/illuminate/database)
    - 5.2
    - This handles all of our database queries and such.
+ [Carbon](https://packagist.org/packages/nesbot/carbon)
    - 1.21
    - A simple PHP API extension for DateTime.
+ [Violin](https://packagist.org/packages/alexgarrett/violin)
    - 2.2.2
    - This is how we validate all data in our forms.
+ [RandomLib](https://packagist.org/packages/ircmaxell/random-lib)
    - 1.1
    - A library for generating random numbers and strings of various strengths.
+ [Algolia](https://packagist.org/packages/algolia/algoliasearch-client-php)
	- 1.7
	- A search API


## License
This work is licensed under the MIT license. See [License File](LICENSE) for more information.

## Other
If you feel this project would suit your needs, feel free to use any or all of the code I have provided.  
