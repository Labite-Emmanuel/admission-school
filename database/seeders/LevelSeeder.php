<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['level_name' => 'Nursery', 'description_code' => 'NS', 'description' => 'Nursery School', 'status' => 1],
            ['level_name' => 'Primary', 'description_code' => 'PS', 'description' => 'Primary School', 'status' => 1],
            ['level_name' => 'High School', 'description_code' => 'HS', 'description' => 'High School', 'status' => 1],
        ];

        foreach ($rows as $row) {
            Level::updateOrCreate(
                ['description_code' => $row['description_code']],
                $row
            );
        }
    }
}
