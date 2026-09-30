<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_pickup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_userC')->nullable()->index();
            $table->string('code_student')->nullable()->index();
            $table->string('code_academic')->nullable()->index();
            $table->string('pickup_name1')->nullable();
            $table->string('pickup_contact1')->nullable();
            $table->string('pickup_relation1')->nullable();
            $table->string('filepickup1')->nullable();
            $table->string('pickup_name2')->nullable();
            $table->string('pickup_contact2')->nullable();
            $table->string('pickup_relation2')->nullable();
            $table->string('filepickup2')->nullable();
            $table->string('pickup_name3')->nullable();
            $table->string('pickup_contact3')->nullable();
            $table->string('pickup_relation3')->nullable();
            $table->string('filepickup3')->nullable();
            $table->string('filepickup')->nullable();
            $table->string('date_enreg')->nullable();
            $table->string('heur_enreg')->nullable();
            $table->unsignedTinyInteger('etat')->nullable()->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_pickup');
    }
};
