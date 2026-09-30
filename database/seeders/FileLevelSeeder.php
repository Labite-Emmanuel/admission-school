<?php

namespace Database\Seeders;

use App\Models\FileLevel;
use Illuminate\Database\Seeder;

class FileLevelSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['description' => 'Photocopy of birth certificate/Passport', 'description_code' => 'PCP'],
            ['description' => 'Photocopy of an updated vaccination records', 'description_code' => 'VR'],
            ['description' => 'Transcripts', 'description_code' => 'Transcripts'],
            ['description' => 'Transfer/Leaving Certificate', 'description_code' => 'Transfer'],
        ];

        foreach ($rows as $row) {
            FileLevel::updateOrCreate(
                ['description_code' => $row['description_code']],
                $row
            );
        }
    }
}
