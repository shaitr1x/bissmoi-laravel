<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('merchant_verification_requests', function (Blueprint $table) {
            $table->string('business_phone')->nullable();
            $table->boolean('has_physical_office')->nullable();
            $table->string('office_address')->nullable();
            $table->string('website_or_social')->nullable();
            $table->text('business_description')->nullable();
            $table->string('business_experience')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('merchant_verification_requests', function (Blueprint $table) {
            $table->dropColumn([
                'business_phone',
                'has_physical_office',
                'office_address',
                'website_or_social',
                'business_description',
                'business_experience',
            ]);
        });
    }
};
