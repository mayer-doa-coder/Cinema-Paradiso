<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRequest extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message'
    ];

    /**
     * Get the sender of the request
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the request
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
