<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Webkul\Lead\Models\LeadProxy;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';

    protected $fillable = [
        'uuid',
        'name',
        'is_group',
        'type',
        'last_message',
        'last_message_at',
        'user_id',
        'recipient_id',
        'lead_id',
        'created_at',
        'updated_at'
    ];

    public function lead()
    {
        return $this->belongsTo(LeadProxy::modelClass());
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function recipient()
    {
        return $this->belongsTo(Chat::class);
    }

    public function members()
    {
        return $this->hasMany(ChatMember::class, 'chat_id');
    }


}
