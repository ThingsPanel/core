<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_log', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('type', 36)->nullable();
            $table->longText('describe')->nullable();
            $table->string('data_id', 36)->nullable();
            $table->bigInteger('created_at')->nullable();
            $table->longText('detailed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_log');
    }
}
