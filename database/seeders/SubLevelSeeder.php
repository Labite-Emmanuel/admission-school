<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\SubLevel;
use Illuminate\Database\Seeder;

class SubLevelSeeder extends Seeder
{
    public function run(): void
    {
        $primary = Level::where('description_code', 'PS')->first();
        $highSchool = Level::where('description_code', 'HS')->first();
        $nursery = Level::where('description_code', 'NS')->first();

        if (!$primary || !$highSchool || !$nursery) {
            $this->command->warn('Run LevelSeeder first so Level records exist.');
            return;
        }

        $rows = [
            // Primary
            ['description' => 'Lower Primary', 'description_code' => 'LPPS', 'id_level' => $primary->id],
            ['description' => 'Upper Primary', 'description_code' => 'UPPS', 'id_level' => $primary->id],
            // High School
            ['description' => 'Lower Secondary', 'description_code' => 'LSHS', 'id_level' => $highSchool->id],
            ['description' => 'Upper Secondary', 'description_code' => 'USHS', 'id_level' => $highSchool->id],
            ['description' => 'Advanced Secondary', 'description_code' => 'ASHS', 'id_level' => $highSchool->id],
            // Nursery
            ['description' => 'Junior Nursery', 'description_code' => 'JNNS', 'id_level' => $nursery->id],
            ['description' => 'Senior Nursery', 'description_code' => 'SNNS', 'id_level' => $nursery->id],
        ];

        foreach ($rows as $row) {
            SubLevel::updateOrCreate(
                ['description_code' => $row['description_code']],
                $row
            );
        }
    }
}
