<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emergency extends Model
{
    protected $table = 'emergency';

    protected $fillable = [
        'id_userE',
        'code_student',
        'code_academic',
        'emergency_name1',
        'emergency_contact1',
        'emergency_relation1',
        'emergency_name2',
        'emergency_contact2',
        'emergency_relation2',
        'date_enreg',
        'heur_enreg',
        'etat',
    ];

    public function studentDetail(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class, 'code_student', 'code_student');
    }
}
