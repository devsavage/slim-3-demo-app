<?php

namespace Savage\Http\Controllers;

class AuthController extends Controller {
    public function get() {

    }

    public function getLogin() {
        return $this->render('auth/login');
    }

    public function postLogin() {
        var_dump($this->container);
        die();
    }

    public function getRegister() {
        return $this->render('auth/register');
    }

    public function postRegister() {
        if($this->data() === null) {
            $this->flash('error', 'Please fill out the fields!');
            return $response->withRedirect($this->router->pathFor('auth.register'));
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
                'username|Username' => [$data['username'], 'required|alnumDash|max(25)'],
                'email|E-Mail' => [$data['email'], 'required|email|max(50)'],
                'password|Password' => [$data['password'], 'required|min(6)|max(75)'],
                'confirm_password|Confirm Password' => [$data['confirm_password'], 'required|matches(password)'],
            ]);

            if($validator->passes()) {
                // Add the user to database and send them to login
                $this->flash('success', 'You have registered!');
                return $response->withRedirect($this->router->pathFor('auth.login'));
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
