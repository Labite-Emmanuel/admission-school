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
        Schema::create('academic_year', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('nature', 50)->nullable();
            $table->string('academicyear', 20)->nullable();
            $table->string('photo')->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('codeFather', 50)->nullable();
            $table->string('codeMother', 50)->nullable();
            $table->string('codeGuardian', 50)->nullable();
            $table->string('class_current', 50)->nullable();
            $table->unsignedBigInteger('id_class')->nullable();
            $table->string('level', 50)->nullable();
            $table->string('sublevel', 50)->nullable();
            $table->string('classroom', 50)->nullable();
            $table->string('class_section', 50)->nullable();
            $table->string('classroom_type', 50)->nullable();
            $table->string('registration',  50)->nullable();
            $table->string('code', 50)->nullable();
            $table->string('invoice', 100)->nullable();
            $table->integer('registration_number')->nullable();
            $table->integer('registration_num')->nullable();
            $table->date('admission_date')->nullable();
            $table->integer('number')->nullable();
            $table->integer('admission_number')->nullable();
            $table->integer('number_admission')->nullable();
            $table->decimal('frais_scho', 10, 2)->nullable();
            $table->string('statut', 50)->nullable();
            $table->boolean('etat')->default(true);
            $table->boolean('migration')->default(false);
            $table->boolean('actif')->default(true);
            $table->text('reason_of_leaving')->nullable();
            $table->text('more_reason_of_leaving')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_year');
    }
};

