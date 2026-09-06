<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';

    protected $fillable = [
        'device_name',
        'device_os',
        'device_cpu',
        'device_gpu',
        'device_ram',
    ];
}