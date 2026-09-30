<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningBehavioural extends Model
{
    protected $table = 'learning_behavioural';

    protected $fillable = [
        'code_student',
        'code_academic',
        'has_condition',
        'learning_dyslexia',
        'learning_dyscalculia',
        'learning_add_adhd',
        'learning_autism_spectrum',
        'learning_speech_language',
        'learning_global_delay',
        'learning_other_specify',
        'behaviour_group_setting',
        'behaviour_aggressive',
        'behaviour_impulsivity',
        'behaviour_emotional_social',
        'behaviour_sensory',
        'behaviour_toileting',
        'behaviour_other_specify',
        'other_information',
        'date_enreg',
        'heur_enreg',
        'etat',
    ];

    protected $casts = [
        'learning_dyslexia' => 'boolean',
        'learning_dyscalculia' => 'boolean',
        'learning_add_adhd' => 'boolean',
        'learning_autism_spectrum' => 'boolean',
        'learning_speech_language' => 'boolean',
        'learning_global_delay' => 'boolean',
        'behaviour_group_setting' => 'boolean',
        'behaviour_aggressive' => 'boolean',
        'behaviour_impulsivity' => 'boolean',
        'behaviour_emotional_social' => 'boolean',
        'behaviour_sensory' => 'boolean',
        'behaviour_toileting' => 'boolean',
    ];

    public function studentDetail(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class, 'code_student', 'code_student');
    }
}
