<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Device extends Model
{
    use Notifiable;
    
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

    /**
     * 此设备所属的钱包地址
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * 确认此设备是否是 IOS 操作系统
     */
    public function isIOS()
    {
        return $this->os === static::OS_IOS;
    }

    /**
     * Get the notification routing information for the given driver.
     *
     * @param  string  $driver
     * @param  \Illuminate\Notifications\Notification|null  $notification
     * @return mixed
     */
    public function routeNotificationFor($driver, $notification = null)
    {
        return $this->fingerprint;
    }
}
