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

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at?->format('d.m.Y H:i:s');
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at?->format('d.m.Y H:i:s');
    }
}
