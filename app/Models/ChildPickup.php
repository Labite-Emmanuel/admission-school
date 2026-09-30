<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildPickup extends Model
{
    protected $table = 'child_pickup';

    protected $fillable = [
        'id_userC',
        'code_student',
        'code_academic',
        'pickup_name1',
        'pickup_contact1',
        'pickup_relation1',
        'filepickup1',
        'pickup_name2',
        'pickup_contact2',
        'pickup_relation2',
        'filepickup2',
        'pickup_name3',
        'pickup_contact3',
        'pickup_relation3',
        'filepickup3',
        'filepickup',
        'date_enreg',
        'heur_enreg',
        'etat',
    ];

    public function studentDetail(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class, 'code_student', 'code_student');
    }
}
