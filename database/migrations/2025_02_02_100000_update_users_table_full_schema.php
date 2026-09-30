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
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id('id_us');
            $table->unsignedBigInteger('id_autority')->nullable();
            $table->string('avatar')->nullable();
            $table->string('first_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('username')->nullable()->unique();
            $table->string('password');
            $table->string('nature')->nullable();
            $table->string('statut')->nullable();
            $table->string('role')->nullable();
            $table->string('etat')->nullable();
            $table->string('code_academic')->nullable();
            $table->string('class')->nullable();
            $table->string('end_registration', 20)->nullable(); // 'no' | 'process' | 'end'
            $table->string('code_parent')->nullable();
            $table->string('login')->nullable();
            $table->boolean('is_connect')->default(false);
            $table->timestamp('hour_connect')->nullable();
            $table->timestamp('over_connect')->nullable();
            $table->string('academic_year')->nullable();
            $table->boolean('open_school')->default(false);
            $table->timestamp('date_save')->nullable();
            $table->string('token', 100)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }
};
