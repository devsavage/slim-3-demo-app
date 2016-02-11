<?php
namespace Savage\Http\Filters;

use Slim\App as Site;

class BasicFilter
{
    protected $site;
    protected $json;

    public function __construct($site, $json = false) {
        $this->site = $site;
        $this->json = $json;
    }

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
