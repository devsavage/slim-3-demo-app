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
        return 'postUserEdit';
    }
}
