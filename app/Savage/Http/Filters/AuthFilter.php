<?php
namespace Savage\Http\Filters;

use Slim\App as Site;

class AuthFilter extends BasicFilter
{
    public function __invoke($request, $response, $next) {
        if(!$this->site->auth) {
            $this->getContainer()->flash->addMessage("info", "Please login before viewing that page.");
            return $this->redirectTo($response, 'auth.login');
        }

        return $next($request, $response);
    }
}
