<?php

namespace Savage\Http\Controllers;

class HomeController extends Controller
{
    public function get() {
        return $this->render('home', [
            'csrf_name' => $this->request->getAttribute('csrf_name'),
            'csrf_value' => $this->request->getAttribute('csrf_value'),
        ]);
    }
}