<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Relationship;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relations = [
            'Père',
            'Mère',
            'Tuteur',
            'Oncle',
            'Tante',
            'Grand-parent',
        ];

        foreach ($relations as $description) {
            Relationship::create([
                'description' => $description,
            ]);
        }
    }
}

