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
}