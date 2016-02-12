<?php

namespace Savage\Http\Controllers;

class TestController extends Controller
{
    public function getIndex() {
         return $this->container->site->auth->notify("Test Again");
    }
}
