<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /**
     * 设备操作系统
     */
    const OS_ANDROID = 'android';
    const OS_IOS = 'ios';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'os',
        'address_id',
        'fingerprint',
    ];
}
