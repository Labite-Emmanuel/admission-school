<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->string('code_student')->nullable()->index()->after('code_parent');
        });
    }

    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('code_student');
        });
    }
};
