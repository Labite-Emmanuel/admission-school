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
        Schema::create('academicyear', function (Blueprint $table) {
            $table->id();
            $table->string('year', 20)->index();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->boolean('etat')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academicyear');
    }
};
