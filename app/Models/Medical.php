<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medical extends Model
{
    protected $table = 'medical';

    protected $fillable = [
        'code_student',
        'code_academic',
        'blood_group',
        'doctor_name',
        'doctor_contact',
        'any_recommandation',
        'medecine_health',
        'medication_school',
        'medication_list',
        'medication1',
        'medication2',
        'medication3',
        'medication4',
        'medication5',
        'medication6',
        'medication7',
        'medication8',
        'allergy',
        'allergy_reaction',
        'allergy_food',
        'allergy_insect',
        'allergy_medicine',
        'allergy_other',
        'allergy_resp_required',
        'other_medication_infos',
        'has_other_conditions',
        'other_med_info_file',
        'learning_difficulty',
        'learning_diff_file',
        'date_enreg',
        'heur_enreg',
        'etat',
    ];

    public function studentDetail(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class, 'code_student', 'code_student');
    }
}
