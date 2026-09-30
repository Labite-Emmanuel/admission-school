<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nationality;

class NationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nationalities = [
            'Camerounaise',
            'Française',
            'Nigériane',
            'Ivoirienne',
            'Ghanéenne',
        ];

        foreach ($nationalities as $name) {
            Nationality::create([
                'name_nationality' => $name,
            ]);
        }
    }
}

