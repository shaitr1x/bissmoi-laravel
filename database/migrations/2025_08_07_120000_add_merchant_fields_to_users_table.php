<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('shop_name')->nullable();
            $table->string('merchant_nif')->nullable();
            $table->string('merchant_website')->nullable();
            $table->string('merchant_id_doc')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['shop_name', 'merchant_nif', 'merchant_website', 'merchant_id_doc']);
        });
    }
};
