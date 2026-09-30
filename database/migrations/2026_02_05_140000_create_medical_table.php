<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical', function (Blueprint $table) {
            $table->id();
            $table->string('code_student')->nullable()->index();
            $table->string('code_academic')->nullable()->index();
            $table->string('blood_group')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('doctor_contact')->nullable();
            $table->string('any_recommandation')->nullable();
            $table->string('medecine_health')->nullable();
            $table->string('medication_school')->nullable();
            $table->string('medication1')->nullable();
            $table->string('medication2')->nullable();
            $table->string('medication3')->nullable();
            $table->string('medication4')->nullable();
            $table->string('medication5')->nullable();
            $table->string('medication6')->nullable();
            $table->string('medication7')->nullable();
            $table->string('medication8')->nullable();
            $table->string('allergy')->nullable();
            $table->text('allergy_reaction')->nullable();
            $table->string('allergy_food')->nullable();
            $table->string('allergy_insect')->nullable();
            $table->string('allergy_medicine')->nullable();
            $table->string('allergy_other')->nullable();
            $table->string('allergy_resp_required')->nullable();
            $table->text('other_medication_infos')->nullable();
            $table->string('other_med_info_file')->nullable();
            $table->string('learning_difficulty')->nullable();
            $table->string('learning_diff_file')->nullable();
            $table->string('date_enreg')->nullable();
            $table->string('heur_enreg')->nullable();
            $table->unsignedTinyInteger('etat')->nullable()->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical');
    }
};
