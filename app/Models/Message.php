<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatsTimestamps;

class Message extends Model
{
    use FormatsTimestamps;

    protected $fillable = [
        'name', 'email', 'message', 'image', 'user_ip',
    ];

    protected $appends = ['created_at_formatted', 'updated_at_formatted'];

}
