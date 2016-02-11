<?php

namespace Savage\Http\Controllers;

class TestController extends Controller
{
    public function getIndex() {
         var_dump($this->container->site->auth);
    }
}
