# Slim 3 Demo App
This application will try to use most features of Slim 3.  
I am using Eloquent ORM for the database implementation. You can read more about Eloquent ORM [here](https://laravel.com/docs/master/eloquent).  
I have also included my config file within this project which you can find in **confg/container.config.php**  


## Installation
You will need to use [Composer](https://getcomposer.org/) as well as PHP 5.5 or newer.  
Then run the following in the folder **composer.json** is located.
```bash
$ composer install
```
This will install all of the required dependencies as well as autoload the other necessary files.

## Other

##### Here is a detailed version of the config file.
``` php
<?php
return [
    'settings' => [
      'displayErrorDetails' => true, //This is for local development so we can see Slim errors
      'viewTemplatesDirectory' => '../resources/views', //Twig views folder
      'mysql' => [
        'driver' => 'mysql',  //Our database driver
        'host' => '127.0.0.1', //Our database host ip
        'username' => 'dev', //Our database username
        'password' => 'dev', //Our database password
        'charset' => 'utf8', //Our database character set
        'collation' => 'utf8_unicode_ci', //Our database collation
      ],
    ],
    // Here is where we instantiate the Slim\Flash functions.
    'flash' => function() {
      return new \Slim\Flash\Messages;
    },
    
    // Here is where we instantiate the Slim\Csrf functions.
    'csrf' => function () {
      return new \Slim\Csrf\Guard;
    },

    // Here is where we instantiate the Slim\Views\Twig functions.
    'view' => function($c) {
      $view = new \Slim\Views\Twig($c['settings']['viewTemplatesDirectory']); // We pass in our templates directory that we set above in the settings

      // We add our Slim extensions to Twig
      $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
      ));

      // We add our Slim flash functions to Twig
      $view->getEnvironment()->addGlobal('flash', $c['flash']);
    
      return $view;
    },

     // We create our database details for use with Eloquent ORM.
    'db' => function($c) {
      // Create a new instance of Eloquent ORM database manager.
      $capsule = new Illuminate\Database\Capsule\Manager;

      // We add our default connection to the manager.
      $capsule->addConnection([
        'driver' => $c['settings']['mysql']['driver'],
        'host' => $c['settings']['mysql']['host'],
        'database' => 'addressbook',
        'username' => $c['settings']['mysql']['username'],
        'password' => $c['settings']['mysql']['password'],
        'charset' => $c['settings']['mysql']['charset'],
        'collation' => $c['settings']['mysql']['collation']
      ], 'default');
      // We return our manager so we can boot within our app/bootstrap.php class.
      return $capsule;
    }
];
```

##### Here is a detailed version of our app/bootstrap.php file. #####
``` php
<?php
session_start(); //Start our PHP session
define('INC_ROOT', dirname(__DIR__)); // Define our directory where all of our files are stored.

require_once INC_ROOT . '/vendor/autoload.php'; // Require in our autoloader

// Create our new Slim\App (I am using a modified Slim\App file for better control and more abilities)
$site = new \Savage\Site(new \Slim\Container(
  include '../config/container.config.php' // Include our config file
));

$site->add($site->getContainer()->csrf); // Add our csrf to the container
$site->add(new \Savage\Http\Middleware\CsrfMiddleware($site)); // Add our csrf middleware to the container

require 'routes.php'; // Add in our routes file

$site->getContainer()->db->bootEloquent(); // Finally bootup our database for use
```