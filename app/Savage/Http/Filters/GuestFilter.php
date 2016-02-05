<?php
namespace Savage\Http\Filters;

use Slim\App as Site;

class GuestFilter extends BasicFilter
{
    protected $site;

    public function __construct(Site $site) {
        $this->site = $site;
    }

    public function __invoke($request, $response, $next) {
        if($this->site->auth) {
            return $this->redirectTo($response, 'home');
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
