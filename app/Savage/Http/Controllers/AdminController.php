<?php

namespace Savage\Http\Controllers;

class AdminController extends Controller
{
    public function get() {
        return $this->render('admin/home');
    }
}
