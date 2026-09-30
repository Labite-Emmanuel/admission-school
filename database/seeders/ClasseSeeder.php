<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\Level;
use App\Models\SubLevel;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    public function run(): void
    {
        $nurseryLevel = Level::where('description_code', 'NS')->first();
        $primaryLevel = Level::where('description_code', 'PS')->first();
        $highSchoolLevel = Level::where('description_code', 'HS')->first();

        if (!$nurseryLevel || !$primaryLevel || !$highSchoolLevel) {
            $this->command->warn('Run LevelSeeder first.');
            return;
        }

        $idSubLevelNs = SubLevel::where('id_level', $nurseryLevel->id)->first()?->id;
        $idSubLevelPs = SubLevel::where('id_level', $primaryLevel->id)->first()?->id;
        $idSubLevelHs = SubLevel::where('id_level', $highSchoolLevel->id)->first()?->id;

        $acaYear = '2025-2026';

        $rows = [
            ['PreN', 'PreN', 'Pre Nursery', $idSubLevelNs],
            ['Nursery', 'Nursery', 'Nursery', $idSubLevelNs],
            ['Reception', 'Reception', 'Reception', $idSubLevelNs],
            ['Y1', 'Y1', 'Year One', $idSubLevelPs],
            ['Y2', 'Y2', 'Year Two', $idSubLevelPs],
            ['Y3', 'Y3', 'Year Three', $idSubLevelPs],
            ['Y4', 'Y4', 'Year Four', $idSubLevelPs],
            ['Y5', 'Y5', 'Year Five', $idSubLevelPs],
            ['Y6', 'Y6', 'Year Six', $idSubLevelPs],
            ['Y7', 'Y7', 'Year Seven', $idSubLevelHs],
            ['Y8', 'Y8', 'Year Eight', $idSubLevelHs],
            ['Y9', 'Y9', 'Year Nine', $idSubLevelHs],
            ['Y10', 'Y10', 'Year Ten', $idSubLevelHs],
            ['Y11', 'Y11', 'Year Eleven', $idSubLevelHs],
            ['Y12', 'Y12', 'Year Twelve', $idSubLevelHs],
            ['Y13', 'Y13', 'Year Thirteen', $idSubLevelHs],
            ['SpcPr', 'SpcPr', 'Special Class Primary', $idSubLevelPs],
            ['SpcNs', 'SpcNs', 'Special Class Nursery', $idSubLevelNs],
            ['SpcHs', 'SpcHs', 'Special Class High School', $idSubLevelHs],
        ];

        foreach ($rows as [$code, $nameClasse, $description, $idSubLevel]) {
            Classe::updateOrCreate(
                ['Code' => $code, 'aca_year' => $acaYear],
                [
                    'name_classe'  => $nameClasse,
                    'Code'         => $code,
                    'description'  => $description,
                    'id_subLevel'  => $idSubLevel,
                    'aca_year'     => $acaYear,
                ]
            );
        }
    }
}
