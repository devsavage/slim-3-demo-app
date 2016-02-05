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
        $this->hash = $utils;
        $this->auth = $auth;

        $this->addFieldMessages([
            'username' => [
                'uniqueUsername' => 'This username is already in use.',
            ],

            'email' => [
                'uniqueEmail' => 'This e-mail is already in use.',
            ]
        ]);

        // $this->addRuleMessages([
        //
        // ]);
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
}
