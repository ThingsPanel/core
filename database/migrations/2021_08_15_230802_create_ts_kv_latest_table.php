<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTsKvLatestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_kv_latest', function (Blueprint $table) {
            $table->string('entity_type');
            $table->string('entity_id', 36);
            $table->string('key');
            $table->bigInteger('ts');
            $table->string('bool_v', 5)->nullable();
            $table->longText('str_v')->nullable();
            $table->bigInteger('long_v')->nullable();
            $table->double('dbl_v')->nullable();
            $table->primary(['entity_type', 'entity_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ts_kv_latest');
    }
}
