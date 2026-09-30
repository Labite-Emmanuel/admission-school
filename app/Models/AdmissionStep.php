<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionStep extends Model
{
    protected $table = 'admission_step';

    protected $fillable = [
        'code_stud',
        'step',
        'term',
        'academicyear',
    ];
}
