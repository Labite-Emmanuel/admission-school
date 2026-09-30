<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical', function (Blueprint $table) {
            $table->text('medication_list')->nullable()->after('medication_school');
            $table->string('has_other_conditions', 10)->nullable()->after('other_medication_infos');
        });
    }

    public function down(): void
    {
        Schema::table('medical', function (Blueprint $table) {
            $table->dropColumn(['medication_list', 'has_other_conditions']);
        });
    }
};
