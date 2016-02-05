<?php

namespace Savage\Http\Validation;

use Savage\Http\Auth\User;
use Savage\Http\Util\Utils;
use Violin\Violin;

class Validator extends Violin
{
    protected $user;
    protected $utils;

    public function __construct(User $user, Utils $utils) {
        $this->user = $user;
        $this->hash = $utils;
    }
}
