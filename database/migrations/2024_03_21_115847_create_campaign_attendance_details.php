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
        Schema::create('campaign_attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_attendance_id')->constrained('campaign_attendances');
            $table->boolean('caps_handover')->default(false);
            $table->boolean('bottles_handover')->default(false);
            $table->boolean('cans_handover')->default(false);
            $table->boolean('buying_consumables')->default(false);
            $table->boolean('campaign_labour')->default(false);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_attendance_details');
    }
};
