<?php

namespace Savage\Http\Controllers;

use Savage\Http\Util\Session;

class Controller
{
    protected $request;
    protected $response;
    protected $container;

    public function __construct($request, $response, $container) {
        $this->request = $request;
        $this->response = $response;
        $this->container = $container;
    }

    public function render($name, array $args = []) {
        return $this->container->view->render($this->response, $name . '.twig', $args);
    }

    public function router() {
        return $this->container->router;
    }

    public function flash($type, $message) {
        return $this->container->flash->addMessage($type, $message);
    }

    public function flashNow($type, $message) {
        return $this->container->flash->addMessageNow($type, $message);
    }

    public function getValidator() {
        return $this->container->validator;
    }

    public function redirectTo($path, $urlParams = null, $append = null) {
        if($append !== null) {
            return $this->response->withRedirect($this->router()->pathFor($path) . $append);
        }

        if($urlParams !== null) {
            return $this->response->withRedirect($this->router()->pathFor($path, $urlParams));
        }

        return $this->response->withRedirect($this->router()->pathFor($path));
    }

    public function redirectToUrl($url) {
        return $this->response->withRedirect($url);
    }

    public function isLoggedIn() {
        if(Session::exists($this->container->settings['auth']['session']))
            return true;
        else {
            return false;
        }
    }

    public function getAuthUser() {
        if(Session::exists($this->container->settings['auth']['session']))
            return $this->container->user->where('id', Session::get($this->container->settings['auth']['session']));
        else {
            return null;
        }
    }

    public function data() {
        if(!empty($this->request->getParsedBody())) {
            return json_decode(json_encode($this->request->getParsedBody()));
        }

        return null;
    }
}
