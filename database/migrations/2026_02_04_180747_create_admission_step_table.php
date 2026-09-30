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
        Schema::create('admission_step', function (Blueprint $table) {
            $table->id();
            $table->string('code_stud')->index();
            $table->string('step')->nullable();
            $table->string('term')->nullable();
            $table->string('academicyear')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_step');
    }
};
