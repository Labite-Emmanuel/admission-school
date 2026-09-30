<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Academic_year;
use App\Models\ParentModel;

class StudentDetail extends Model
{
    protected $table = 'StudentDetails';

    protected $primaryKey = 'id_stud';

    protected $fillable = [
        'id_userS',
        'sexe',
        'nom',
        'prenom',
        'birthday',
        'birth_city',
        'birth_country',
        'nationality',
        'first_lang',
        'file',
        'school',
        'id_type',
        'id_number',
        'home_adress',
        'mobile',
        'whatsapp',
        'email',
        'external_student',
        'code_student',
        'tb_father',
        'tb_mother',
        'tb_guardian',
        'code_father',
        'code_mother',
        'code_guardian',
        'code_academic',
        'date_enreg',
        'heur_enreg',
        'etat_stud',
        'trips_day_pickup',
        'trips_day_dropoff',
        'date_trips',
    ];

    protected function casts(): array
    {
        return [
            'birthday' => 'date',
            'date_enreg' => 'date',
            'date_trips' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_userS', 'id_us');
    }

    public function admissionStep(): HasOne
    {
        return $this->hasOne(AdmissionStep::class, 'code_stud', 'code_student');
    }

    public function files(): HasOne
    {
        return $this->hasOne(File::class, 'code_student', 'code_student');
    }

    public function medical(): HasOne
    {
        return $this->hasOne(Medical::class, 'code_student', 'code_student');
    }

    public function emergency(): HasOne
    {
        return $this->hasOne(Emergency::class, 'code_student', 'code_student');
    }

    public function childPickup(): HasOne
    {
        return $this->hasOne(ChildPickup::class, 'code_student', 'code_student');
    }

    public function commitment(): HasOne
    {
        return $this->hasOne(Commitment::class, 'code_student', 'code_student');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(Academic_year::class, 'code_academic', 'code');
    }

    public function father(): BelongsTo
    {
        return $this->belongsTo(ParentModel::class, 'code_father', 'code_parent');
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(ParentModel::class, 'code_mother', 'code_parent');
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(ParentModel::class, 'code_guardian', 'code_parent');
    }
}
