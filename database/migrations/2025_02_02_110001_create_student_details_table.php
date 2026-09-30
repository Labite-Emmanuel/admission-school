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
        Schema::create('StudentDetails', function (Blueprint $table) {
            $table->id('id_stud');
            $table->unsignedBigInteger('id_userS')->nullable()->index();
            $table->string('sexe')->nullable();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->date('birthday')->nullable();
            $table->string('birth_city')->nullable();
            $table->string('birth_country')->nullable();
            $table->string('nationality')->nullable();
            $table->string('first_lang')->nullable();
            $table->string('file')->nullable();
            $table->string('school')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->text('home_adress')->nullable();
            $table->string('mobile')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('external_student')->nullable();
            $table->string('code_student')->nullable()->index();
            $table->string('tb_father')->nullable();
            $table->string('tb_mother')->nullable();
            $table->string('tb_guardian')->nullable();
            $table->string('code_father')->nullable();
            $table->string('code_mother')->nullable();
            $table->string('code_guardian')->nullable();
            $table->string('code_academic')->nullable();
            $table->date('date_enreg')->nullable();
            $table->string('heur_enreg')->nullable();
            $table->string('etat_stud')->nullable();
            $table->string('trips_day_pickup')->nullable();
            $table->string('trips_day_dropoff')->nullable();
            $table->date('date_trips')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('StudentDetails');
    }
};
