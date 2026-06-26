<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            // 'van' ili 'car' — omogućava kasnije rent-a-car bez izmene strukture.
            $table->enum('type', ['van', 'car'])->default('van')->index();
            $table->string('name');                 // npr. "Teretni kombi Renault Trafic"
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable(); // npr. "2017. 2.0TDI"
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_recommended')->default(false); // "preporučeno"
            $table->boolean('is_featured')->default(false);    // "izdvojeno"
            $table->boolean('is_active')->default(true);       // prikaz na sajtu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
