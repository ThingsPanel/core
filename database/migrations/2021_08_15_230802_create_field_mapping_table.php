<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_mapping', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('device_id', 36)->nullable();
            $table->string('field_from')->nullable();
            $table->string('field_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('field_mapping');
    }
}
