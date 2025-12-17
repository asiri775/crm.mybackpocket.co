<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class LoginRequests extends Model
{
    use HasFactory;

    protected $table = 'login_requests';

    protected $fillable = [
        'user_id',
        'type',
        'token',
        'created_at',
        'updated_at'
    ];

    public static function getRequest($token, $type = 'crm')
    {
        return self::where('type', $type)->where('token', $token)->first();
    }

}
