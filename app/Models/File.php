<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'filepassport',
        'fileacademi',
        'fileexams',
        'filevacc',
        'code_student',
        'code_academic',
        'date_enreg',
        'heur_enreg',
        'etat',
    ];

    protected function casts(): array
    {
        return [
            'date_enreg' => 'date',
        ];
    }
}

