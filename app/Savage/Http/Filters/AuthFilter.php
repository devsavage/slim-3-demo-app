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
            $this->redirectTo($response, 'auth.login');
        }

        return $next($request, $response);
    }

    // protected function getContainer() {
    //     return $this->site->getContainer();
    // }
    //
    // public function router() {
    //     return $this->getContainer()->router;
    // }
    //
    // public function redirectTo($response, $path) {
    //     return $response->withRedirect($this->router()->pathFor($path));
    // }
}
