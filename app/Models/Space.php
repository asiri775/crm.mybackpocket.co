<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Space extends Model
{
    use HasFactory;

    protected $table = 'bravo_spaces';

    protected $fillable = [
        'id',
        'title',
    ];

}
