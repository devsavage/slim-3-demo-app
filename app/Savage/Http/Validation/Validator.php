<?php

namespace Savage\Http\Validation;

use Savage\Http\Auth\User;
use Savage\Http\Util\Utils;
use Violin\Violin;

class Validator extends Violin
{
    protected $user;
    protected $utils;
    protected $auth;

    public function __construct(User $user, Utils $utils, $auth = null) {
        $this->user = $user;
        $this->utils = $utils;
        $this->auth = $auth;

        $this->addFieldMessages([
            'username' => [
                'uniqueUsername' => 'This username is already in use.',
            ],

            'email' => [
                'uniqueEmail' => 'This e-mail is already in use.',
            ],

            'confirm_new_password' => [
                'matches' => 'Confirm New Password must match New Password.',
            ],

            'message-recipient' => [
                'required' => 'Recipient is required.'
            ],

            'message-subject' => [
                'required' => 'Message subject is required.'
            ],

            'message-body' => [
                'required' => 'Message body is required.'
            ],
        ]);

        $this->addRuleMessages([
            'matchesCurrentPassword' => 'Your current password is incorrect.',
            'validUsername' => 'The username you entered was not found.',
            'notAuthUsername' => 'Please enter a valid username.',
        ]);
    }

    public function validate_uniqueUsername($value, $input, $args) {
        return !(bool) $this->user->where('username', $value)->count();
    }

    public function validate_uniqueEmail($value, $input, $args) {
        $user = $this->user->where('email', $value);
        if($this->auth && $this->auth->email === $value) {
            return true;
        }

        return !(bool) $user->count();
    }

    public function validate_matchesCurrentPassword($value, $input, $args) {
        if($this->auth && $this->utils->verifyPassword($value, $this->auth->password)) {
            return true;
        }

        return false;
    }

    public function validate_validUsername($value, $input, $args) {
        if($this->user->where('username', $value)->first()) {
            return true;
        }

        return false;
    }

    public function validate_notAuthUsername($value, $input, $args) {
        if($this->auth && $this->auth->username !== $value) {
            return true;
        }

        return false;
    }
}
