<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 地址绑定设备
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->comment('名称');
            $table->string('os', 50)->comment('操作系统');
            $table->unsignedInteger('address_id')->comment('地址 ID');
            $table->string('fingerprint', 150)->comment('唯一ID/指纹');
            $table->timestamps();

            $table->unique(['address_id', 'fingerprint']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
