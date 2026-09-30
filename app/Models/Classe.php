<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classe extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name_classe',
        'Code',
        'description',
        'level',
        'id_subLevel',
        'aca_year',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function subLevel(): BelongsTo
    {
        return $this->belongsTo(SubLevel::class, 'id_subLevel');
    }

    public static function getClassesByCodeAndYear($classroom, $academicYear): ?self
    {
        return self::where('name_classe', $classroom)
            ->where('aca_year', $academicYear)
            ->first();
    }
}
