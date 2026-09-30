<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('level', function (Blueprint $table) {
            $table->id();
            $table->string('level_name')->nullable();
            $table->text('description')->nullable();
            $table->string('description_code', 100)->nullable();
            $table->unsignedTinyInteger('status')->nullable()->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('level');
    }
};
