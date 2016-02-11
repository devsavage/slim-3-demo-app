<?php

namespace Savage\Http\Controllers;

class AdminController extends Controller
{
    public function get() {
        return $this->render('admin/home');
    }

    public function getUsers() {
        return $this->render('admin/users', [
            'users' => $this->container->user->get()
        ]);
    }

    public function getUserEdit() {
        $id = $this->request->getAttribute('id');
        $user = $this->container->user->where('id', $id)->first();

        if($user) {
            return $this->render('admin/userEdit', [
                'user' => $user
            ]);
        } else {
            $this->flash('error', "A user with the id {$id} was not found.");
            return $this->redirectTo('admin.users');
        }
    }

    public function postUserEdit() {
        $id = $this->request->getAttribute('id');
        $user = $this->container->user->where('id', $id)->first();
        $validator = $this->getValidator();

        if(!$user) {
            $this->flash('info', "User not found");
            return $this->redirectToUrl('admin.users');
        }

        if($this->data() == null) {
            $this->flash('info', "User data was null");
            return $this->redirectTo('admin.users');
        }

        $first_name = $this->data()->first_name;
        $last_name = $this->data()->last_name;
        $email = $this->data()->email;

        $validator->validate([
            'first_name|First Name' => [$first_name, 'required|max(20)'],
            'last_name|Last Name' => [$last_name, 'required|max(20)'],
            'email|E-Mail' => [$email, 'required|email|max(50)'],
        ]);

        if($validator->passes()) {
            $user->update([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
            ]);

            $this->flash("success", "{$user->username}'s account has been updated!");
            return $this->redirectTo('admin.users.edit', ['id' => $id]);
        } else {
            $this->flashNow('error', "There were some errors while trying to update {$user->username}'s profile, please fix them and try again.");

            return $this->render('admin/userEdit', [
                'user' => $user,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function postUserBan() {
        $id = $this->request->getAttribute('id');
        $user = $this->container->user->where('id', $id)->first();

        if(!$user) {
            $data = [
                'status' => 404,
                'error' => "Not Found",
                'message' => "User with id {$id} does not exist"
            ];

            return $this->response->withStatus(404)->withHeader('Content-type', 'application/json')->write(json_encode($data));
        }

        if(!(bool)$user->active) {
            $data = [
                'status' => 400,
                'error' => 'Bad Request',
                'message' => "{$user->username}'s account is already banned."
            ];

            return $this->response->withStatus(400)->withHeader('Content-type', 'application/json')->write(json_encode($data));
        } else {

            $user->update([
                'active' => false
            ]);

            $data = [
                'status' => 200,
                'message' => "{$user->username}'s account has been banned."
            ];

            return $this->response->withStatus(200)->withHeader('Content-type', 'application/json')->write(json_encode($data));
        }
    }

    public function postUserActivate() {
        $id = $this->request->getAttribute('id');
        $user = $this->container->user->where('id', $id)->first();

        if(!$user) {
            $data = [
                'status' => 404,
                'error' => "Not Found",
                'message' => "User with id {$id} does not exist"
            ];

            return $this->response->withStatus(404)->withHeader('Content-type', 'application/json')->write(json_encode($data));
        }

        if((bool)$user->active) {
            $data = [
                'status' => 400,
                'error' => 'Bad Request',
                'message' => "{$user->username}'s account is already activated."
            ];

            return $this->response->withStatus(400)->withHeader('Content-type', 'application/json')->write(json_encode($data));
        } else {

            $user->update([
                'active' => true
            ]);

            $data = [
                'status' => 200,
                'message' => "{$user->username}'s account has been activated."
            ];

            return $this->response->withStatus(200)->withHeader('Content-type', 'application/json')->write(json_encode($data));
        }
    }

    public function postUserDelete() {
        // To be safe
        static $username;

        $id = $this->request->getAttribute('id');
        $user = $this->container->user->where('id', $id)->first();

        if(!$user) {
            $data = [
                'status' => 404,
                'error' => "Not Found",
                'message' => "User with id {$id} either does not exist or they may have been deleted."
            ];

            return $this->response->withStatus(404)->withHeader('Content-type', 'application/json')->write(json_encode($data));
        } else {
            $username = $user->username;

            $user->delete();

            $data = [
                'status' => 200,
                'message' => "{$username}'s account has been deleted."
            ];

            return $this->response->withStatus(200)->withHeader('Content-type', 'application/json')->write(json_encode($data));
        }
    }

    public function postUserPromote() {
        $id = $this->request->getAttribute('id');
        $type = $this->request->getAttribute('type');
        $user = $this->container->user->where('id', $id)->first();

        if(!$user) {
            $data = [
                'status' => 404,
                'error' => "Not Found",
                'message' => "User with id {$id} does not exist."
            ];

            return $this->response->withStatus(404)->withHeader('Content-type', 'application/json')->write(json_encode($data));
        } else {
            switch($type) {
                case "admin":
                    if(!$user->isAdmin()) {
                        $user->promoteAdmin();

                        $data = [
                            'status' => 200,
                            'message' => "{$user->username} is now an administrator"
                        ];

                        return $this->response->withStatus($data['status'])->withHeader('Content-type', 'application/json')->write(json_encode($data));
                    } else {
                        $data = [
                            'status' => 422,
                            'message' => "{$user->username} is already an administrator"
                        ];

                        return $this->response->withStatus($data['status'])->withHeader('Content-type', 'application/json')->write(json_encode($data));
                    }
                    break;
                default:
                    $data = [
                        'status' => 400,
                        'message' => "Promotion type was not given."
                    ];

                    return $this->response->withStatus($data['status'])->withHeader('Content-type', 'application/json')->write(json_encode($data));
                    break;
            }
        }
    }

    public function postUserDemote() {
        $id = $this->request->getAttribute('id');
        $type = $this->request->getAttribute('type');
        $user = $this->container->user->where('id', $id)->first();

        if(!$user) {
            $data = [
                'status' => 404,
                'error' => "Not Found",
                'message' => "User with id {$id} does not exist."
            ];

            return $this->response->withStatus(404)->withHeader('Content-type', 'application/json')->write(json_encode($data));
        } else {
            switch($type) {
                case "admin":
                    if(!$user->isAdmin()) {
                        $user->demoteAdmin();

                        $data = [
                            'status' => 200,
                            'message' => "{$user->username} is no longer an administrator"
                        ];

                        return $this->response->withStatus($data['status'])->withHeader('Content-type', 'application/json')->write(json_encode($data));
                    } else {
                        $data = [
                            'status' => 422,
                            'message' => "{$user->username} is not an administrator"
                        ];

                        return $this->response->withStatus($data['status'])->withHeader('Content-type', 'application/json')->write(json_encode($data));
                    }
                    break;
                default:
                    $data = [
                        'status' => 400,
                        'message' => "Promotion type was not given."
                    ];

                    return $this->response->withStatus($data['status'])->withHeader('Content-type', 'application/json')->write(json_encode($data));
                    break;
            }
        }
    }
}
