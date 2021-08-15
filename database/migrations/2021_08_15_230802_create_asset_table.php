<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->longText('additional_info')->nullable();
            $table->string('customer_id', 36)->nullable()->comment('客户ID');
            $table->string('name')->nullable()->comment('名称');
            $table->string('label')->nullable()->comment('标签');
            $table->string('search_text')->nullable();
            $table->string('type')->nullable()->comment('类型');
            $table->string('parent_id', 36)->nullable()->comment('父级ID');
            $table->integer('tier')->comment('层级');
            $table->string('business_id', 36)->nullable()->comment('业务ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset');
    }
}
