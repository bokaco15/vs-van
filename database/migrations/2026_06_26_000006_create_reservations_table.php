<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->date('date'); // pun datum sa godinom
            $table->string('status')->default('booked'); // booked / blocked / ...
            $table->string('note')->nullable();
            $table->timestamps();

            // Isto vozilo ne može biti dva puta zauzeto istog dana.
            $table->unique(['vehicle_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
