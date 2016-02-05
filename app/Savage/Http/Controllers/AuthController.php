<?php

namespace Savage\Http\Controllers;

class AuthController extends Controller {
    public function get() {

    }

    public function getLogin() {
        return 'Auth->Login';
    }

    public function postLogin() {
        // Handle login data
    }

    public function getRegister() {
        return 'Auth->Register';
    }

    public function postRegister() {
        // Handle registration data
    }
}