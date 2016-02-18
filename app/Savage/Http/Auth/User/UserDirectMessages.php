<?php

namespace Savage\Http\Auth\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserDirectMessages extends Eloquent
{
    protected $table = 'direct_messages';

    protected $fillable = ['subject', 'body', 'receiver_id', 'sender_id', 'viewed', 'deleted'];

    public function sendMessage($toId, $fromId, $subject, $message) {
        return $this->create([
            'receiver_id' => $toId,
            'sender_id' => $fromId,
            'subject' => $subject,
            'body' => $message,
            'viewed' => false,
        ]);
    }
}
