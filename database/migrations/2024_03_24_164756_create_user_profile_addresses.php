<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profile_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('user_profile');
            $table->string('full_name');
            $table->string('phone');
            $table->string('country');
            $table->string('country_code');
            $table->string('city');
            $table->bigInteger('econt_city_id');
            $table->string('quarter')->nullable();
            $table->string('post_code');
            $table->string('street');
            $table->string('street_number')->nullable();
            $table->string('building_number')->nullable();
            $table->string('entrance')->nullable();
            $table->string('floor')->nullable();
            $table->string('apartment')->nullable();
            $table->boolean('primary')->default(false);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile_addresses');
    }
};
