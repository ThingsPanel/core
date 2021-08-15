<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->integer('type')->nullable()->comment('1:业务  2：自动化-控制策略 3：自动化-告警策略  4：可视化');
            $table->string('name')->nullable();
            $table->string('data')->nullable();
            $table->integer('count')->nullable()->comment('数量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigation');
    }
}
