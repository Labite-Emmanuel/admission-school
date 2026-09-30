<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('academicyear')->insert([
            [
                'year' => '2025-2026',
                'start' => '2025-09-01',
                'end' => '2026-08-31',
                'etat' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
