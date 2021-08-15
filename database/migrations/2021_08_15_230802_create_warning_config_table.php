<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarningConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warning_config', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('wid')->comment('业务ID');
            $table->string('name')->nullable()->comment('预警名称');
            $table->string('describe')->nullable()->comment('预警描述');
            $table->text('config')->nullable()->comment('配置');
            $table->text('message')->nullable()->comment('消息模板');
            $table->string('bid')->nullable()->comment('设备ID');
            $table->string('sensor', 100)->nullable();
            $table->string('customer_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warning_config');
    }
}
