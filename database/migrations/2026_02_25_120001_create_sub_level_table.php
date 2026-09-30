<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subLevel', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('description_code', 100)->nullable();
            $table->foreignId('id_level')->constrained('level')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subLevel');
    }
};
