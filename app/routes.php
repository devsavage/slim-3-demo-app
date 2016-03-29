<?php

$site->route(['GET'], '/', \Savage\Http\Controllers\HomeController::class)->setName('home');
$site->route(['GET'], '/tests', \Savage\Http\Controllers\TestController::class, 'index')->setName('tests');
$site->route(['GET'], '/fakesomedata', \Savage\Http\Controllers\FakeController::class, 'index')->setName('fake.faker');

$site->get('/auth/logout', function($request, $response, $args) use ($site) {
    \Savage\Http\Util\Session::delete($site->getContainer()->settings['auth']['session']);

    if(\Savage\Http\Util\Cookie::exists($site->getContainer()->settings['auth']['remember'])) {
      $site->auth->removeRememberCredentials();
      \Savage\Http\Util\Cookie::delete($site->getContainer()->settings['auth']['remember']);
    }

    return $response->withRedirect($site->getContainer()->router->pathFor('home'));
})->setName('auth.logout');

$site->group('/auth', function() {
    $this->route(['GET', 'POST'], '/login', \Savage\Http\Controllers\AuthController::class, 'login')->add(new \Savage\Http\Filters\GuestFilter($this))->setName('auth.login');

    $this->route(['GET', 'POST'], '/register', \Savage\Http\Controllers\AuthController::class, 'register')->add(new \Savage\Http\Filters\GuestFilter($this))->setName('auth.register');

    $this->route(['GET'], '/settings', \Savage\Http\Controllers\AuthController::class, 'settings')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.settings');

    $this->route(['GET', 'POST'], '/settings/update/profile', \Savage\Http\Controllers\AuthController::class, 'profile')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.update.profile');

    $this->route(['GET', 'POST'], '/settings/update/password', \Savage\Http\Controllers\AuthController::class, 'password')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.update.password');

    $this->route(['GET'], '/notifications', \Savage\Http\Controllers\AuthController::class, 'notifications')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.notifications');

    $this->route(['GET'], '/messages', \Savage\Http\Controllers\AuthController::class, 'directMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages');

    $this->route(['GET'], '/messages/sent', \Savage\Http\Controllers\AuthController::class, 'sentMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.sent');

    $this->route(['GET'], '/messages/trash', \Savage\Http\Controllers\AuthController::class, 'trashedMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.trash');


    $this->route(['GET', 'POST'], '/messages/view/{id}', Savage\Http\Controllers\AuthController::class, 'viewDirectMessage')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.view');

    $this->route(['POST'], '/messages/compose', Savage\Http\Controllers\AuthController::class, 'composeMessage')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.compose');

    $this->route(['POST'], '/messages/reply/{id}', Savage\Http\Controllers\AuthController::class, 'directMessageResponse')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.reply');

    // Temp Delete
    $this->route(['POST'], '/messages/edit/trash', Savage\Http\Controllers\AuthController::class, 'trashDirectMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.edit.trash');

    // Restore
    $this->route(['POST'], '/messages/edit/restore', Savage\Http\Controllers\AuthController::class, 'restoreDirectMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.edit.restore');

    // Delete Forever!
    $this->route(['POST'], '/messages/edit/delete', Savage\Http\Controllers\AuthController::class, 'deleteDirectMessages')->add(new \Savage\Http\Filters\AuthFilter($this))->setName('auth.messages.edit.delete');

});

$site->group('/admin', function() {
    $this->route(['GET'], '/', \Savage\Http\Controllers\AdminController::class)->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.home');

    $this->route(['GET'], '/users', \Savage\Http\Controllers\AdminController::class, 'users')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users');

    $this->route(['GET', 'POST'], '/users/edit/{id}', \Savage\Http\Controllers\AdminController::class, 'userEdit')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users.edit');

    $this->route(['POST'], '/users/edit/{id}/activate', \Savage\Http\Controllers\AdminController::class, 'userActivate')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users.activate');

    $this->route(['POST'], '/users/edit/{id}/ban', \Savage\Http\Controllers\AdminController::class, 'userBan')->add(new \Savage\Http\Filters\AdminFilter($this))->setName('admin.users.ban');

    $this->route(['POST'], '/users/edit/{id}/delete', \Savage\Http\Controllers\AdminController::class, 'userDelete')->add(new \Savage\Http\Filters\HeadAdminFilter($this, true))->setName('admin.users.delete');

    $this->route(['POST'], '/users/edit/{id}/promote/{type}', \Savage\Http\Controllers\AdminController::class, 'userPromote')->add(new \Savage\Http\Filters\HeadAdminFilter($this, true))->setName('admin.users.promote');

    $this->route(['POST'], '/users/edit/{id}/demote/{type}', \Savage\Http\Controllers\AdminController::class, 'userDemote')->add(new \Savage\Http\Filters\HeadAdminFilter($this, true))->setName('admin.users.demote');
});

$site->group('/api', function() {
    $this->route(['GET'], '/users/usernames', Savage\Http\Controllers\ApiController::class, 'usernameList')->setName('api.users.usernames');
    $this->route(['GET', 'POST'], '/notifications', Savage\Http\Controllers\ApiController::class, 'notifications')->setName('api.notifications');
    $this->route(['GET'], '/messages', Savage\Http\Controllers\ApiController::class, 'directMessages')->setName('api.messages');
    $this->route(['POST'], '/messages/delete', Savage\Http\Controllers\ApiController::class, 'deleteDirectMessage')->setName('api.messages.delete');
});
