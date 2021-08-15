<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('business_id', 36)->nullable()->comment('业务ID');
            $table->string('name')->nullable()->comment('策略名称');
            $table->string('describe')->nullable()->comment('策略描述');
            $table->string('status')->nullable()->comment('策略状态');
            $table->text('config')->nullable()->comment('配置');
            $table->bigInteger('sort')->nullable();
            $table->bigInteger('type')->nullable();
            $table->string('issued', 20)->nullable();
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
        Schema::dropIfExists('conditions');
    }
}
