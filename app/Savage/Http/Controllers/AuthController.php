<?php

namespace Savage\Http\Controllers;

class AuthController extends Controller {
    public function get() {

    }

    public function getLogin() {
        return $this->render('auth/login');
    }

    public function postLogin() {
        // Handle login data
    }

    public function getRegister() {
        return $this->render('auth/register');
    }

    public function postRegister() {
        // Handle registration data
    }
}
