<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMember extends Model
{
    use HasFactory;

    protected $table = 'chat_members';

    protected $fillable = [
        'chat_id',
        'user_id',
        'is_admin',
        'last_readed_at',
        'created_at',
        'updated_at'
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
