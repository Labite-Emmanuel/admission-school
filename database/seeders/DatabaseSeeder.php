<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ClasseSeeder;
use Database\Seeders\NationalitySeeder;
use Database\Seeders\RelationshipSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed de base (ajustes selon ton besoin)
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            AcademicYearSeeder::class,
            LevelSeeder::class,
            SubLevelSeeder::class,
            ClasseSeeder::class,
            NationalitySeeder::class,
            RelationshipSeeder::class,
            FileLevelSeeder::class,
            ReqFileSeeder::class,
        ]);
    }
}
