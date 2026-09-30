<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columnsToDrop = ['state_admission_test', 'code_class', 'code_subject', 'academic_code', 'sub_level'];
        foreach ($columnsToDrop as $column) {
            if (Schema::hasColumn('classes', $column)) {
                Schema::table('classes', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }

        if (!Schema::hasColumn('classes', 'id_subLevel')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->unsignedBigInteger('id_subLevel')->nullable()->after('description');
                $table->foreign('id_subLevel')->references('id')->on('subLevel')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['id_subLevel']);
            $table->dropColumn('id_subLevel');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->string('sub_level')->nullable()->after('description');
            $table->string('code_subject')->nullable();
            $table->string('code_class')->nullable();
            $table->string('state_admission_test')->nullable();
            $table->string('academic_code')->nullable();
        });
    }
};
