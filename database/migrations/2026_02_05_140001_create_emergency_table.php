<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_userE')->nullable()->index();
            $table->string('code_student')->nullable()->index();
            $table->string('code_academic')->nullable()->index();
            $table->string('emergency_name1')->nullable();
            $table->string('emergency_contact1')->nullable();
            $table->string('emergency_relation1')->nullable();
            $table->string('emergency_name2')->nullable();
            $table->string('emergency_contact2')->nullable();
            $table->string('emergency_relation2')->nullable();
            $table->string('date_enreg')->nullable();
            $table->string('heur_enreg')->nullable();
            $table->unsignedTinyInteger('etat')->nullable()->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency');
    }
};
