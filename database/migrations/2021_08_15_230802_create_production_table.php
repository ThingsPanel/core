<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->integer('type')->nullable()->comment('种植｜用药｜收获');
            $table->string('name')->nullable()->comment('字段名');
            $table->string('value')->nullable()->comment('值');
            $table->bigInteger('created_at')->nullable();
            $table->string('remark')->nullable()->comment('备注');
            $table->bigInteger('insert_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production');
    }
}
