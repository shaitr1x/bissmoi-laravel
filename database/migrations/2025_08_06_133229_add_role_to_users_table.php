<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['client', 'merchant', 'admin'])->default('client');
            $table->boolean('merchant_approved')->default(false);
            $table->text('merchant_description')->nullable();
            $table->string('merchant_phone')->nullable();
            $table->text('merchant_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'merchant_approved', 'merchant_description', 'merchant_phone', 'merchant_address']);
        });
    }
};
