<?php
namespace Savage\Http\Filters;

use Slim\App as Site;

class BasicFilter
{
    protected $site;

    public function __construct(Site $site) {
        $this->site = $site;
    }

    // public function __invoke($request, $response, $next) {
    //     return $next($request, $response);
    // }

    protected function getContainer() {
        return $this->site->getContainer();
    }

    public function router() {
        return $this->getContainer()->router;
    }

    public function redirectTo($response, $path) {
        return $response->withRedirect($this->router()->pathFor($path));
    }
}
