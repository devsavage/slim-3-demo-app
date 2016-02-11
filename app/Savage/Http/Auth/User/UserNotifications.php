<?php

namespace Savage\Http\Auth\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserNotifications extends Eloquent
{
    protected $table = 'notifications';

    protected $fillable = ['user_id', 'message', 'urgent'];
}
