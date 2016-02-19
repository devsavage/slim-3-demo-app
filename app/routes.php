<?php

$site->route(['GET'], '/', \Savage\Http\Controllers\HomeController::class)->setName('home');
$site->authRoute(['GET'], '/tests', \Savage\Http\Controllers\TestController::class, 'index')->setName('tests');

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

    $this->authRoute(['GET'], '/notifications', \Savage\Http\Controllers\AuthController::class, 'notifications')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.notifications');

    $this->authRoute(['GET'], '/messages', \Savage\Http\Controllers\AuthController::class, 'directMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages');

    $this->authRoute(['GET'], '/messages/trash', \Savage\Http\Controllers\AuthController::class, 'trashedMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.trash');


    $this->authRoute(['GET', 'POST'], '/messages/view/{id}', Savage\Http\Controllers\AuthController::class, 'viewDirectMessage')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.view');

    $this->authRoute(['POST'], '/messages/compose', Savage\Http\Controllers\AuthController::class, 'composeMessage')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.compose');

    $this->authRoute(['POST'], '/messages/reply/{id}', Savage\Http\Controllers\AuthController::class, 'directMessageResponse')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.reply');

    // Temp Delete
    $this->authRoute(['POST'], '/messages/edit/trash', Savage\Http\Controllers\AuthController::class, 'trashDirectMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.edit.trash');

    // Restore
    $this->authRoute(['POST'], '/messages/edit/restore', Savage\Http\Controllers\AuthController::class, 'restoreDirectMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.edit.restore');

    // Delete Forever!
    $this->authRoute(['POST'], '/messages/edit/delete', Savage\Http\Controllers\AuthController::class, 'deleteDirectMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.edit.delete');

});

$site->group('/admin', function() {
    $this->route(['GET'], '/', \Savage\Http\Controllers\AdminController::class)->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.home');

    $this->authRoute(['GET'], '/users', \Savage\Http\Controllers\AdminController::class, 'users')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users');

    $this->authRoute(['GET', 'POST'], '/users/edit/{id}', \Savage\Http\Controllers\AdminController::class, 'userEdit')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users.edit');

    $this->authRoute(['POST'], '/users/edit/{id}/activate', \Savage\Http\Controllers\AdminController::class, 'userActivate')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users.activate');

    $this->authRoute(['POST'], '/users/edit/{id}/ban', \Savage\Http\Controllers\AdminController::class, 'userBan')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users.ban');

    $this->authRoute(['POST'], '/users/edit/{id}/delete', \Savage\Http\Controllers\AdminController::class, 'userDelete')->add(new \Savage\Http\Filters\HeadAdminFilter($this, true))->setName('admin.users.delete');

    $this->authRoute(['POST'], '/users/edit/{id}/promote/{type}', \Savage\Http\Controllers\AdminController::class, 'userPromote')->add(new \Savage\Http\Filters\HeadAdminFilter($this, true))->setName('admin.users.promote');
    $this->authRoute(['POST'], '/users/edit/{id}/demote/{type}', \Savage\Http\Controllers\AdminController::class, 'userDemote')->add(new \Savage\Http\Filters\HeadAdminFilter($this, true))->setName('admin.users.demote');
});

$site->group('/api', function() {
    $this->authRoute(['GET', 'POST'], '/notifications', Savage\Http\Controllers\ApiController::class, 'notifications')->setName('api.notifications');
    $this->authRoute(['GET'], '/messages', Savage\Http\Controllers\ApiController::class, 'directMessages')->setName('api.messages');
    $this->authRoute(['POST'], '/messages/delete', Savage\Http\Controllers\ApiController::class, 'deleteDirectMessage')->setName('api.messages.delete');
});
