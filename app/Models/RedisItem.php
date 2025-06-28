<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedisItem extends Model
{
    protected $table = 'redis_items';
    protected $fillable = ['key', 'value'];
    protected $casts = [
        'value' => 'array',
    ];
}
