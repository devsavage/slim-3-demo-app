# Slim 3 Demo App
This application will try to use most features of Slim 3 as well as implement a few helpers along the way.  
I am using Eloquent ORM for the database implementation. You can read more about Eloquent ORM [here](https://laravel.com/docs/master/eloquent).  


## Current and Planned Features
| Feature        | Description           | Status  |
| ------------- |:-------------:| -----:|
| CSRF Protection      | Cross-Site Request Forgery protection | Feature Complete |
| Authenticatuion      | A fully featured authentication system | In Progress |

## Installation
You will need to use [Composer](https://getcomposer.org/) as well as PHP 5.5 or newer.  
Then run the following in the folder **composer.json** is located.
```bash
$ composer install
```
This will install all of the required dependencies as well as autoload the other necessary files.

## Framework
Here is a list of most of the packages that make this app work.
+ [Slim](https://packagist.org/packages/slim/slim)
    - 3.0
   - PHP micro framework. The complete inner-workings of the site.
+ [Slim/Flash](https://packagist.org/packages/slim/flash)
    - 0.1.0
    - Extension to Slim. This allows us to show messages until we refresh a page.
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


## License
This work is licensed under the MIT license. See [License File](LICENSE) for more information.

## Other
If you feel this project would suit your needs, feel free to use any or all of the code I have provided.  