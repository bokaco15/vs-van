<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Key-value skladište za tekstualni sadržaj sajta (hero, kontakt, ...).
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();   // npr. "hero_title", "contact_phone"
            $table->text('value')->nullable();
            $table->string('group')->nullable(); // grupisanje u adminu (hero, kontakt...)
            $table->string('label')->nullable(); // čitljiv naziv polja u adminu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
