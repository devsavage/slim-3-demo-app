<?php
namespace Savage\Http\Filters;

use Slim\App as Site;

class AdminFilter extends BasicFilter
{
    public function __invoke($request, $response, $next) {
        if(!$this->site->auth || !$this->site->auth->isAdmin()) {
            $this->getContainer()->flash->addMessage("error", "Access Denied");
            return $this->redirectTo($response, 'home');
        }

        return $next($request, $response);
    }
}
