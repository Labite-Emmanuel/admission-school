<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commitment extends Model
{
    protected $table = 'Commitments';

    protected $fillable = [
        'id_userCM',
        'code_student',
        'code_academic',
        'aggree_one',
        'aggree_two',
        'aggree_three',
        'aggree_for',
        'aggree_five',
        'father_name',
        'father_contact',
        'mother_name',
        'mother_contact',
        'guardian_name',
        'guardian_contact',
        'date_end',
        'heur_end',
        'etat',
    ];

    public function studentDetail(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class, 'code_student', 'code_student');
    }
}
