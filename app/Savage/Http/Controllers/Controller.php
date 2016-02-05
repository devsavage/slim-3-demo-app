<?php

namespace Savage\Http\Controllers;

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
        $this->container->view->render($this->response, $name . '.twig', $args);
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

    public function redirectTo($path) {
        return $this->response->withRedirect($this->router()->pathFor($path));
    }

    public function data() {
        if(!empty($this->request->getParsedBody())) {
            return json_decode(json_encode($this->request->getParsedBody()));
        }

        return null;
    }
}
