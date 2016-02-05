<?php

namespace Savage\Http\Middleware;

use Slim\App as Site;

class CsrfMiddleware
{
    protected $site;

    public function __construct(Site $site) {
        $this->site = $site;
    }

    public function __invoke($request, $response, $next) {
        $view = $this->site->getContainer()->view->getEnvironment();

        $view->addGlobal('csrf_name', $request->getAttribute('csrf_name'));
        $view->addGlobal('csrf_token', $request->getAttribute('csrf_value'));

        return $next($request, $response);
    }
}