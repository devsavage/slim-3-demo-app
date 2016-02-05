<?php

// $site->get('/', function($request, $response, $args) {
//   return $this->view->render($response, 'home.twig');
// })->setName('home');

// $site->get('/flash', function($request, $response, $args) {
//   $this->flash->addMessage('global', 'Test Message');
//   return $response->withRedirect($this->router->pathFor('home'));
// });

// $site->get('/', function($request, $response, $args) {
//   call_user_func_array([new \Savage\Http\Controllers\HomeController($request, $response), 'index'], $args);
// })->setName('home');

$site->route(['GET'], '/', \Savage\Http\Controllers\HomeController::class)->setName('home');

$site->get('/auth/logout', function($request, $response, $args) use ($site) {
    \Savage\Http\Util\Session::delete($site->getContainer()->settings['auth']['session']);

    if(\Savage\Http\Util\Cookie::exists($site->getContainer()->settings['auth']['remember'])) {
      $site->auth->removeRememberCredentials();
      \Savage\Http\Util\Cookie::delete($site->getContainer()->settings['auth']['remember']);
    }

    return $response->withRedirect($site->getContainer()->router->pathFor('home'));
})->setName('auth.logout');

$site->group('/auth', function() {
    $this->authRoute(['GET', 'POST'], '/login', \Savage\Http\Controllers\AuthController::class, 'login')->add(new \Savage\Http\Filters\GuestFilter($this))->setName('auth.login');

    $this->authRoute(['GET', 'POST'], '/register', \Savage\Http\Controllers\AuthController::class, 'register')->add(new \Savage\Http\Filters\GuestFilter($this))->setName('auth.register');
});
