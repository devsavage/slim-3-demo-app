<?php

namespace Savage\Http\Auth;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $table = 'users';

    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'remember_identifier', 'remember_token'];

    public function permissions() {
        return $this->hasOne('Savage\Http\Auth\Permission\UserPermissions', 'user_id');
    }

    public function hasPermission($permission) {
        return (bool)$this->permissions->{$permission};
    }

    public function isAdmin() {
        return $this->hasPermission('is_admin');
    }

    public function updateRememberCredentials($identifier, $token) {
        $this->update([
          'remember_identifier' => $identifier,
          'remember_token' => $token,
        ]);
    }

    public function removeRememberCredentials() {
        $this->updateRememberCredentials(null, null);
    }
}
