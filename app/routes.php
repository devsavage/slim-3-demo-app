<?php

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

    $this->authRoute(['GET'], '/settings', \Savage\Http\Controllers\AuthController::class, 'settings')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.settings');

    $this->authRoute(['GET', 'POST'], '/settings/update/profile', \Savage\Http\Controllers\AuthController::class, 'profile')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.update.profile');

    $this->authRoute(['GET', 'POST'], '/settings/update/password', \Savage\Http\Controllers\AuthController::class, 'password')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.update.password');
});

$site->group('/admin', function() {
    $this->route(['GET'], '/', \Savage\Http\Controllers\AdminController::class)->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.home');

    $this->authRoute(['GET'], '/users', \Savage\Http\Controllers\AdminController::class, 'users')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users');

    $this->authRoute(['GET', 'POST'], '/users/edit/{id}', \Savage\Http\Controllers\AdminController::class, 'userEdit')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users.edit');

    $this->authRoute(['POST'], '/users/edit/{id}/activate', \Savage\Http\Controllers\AdminController::class, 'userActivate')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users.activate');

    $this->authRoute(['POST'], '/users/edit/{id}/ban', \Savage\Http\Controllers\AdminController::class, 'userBan')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users.ban');

    $this->authRoute(['POST'], '/users/edit/{id}/delete', \Savage\Http\Controllers\AdminController::class, 'userDelete')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users.delete');
});
