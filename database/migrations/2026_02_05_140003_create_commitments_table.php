<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Commitments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_userCM')->nullable()->index();
            $table->string('code_student')->nullable()->index();
            $table->string('code_academic')->nullable()->index();
            $table->unsignedTinyInteger('aggree_one')->nullable()->default(1);
            $table->unsignedTinyInteger('aggree_two')->nullable()->default(1);
            $table->unsignedTinyInteger('aggree_three')->nullable()->default(1);
            $table->unsignedTinyInteger('aggree_for')->nullable()->default(1);
            $table->string('father_name')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->string('date_end')->nullable();
            $table->string('heur_end')->nullable();
            $table->unsignedTinyInteger('etat')->nullable()->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Commitments');
    }
};
