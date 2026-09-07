<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'user_name',
        'device_id',
        'device_name',
        'manufacturer',
        'model',
        'cpu',
        'ram',
        'gpu',
        'storage',
        'windows_version',
        'last_seen',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];
}
