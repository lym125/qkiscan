<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceResource;
use App\Models\Address;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
    /**
     * 获取 “钱包地址” 绑定的所有设备
     *
     * @param string $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($address)
    {
        $walletAddress = Address::where('address', $address)->first();

        if (null === $walletAddress) {
            throw new BusinessException('钱包地址未找到');
        }

        return response()->json([
            'code' => 0,
            'data' => DeviceResource::collection(
                $walletAddress->devices()->latest('id')->get()
            ),
            'message' => 'OK',
        ]);
    }

    /**
     * 为 “钱包地址” 绑定设备
     *
     * @param string $address
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($address, Request $request)
    {
        $this->validate($request, [
            'name' => ['bail', 'required', 'string', 'max:255'],
            'os' => [
                'bail',
                'required',
                'string',
                Rule::in([Device::OS_IOS, Device::OS_ANDROID]),
            ],
            'fingerprint' => ['bail', 'required', 'string', 'max:150'],
        ], [], [
            'name' => '设备名称',
            'os' => '操作系统',
            'fingerprint' => '设备指纹',
        ]);

        if (! is_address($address)) {
            throw new BusinessException('钱包地址格式不正确');
        }

        $walletAddress = Address::firstOrCreate(['address' => $address]);

        $device = Device::firstOrCreate([
            'address_id' => $walletAddress->id,
            'fingerprint' => $request->fingerprint,
        ], [
            'name' => $request->name,
            'os' => $request->os,
        ]);

        return response()->json([
            'code' => 0,
            'data' => DeviceResource::make($device),
            'message' => 'OK',
        ]);
    }

    /**
     * 删除 “钱包地址” 绑定的设备
     *
     * @param string $address
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($address, Request $request)
    {
        $this->validate($request, [
            'fingerprint' => ['bail', 'required', 'string', 'max:150'],
        ], [], [
            'fingerprint' => '设备指纹',
        ]);

        $walletAddress = Address::where('address', $address)->first();

        if (null === $walletAddress) {
            throw new BusinessException('钱包地址未找到');
        }

        $walletAddress->devices()->where('fingerprint', $request->fingerprint)->delete();

        return response()->json([
            'code' => 0,
            'data' => '',
            'message' => 'OK',
        ]);
    }
}
