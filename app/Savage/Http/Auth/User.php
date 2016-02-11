<?php

namespace Savage\Http\Auth;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $table = 'users';

    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'remember_identifier', 'remember_token', 'active'];

    public function getFullName() {
        return "{$this->first_name} {$this->last_name}";
    }

    public function permissions() {
        return $this->hasOne('Savage\Http\Auth\Permission\UserPermissions', 'user_id');
    }

    public function hasPermission($permission) {
        return (bool)$this->permissions->{$permission};
    }

    public function isAdmin() {
        return $this->hasPermission('is_admin');
    }

    public function promoteAdmin() {
        return $this->permissions()->update([
            'is_admin' => true,
        ]);
    }

    public function demoteAdmin() {
        return $this->permissions()->update([
            'is_admin' => false,
        ]);
    }

    /**
     * I guess this could be optional.. this persion can promote/demote administratiors
     */
    public function isHeadAdmin() {
        return $this->hasPermission('is_head_admin');
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
