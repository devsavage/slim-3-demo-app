<?php

namespace Savage\Http\Auth;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $table = 'users';

    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'remember_identifier', 'remember_token', 'active'];

    protected $hidden = ['password', 'remember_identifier', 'remember_token'];

    public function getFullName() {
        return "{$this->first_name} {$this->last_name}";
    }

    public function permissions() {
        return $this->hasOne('Savage\Http\Auth\Permission\UserPermissions', 'user_id');
    }

    public function notifications() {
        return $this->hasMany('Savage\Http\Auth\User\UserNotifications', 'user_id');
    }

    public function directMessages() {
        return $this->hasMany('Savage\Http\Auth\User\UserDirectMessages', 'receiver_id');
    }

    public function notify($message, $urgent = false) {
        return $this->notifications()->create([
            'message' => $message,
            'urgent' => $urgent,
        ]);
    }

    public function hasPermission($permission) {
        return (bool)$this->permissions->{$permission};
    }

    public function isAdmin() {
        // We want to check if a user may be a head admin so they can do things like normal admins can.
        return $this->hasPermission('is_admin') || $this->hasPermission('is_head_admin');
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

    public function getDirectMessages($deleted = false) {
        return $this->directMessages()
            ->join('users', 'direct_messages.sender_id', '=', 'users.id')
            ->select('direct_messages.*', 'users.username as sender_username', 'users.first_name as sender_first_name', 'users.last_name as sender_last_name')
            ->orderBy('direct_messages.created_at', 'DESC')
            ->where('deleted', $deleted)
            ->get();
    }
}
