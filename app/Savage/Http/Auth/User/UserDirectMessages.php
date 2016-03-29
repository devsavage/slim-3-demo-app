<?php

namespace Savage\Http\Auth\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserDirectMessages extends Eloquent
{
    protected $table = 'direct_messages';

    protected $fillable = ['subject', 'body', 'receiver_id', 'sender_id', 'has_new_reply', 'sender_viewed', 'receiver_viewed', 'receiver_deleted', 'sender_deleted'];

    // Well...

    public function setViewed($who) {
        if($who == 'receiver') {
            $this->update([
                'receiver_viewed' => true,
            ]);

            return true;
        } else if($who == 'sender') {
            $this->update([
                'sender_viewed' => true,
            ]);

            return true;
        }

        return false;
    }

    public function setDeleted($who) {
        if($who == 'receiver') {
            $this->update([
                'receiver_deleted' => true,
            ]);

            return true;
        } else if($who == 'sender') {
            $this->update([
                'sender_deleted' => true,
            ]);

            return true;
        }

        return false;
    }

    public function setHasReply($who) {
        if($who == 'receiver') {
            $this->update([
                'receiver_viewed' => true,
                'sender_viewed' => false,
            ]);

            return true;
        } else if($who == 'sender') {
            $this->update([
                'receiver_viewed' => false,
                'sender_viewed' => true,
            ]);

            return true;
        }

        return false;
    }

    public function sendMessage($toId, $fromId, $subject, $message) {
        $this->create([
            'receiver_id' => $toId,
            'sender_id' => $fromId,
            'subject' => $subject,
            'body' => $message,
        ]);

        return true;
    }
}
