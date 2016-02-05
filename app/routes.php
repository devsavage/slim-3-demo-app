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

$site->group('/auth', function() {
    $this->authRoute(['GET', 'POST'], '/login', \Savage\Http\Controllers\AuthController::class, 'login')->setName('auth.login');
    $this->authRoute(['GET', 'POST'], '/register', \Savage\Http\Controllers\AuthController::class, 'register')->setName('auth.register');
});
