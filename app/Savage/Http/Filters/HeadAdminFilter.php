<?php
namespace Savage\Http\Filters;

use Slim\App as Site;

class HeadAdminFilter extends BasicFilter
{
    public function __invoke($request, $response, $next) {
        if(!$this->site->auth || !$this->site->auth->isHeadAdmin()) {
            if($this->json === true) {
                $data = [
                    'status' => 401,
                    'message' => "Access denied."
                ];

                return $response->withStatus($data['status'])->withHeader('Content-type', 'application/json')->write(json_encode($data));
            } else {
                $this->getContainer()->flash->addMessage("error", "Access Denied");
                return $this->redirectTo($response, 'home');
            }
        }

        return $next($request, $response);
    }
}
