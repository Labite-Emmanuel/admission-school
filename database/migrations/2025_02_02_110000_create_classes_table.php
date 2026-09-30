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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name_classe')->nullable();
            $table->string('Code', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('sub_level')->nullable();
            $table->string('code_subject')->nullable();
            $table->string('code_class')->nullable();
            $table->string('academic_code')->nullable();
            $table->string('state_admission_test')->nullable();
            $table->string('aca_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
