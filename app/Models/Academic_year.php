<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Academic_year extends Model
{
    protected $table = 'academic_year';

    protected $fillable = [
        'id_user',
        'nature',
        'academicyear',
        'photo',
        'last_name',
        'first_name',
        'codeFather',
        'codeMother',
        'codeGuardian',
        'class_current',
        'id_class',
        'level',
        'sublevel',
        'classroom',
        'class_section',
        'classroom_type',
        'registration',
        'code',
        'invoice',
        'registration_number',
        'registration_num',
        'admission_date',
        'number',
        'admission_number',
        'number_admission',
        'frais_scho',
        'statut',
        'etat',
        'migration',
        'actif',
        'reason_of_leaving',
        'more_reason_of_leaving',
    ];

    protected function casts(): array
    {
        return [
            'admission_date'     => 'date',
            // registration = statut texte (in_process, process_end, pending, rejected, etc.), pas un nombre
            'frais_scho'         => 'decimal:2',
            'registration_number'=> 'integer',
            'registration_num'   => 'integer',
            'number'             => 'integer',
            'admission_number'   => 'integer',
            'number_admission'   => 'integer',
            'etat'               => 'boolean',
            'migration'          => 'boolean',
            'actif'              => 'boolean',
        ];
    }
}

