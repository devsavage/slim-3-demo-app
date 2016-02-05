<?php

namespace Savage;

use Slim\App as Slim;

class Site extends Slim
{
  public function route(array $methods, $pattern, $controller) {
    return $this->map($methods, $pattern, function($request, $response, $args) use ($controller) {
      $callable = new $controller($request, $response, $this);
      return call_user_func_array([$callable, $request->getMethod()], $args);
    });
  }

  public function authRoute(array $methods, $pattern, $controller, $authType) {
    return $this->map($methods, $pattern, function($request, $response, $args) use ($controller, $authType) {
      $callable = new $controller($request, $response, $this);
      return call_user_func_array([$callable, $request->getMethod() . ucfirst($authType)], $args);
    });
  }
} 