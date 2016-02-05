<?php

namespace Savage\Http\Controllers;

class AuthController extends Controller {
    public function get() {

    }

    public function getLogin() {
        return $this->render('auth/login');
    }

    public function postLogin() {
        if($this->data() === null) {
            $this->flash('error', 'Please fill out the fields!');
            return $this->response->withRedirect($this->router->pathFor('auth.login'));
        } else {
            $validator = $this->getValidator();
            $data = [
                'identifier' => $this->data()->identifier,
                'password' => $this->data()->password,
                'remember' => isset($this->data()->remember) ? 'on' : 'off',
            ];

            $this->getValidator()->validate([
                'identifier|E-mail or Username' => [$data['identifier'], 'required'],
                'password|Password' => [$data['password'], 'required'],
            ]);

            if($validator->passes()) {
                // Log the user in
                $user = $this->container->user->where('email', $data['identifier'])->orWhere('username', $data['identifier'])->first();

                if(!$user || !$this->container->util->verifyPassword($data['password'], $user->password)) {
                    $this->flashNow('error', 'The credentials you have entered are invalid.');
                    $this->flashNow('identifier', $data['identifier']);

                    return $this->render('auth/login', [
                        'errors' => $validator->errors()
                    ]);
                } else if($user && $this->container->util->verifyPassword($data['password'], $user->password)) {
                    \Savage\Http\Util\Session::set($this->container->settings['auth']['session'], $user->id);

                    if($data['remember'] === 'on') {
                        $rememberIdentifier = $this->container->util->genAlnumString(128);
                        $rememberToken = $this->container->util->genAlnumString(128);

                        $user->updateRememberCredentials($rememberIdentifier, $rememberToken);

                        \Savage\Http\Util\Cookie::set($this->container->settings['auth']['remember'], "{$rememberIdentifier}.{$rememberToken}", \Carbon\Carbon::now()->addWeek(2)->timestamp);
                    }

                    return $this->redirectTo('home');
                }

                return $this->redirectTo('home');
            } else {
                // Are we going to need to flash all previous data se we can keep it in the input field?
                foreach ($data as $key => $value) {
                    $this->flashNow($key, $value);
                }

                $this->flashNow('error', 'You have some errors with your registration, please fix them and try again.');

                return $this->render('auth/login', [
                    'errors' => $validator->errors()
                ]);
            }
        }
    }

    public function getRegister() {
        return $this->render('auth/register');
    }

    public function postRegister() {
        if($this->data() === null) {
            $this->flash('error', 'Please fill out the fields!');
            return $this->redirectTo('auth.register');
        } else {
            $validator = $this->getValidator();
            $data = [
                'first_name' => $this->data()->first_name,
                'last_name' => $this->data()->last_name,
                'username' => $this->data()->username,
                'email' => $this->data()->email,
                'password' => $this->data()->password,
                'confirm_password' => $this->data()->confirm_password,
            ];

            $this->getValidator()->validate([
                'first_name|First Name' => [$data['first_name'], 'required|max(20)'],
                'last_name|Last Name' => [$data['last_name'], 'required|max(20)'],
                'username|Username' => [$data['username'], 'required|alnumDash|max(25)|uniqueUsername'],
                'email|E-Mail' => [$data['email'], 'required|email|max(50)|uniqueEmail'],
                'password|Password' => [$data['password'], 'required|min(6)|max(75)'],
                'confirm_password|Confirm Password' => [$data['confirm_password'], 'required|matches(password)'],
            ]);

            if($validator->passes()) {
                // Add the user to database and send them to login
                $user = $this->container->user->create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => $this->container->util->hashPassword($data['password']),
                ]);

                $user->permissions()->create(\Savage\Http\Auth\Permission\UserPermissions::$defaults);
                $this->flash('success', 'You have been registered! You can now login!');
                return $this->redirectTo('auth.login');
            } else {
                // Are we going to need to flash all previous data se we can keep it in the input field?
                foreach ($data as $key => $value) {
                    $this->flashNow($key, $value);
                }

                $this->flashNow('error', 'You have some errors with your registration, please fix them and try again.');

                return $this->render('auth/register', [
                    'errors' => $validator->errors()
                ]);
            }
        }
    }
}
