<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('dashboard_id', 36)->nullable();
            $table->longText('config')->nullable();
            $table->string('type')->nullable();
            $table->text('action')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('device_id', 36)->nullable()->comment('设备id');
            $table->string('widget_identifier')->nullable()->comment('图表标识符如: environmentpanel:normal');
            $table->string('asset_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget');
    }
}
