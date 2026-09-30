<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_behavioural', function (Blueprint $table) {
            $table->id();
            $table->string('code_student')->nullable()->index();
            $table->string('code_academic')->nullable()->index();
            $table->string('has_condition', 10)->nullable()->comment('yes/no');
            $table->boolean('learning_dyslexia')->nullable()->default(false);
            $table->boolean('learning_dyscalculia')->nullable()->default(false);
            $table->boolean('learning_add_adhd')->nullable()->default(false);
            $table->boolean('learning_autism_spectrum')->nullable()->default(false);
            $table->boolean('learning_speech_language')->nullable()->default(false);
            $table->boolean('learning_global_delay')->nullable()->default(false);
            $table->string('learning_other_specify')->nullable();
            $table->boolean('behaviour_group_setting')->nullable()->default(false);
            $table->boolean('behaviour_aggressive')->nullable()->default(false);
            $table->boolean('behaviour_impulsivity')->nullable()->default(false);
            $table->boolean('behaviour_emotional_social')->nullable()->default(false);
            $table->boolean('behaviour_sensory')->nullable()->default(false);
            $table->boolean('behaviour_toileting')->nullable()->default(false);
            $table->string('behaviour_other_specify')->nullable();
            $table->text('other_information')->nullable();
            $table->string('date_enreg')->nullable();
            $table->string('heur_enreg')->nullable();
            $table->unsignedTinyInteger('etat')->nullable()->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_behavioural');
    }
};
