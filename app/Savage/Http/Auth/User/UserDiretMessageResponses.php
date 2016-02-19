<?php

namespace Savage\Http\Auth\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserDiretMessageResponses extends Eloquent
{
    protected $table = 'direct_messages_responses';

    protected $fillable = ['message_id', 'body', 'sender_id'];

    public function addReply($messageId, $response, $fromId) {
        return $this->create([
            'message_id' => $messageId,
            'body' => $response,
            'sender_id' => $fromId,
        ]);
    }
}
