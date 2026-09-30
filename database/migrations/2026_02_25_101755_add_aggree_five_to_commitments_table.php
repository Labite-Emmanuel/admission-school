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
        Schema::table('Commitments', function (Blueprint $table) {
            $table->unsignedTinyInteger('aggree_five')->nullable()->default(1)->after('aggree_for');
        });
    }

    public function down(): void
    {
        Schema::table('Commitments', function (Blueprint $table) {
            $table->dropColumn('aggree_five');
        });
    }
};
