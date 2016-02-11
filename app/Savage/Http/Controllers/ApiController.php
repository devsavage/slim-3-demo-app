<?php

namespace Savage\Http\Controllers;

class ApiController extends Controller
{
    public function index() {
        $data = [
            'status' => 200,
            'message' => "Ok",
        ];

        return response($data);
    }

    public function getNotifications() {
        if(!$this->container->site->auth) return $this->response(['status' => 401, 'message' => 'Not authorized']);

        $data = [
            'status' => 200,
            'endpoint' => $this->getEndpoint(),
            'user' => $this->container->site->auth,
            'notifications' => $this->container->site->auth->notifications()->orderBy('id', 'DESC')->get(),
        ];

        return $this->response($data);
    }

    private function response($data = []) {
        if(empty($data) || !isset($data['status'])) return;
        return $this->response->withHeader('Content-type', 'application/json')->withStatus($data['status'])->write(json_encode($data));
    }

    private function getEndpoint() {
        return str_replace("api", "", $this->request->getUri()->getPath());
    }
}
