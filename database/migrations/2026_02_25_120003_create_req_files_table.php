<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reqFiles', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year', 50)->nullable();
            $table->foreignId('id_level')->constrained('level')->cascadeOnDelete();
            $table->foreignId('id_file')->constrained('fileLevel')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reqFiles');
    }
};
