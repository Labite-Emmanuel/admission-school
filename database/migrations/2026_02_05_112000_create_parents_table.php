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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->nullable()->index();
            $table->string('civility')->nullable();
            $table->string('person')->nullable();
            $table->string('last_name')->nullable();
            $table->string('fist_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('personnal_phone')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('other_phone')->nullable();
            $table->string('whatsapp_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('email2')->nullable();
            $table->string('main_mobile')->nullable();
            $table->string('other_mobile')->nullable();
            $table->text('adress')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('nationality')->nullable();
            $table->string('main_language')->nullable();
            $table->string('occupation')->nullable();
            $table->string('enterprise')->nullable();
            $table->text('enterprise_adress')->nullable();
            $table->string('responsible_of_school_fees')->nullable();
            $table->string('code_parent')->nullable()->index();
            $table->text('update_infos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};

