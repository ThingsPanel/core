<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('name')->nullable();
            $table->bigInteger('created_at')->nullable();
            $table->string('app_type')->default('')->comment('应用类型');
            $table->string('app_id')->default('')->comment('app id');
            $table->string('app_secret')->default('')->comment('密钥');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business');
    }
}
