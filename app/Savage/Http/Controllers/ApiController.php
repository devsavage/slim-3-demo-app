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

        $user = $this->container->site->auth;

        $data = [
            'status' => 200,
            'endpoint' => $this->getEndpoint(),
            'user' => $user,
            'notifications' => [
                'new' => $user->notifications()->where('viewed', false)->orderBy('urgent', 'DESC')->orderBy('created_at', 'DESC')->get(),
                'old' => $user->notifications()->where('viewed', true)->orderBy('created_at', 'DESC')->get(),
            ],
            'total' => $user->notifications()->count(),
            'unread' => $user->notifications()->where('viewed', '=', '0')->count(),
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
