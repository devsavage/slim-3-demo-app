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
        $user = $this->container->site->auth;

        if(!$user) return $this->response(['status' => 401, 'message' => 'Not authorized']);

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

    public function postNotifications() {
        $notificationId = $this->request->getParsedBody()['id'];

        $user = $this->container->site->auth;

        if(!$user) return $this->response(['status' => 401, 'message' => 'Not authorized']);

        if(!$notificationId) return $this->response(['status' => 422, 'message' => 'No id was provided.']);

        $targetNotification = $user->notifications()->where('id', $notificationId)->first();

        static $oldNotification;

        if($targetNotification) {
            $user->notifications()->where('id', $notificationId)->update([
                'viewed' => true,
            ]);

            // I don't really need to do this...
            $oldNotification = $targetNotification;

            $data = [
                'status' => 200,
                'message' => "You have marked that notification as read.",
                'notification' => $oldNotification,
            ];

            return $this->response($data);
        } else {
            $data = [
                'status' => 404,
                'message' => "The target notification was not found."
            ];

            return $this->response($data);
        }
    }

    private function response($data = []) {
        if(empty($data) || !isset($data['status'])) return;
        return $this->response->withHeader('Content-type', 'application/json')->withStatus($data['status'])->write(json_encode($data));
    }

    private function getEndpoint() {
        return str_replace("api", "", $this->request->getUri()->getPath());
    }
}
