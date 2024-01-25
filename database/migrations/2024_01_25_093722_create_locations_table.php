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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('latitude', 16, 14);
            $table->double('longitude', 16, 14);
            $table->foreignId('type_id')->constrained('location_types');
            $table->boolean('collects_caps')->default(0);
            $table->boolean('collects_bottles')->default(0);
            $table->boolean('collects_cans')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
