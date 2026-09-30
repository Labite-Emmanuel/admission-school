<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcademicYear extends Model
{
    protected $table = 'academicyear';

    protected $fillable = [
        'year',
        'start',
        'end',
        'etat',
    ];

    protected function casts(): array
    {
        return [
            'start' => 'date',
            'end' => 'date',
            'etat' => 'boolean',
        ];
    }

    public static function getLastAdmissionNumber()
    {
        return DB::table('academic_year')
            ->whereNotNull('number')
            ->orderByDesc('number')
            ->first();
    }
}
