<?php
namespace Savage\Http\Filters;

use Slim\App as Site;

class GuestFilter extends BasicFilter
{
    public function __invoke($request, $response, $next) {
        if($this->site->auth) {
            return $this->redirectTo($response, 'home');
        }

        return $next($request, $response);
    }
}
