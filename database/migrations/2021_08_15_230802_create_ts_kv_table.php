<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTsKvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_kv', function (Blueprint $table) {
            $table->string('entity_type')->comment('类型：DEVICE');
            $table->string('entity_id', 36)->comment('设备id');
            $table->string('key')->comment('字段');
            $table->bigInteger('ts')->comment('毫秒时间戳');
            $table->string('bool_v', 5)->nullable();
            $table->longText('str_v')->nullable();
            $table->bigInteger('long_v')->nullable();
            $table->double('dbl_v')->nullable()->comment('数值');
            $table->primary(['entity_type', 'entity_id', 'key', 'ts']);
        });

        //set hypertable
        DB::statement("SELECT create_hypertable('ts_kv', 'ts', chunk_time_interval=>604800)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ts_kv');
    }
}
