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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('airline');
            $table->string('flight_number');
            $table->foreignId('departure_airport_id')->constrained('airports')->cascadeOnDelete();
            $table->foreignId('arrival_airport_id')->constrained('airports')->cascadeOnDelete();
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->timestamps();

            $table->unique(['airline','flight_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
