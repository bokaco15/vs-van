<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot tabela: jedno vozilo ima više specifikacija i obrnuto.
        Schema::create('feature_vehicle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feature_id')->constrained()->cascadeOnDelete();
            // Opciona vrednost po vozilu (npr. broj sedišta "8+1").
            $table->string('value')->nullable();
            $table->timestamps();

            $table->unique(['vehicle_id', 'feature_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_vehicle');
    }
};
