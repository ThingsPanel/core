<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->longText('configuration')->nullable();
            $table->longText('assigned_customers')->nullable();
            $table->string('search_text')->nullable();
            $table->string('title')->nullable();
            $table->string('business_id', 36)->nullable()->comment('业务id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard');
    }
}
