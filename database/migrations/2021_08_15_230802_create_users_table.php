<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->bigInteger('created_at');
            $table->bigInteger('updated_at');
            $table->string('enabled', 5)->nullable();
            $table->longText('additional_info')->nullable();
            $table->string('authority')->nullable();
            $table->string('customer_id', 36)->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('search_text')->nullable();
            $table->bigInteger('email_verified_at');
            $table->rememberToken();
            $table->string('mobile', 20)->nullable();
            $table->string('remark', 100)->nullable();
            $table->bigInteger('is_admin')->nullable();
            $table->string('business_id', 36)->nullable()->comment('业务id');
            $table->string('wx_openid', 50)->nullable()->comment('微信openid');
            $table->string('wx_unionid', 50)->nullable()->comment('微信unionid');
        });

        //admin user
        DB::insert("INSERT INTO \"public\".\"users\"(\"id\", \"created_at\", \"updated_at\", \"enabled\", \"additional_info\", \"authority\", \"customer_id\", \"email\", \"password\", \"name\", \"first_name\", \"last_name\", \"search_text\", \"email_verified_at\", \"remember_token\", \"mobile\", \"remark\", \"is_admin\", \"business_id\", \"wx_openid\", \"wx_unionid\") VALUES ('9212e9fb-a89c-4e35-9509-0a15df64f45a', 1606099326, 1623490224, 't', NULL, NULL, NULL, 'admin@protonmail.com', '\$2y\$10\$ye5PLk3kU8HUgbXLAAJNV.puOJOEuWc40tbJwA4zjtv79nKvIiKke', 'Admin', NULL, NULL, NULL, 0, NULL, '18618000000', NULL, 0, '', '', '');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
