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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('filepassport')->nullable();
            $table->string('fileacademi')->nullable();
            $table->string('fileexams')->nullable();
            $table->string('filevacc')->nullable();
            $table->string('code_student')->nullable()->index();
            $table->string('code_academic')->nullable()->index();
            $table->date('date_enreg')->nullable();
            $table->string('heur_enreg')->nullable();
            $table->string('etat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};

