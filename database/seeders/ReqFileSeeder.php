<?php

namespace Database\Seeders;

use App\Models\FileLevel;
use App\Models\Level;
use App\Models\ReqFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReqFileSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = DB::table('academicyear')->where('etat', 1)->value('year')
            ?? DB::table('academicyear')->value('year');

        if (!$academicYear) {
            $this->command->warn('Run AcademicYearSeeder first.');
            return;
        }

        $nursery = Level::where('description', 'Nursery School')->first();
        $primary = Level::where('description', 'Primary School')->first();
        $highSchool = Level::where('description', 'High School')->first();

        if (!$nursery || !$primary || !$highSchool) {
            $this->command->warn('Run LevelSeeder first.');
            return;
        }

        $vaccination = FileLevel::where('description_code', 'VR')->first();
        $passport = FileLevel::where('description_code', 'PCP')->first();
        $transcripts = FileLevel::where('description_code', 'Transcripts')->first();
        $transfer = FileLevel::where('description_code', 'Transfer')->first();

        if (!$vaccination || !$passport || !$transcripts || !$transfer) {
            $this->command->warn('Run FileLevelSeeder first.');
            return;
        }

        $rows = [
            // Nursery School
            ['academic_year' => $academicYear, 'id_level' => $nursery->id, 'id_file' => $vaccination->id],
            ['academic_year' => $academicYear, 'id_level' => $nursery->id, 'id_file' => $passport->id],
            // Primary School
            ['academic_year' => $academicYear, 'id_level' => $primary->id, 'id_file' => $passport->id],
            ['academic_year' => $academicYear, 'id_level' => $primary->id, 'id_file' => $vaccination->id],
            ['academic_year' => $academicYear, 'id_level' => $primary->id, 'id_file' => $transcripts->id],
            ['academic_year' => $academicYear, 'id_level' => $primary->id, 'id_file' => $transfer->id],
            // High School
            ['academic_year' => $academicYear, 'id_level' => $highSchool->id, 'id_file' => $passport->id],
            ['academic_year' => $academicYear, 'id_level' => $highSchool->id, 'id_file' => $vaccination->id],
            ['academic_year' => $academicYear, 'id_level' => $highSchool->id, 'id_file' => $transcripts->id],
            ['academic_year' => $academicYear, 'id_level' => $highSchool->id, 'id_file' => $transfer->id],
        ];

        foreach ($rows as $row) {
            ReqFile::updateOrCreate(
                [
                    'academic_year' => $row['academic_year'],
                    'id_level'      => $row['id_level'],
                    'id_file'       => $row['id_file'],
                ],
                $row
            );
        }
    }
}
