<?php
namespace Savage\Http\Filters;

use Slim\App as Site;

class AuthFilter extends BasicFilter
{
    protected $site;

    public function __construct(Site $site) {
        $this->site = $site;
    }

    public function __invoke($request, $response, $next) {
        if(!$this->site->auth) {
            $this->getContainer()->flash->addMessage("info", "Please login before viewing that page.");
            return $this->redirectTo($response, 'auth.login');
        }

        return $next($request, $response);
    }
}
