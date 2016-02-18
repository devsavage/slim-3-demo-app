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

    public function getDirectMessages() {
        $user = $this->container->site->auth;

        if(!$user) return $this->response(['status' => 401, 'message' => 'Not authorized']);

        $data = [
            'status' => 200,
            'user' => $user,
            'direct_messages' => $user->directMessages()
                ->join('users', 'direct_messages.sender_id', '=', 'users.id')
                ->select('direct_messages.*', 'users.username as sender_username', 'users.first_name as sender_first_name', 'users.last_name as sender_last_name')
                ->orderBy('direct_messages.created_at', 'DESC')
                ->where('deleted', false)
                ->get(),
            'total' => $user->directMessages()->count(),
        ];

        return $this->response($data);
    }

    public function postDeleteDirectMessage() {
        $user = $this->container->site->auth;

        $messageId = $this->request->getParsedBody()['id'];

        if(!$user) return $this->response(['status' => 401, 'message' => 'Not authorized']);

        $message = $user->directMessages()->where('id', $messageId)->first();

        if($message) {
            if($message->receiver_id === $user->id) {
                $message->update([
                    'deleted' => true,
                ]);

                return $this->response(['status' => 200, 'message' => 'Message has been deleted']);
            } else {
                return $this->response(['status' => 401, 'message' => 'You are not authorized to delete this message.']);
            }
        } else {
            return $this->response(['status' => 404, 'message' => 'The message you are looking for cannot be found.']);
        }
    }

    private function response($data = []) {
        if(empty($data) || !isset($data['status'])) return;

        $appendData = ['endpoint' => $this->getEndpoint()];

        return $this->response->withHeader('Content-type', 'application/json')->withStatus($data['status'])->write(json_encode(array_merge($appendData, $data)));
    }

    private function getEndpoint() {
        return str_replace("api", "", $this->request->getUri()->getPath());
    }
}
