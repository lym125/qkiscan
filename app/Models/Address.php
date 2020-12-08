<?php

namespace App\Models;

use ERC20\ERC20;
use EthereumRPC\EthereumRPC;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property string $address 地址
 * @property float $amount 余额
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $type 地址类型，1普通地址，2合约地址
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereType($value)
 */
class Address extends Model
{
    protected $table = "address";

    protected $fillable = [
        'address'
    ];

    const TYPE_NORMAL_ADDRESS = 1;
    const TYPE_CONTRACT_ADDRESS = 2;


    public function to()
    {
        return $this->hasMany(TokenTx::class, 'to_address_id');
    }

    public function from()
    {
        return $this->hasMany(TokenTx::class, 'from_address_id');
    }

    public function balances()
    {
        return $this->hasMany(Balances::class, 'address_id');
    }

    /**
     * 属于此地址的绑定设备
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
