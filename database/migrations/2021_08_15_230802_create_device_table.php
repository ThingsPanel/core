<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('asset_id', 36)->nullable()->comment('资产id');
            $table->string('token')->nullable()->comment('安全key');
            $table->longText('additional_info')->nullable()->comment('存储基本配置');
            $table->string('customer_id', 36)->nullable();
            $table->string('type')->nullable()->comment('插件类型');
            $table->string('name')->nullable()->comment('插件名');
            $table->string('label')->nullable();
            $table->string('search_text')->nullable();
            $table->string('extension', 50)->nullable()->comment('插件( 目录名)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device');
    }
}
