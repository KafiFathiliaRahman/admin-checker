<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public const DEPARTMENTS = [
        'Bidang Pemerintahan Desa',
        'Bidang Pembangunan Ekonomi dan Pendapatan Desa',
        'Bidang Sarana Prasarana dan Kewilayahan',
        'Bidang Pemberdayaan Masyarakat Desa',
    ];

    protected $fillable = [
        'user_name',
        'department',
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
