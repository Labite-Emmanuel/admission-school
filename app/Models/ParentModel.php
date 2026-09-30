<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parents';

    public function getFirstNameAttribute(): ?string
    {
        return $this->attributes['fist_name'] ?? null;
    }

    protected $fillable = [
        'id_user',
        'civility',
        'person',
        'last_name',
        'fist_name',
        'phone',
        'home_phone',
        'personnal_phone',
        'work_phone',
        'other_phone',
        'whatsapp_phone',
        'email',
        'email2',
        'main_mobile',
        'other_mobile',
        'adress',
        'postal_code',
        'city',
        'country',
        'nationality',
        'main_language',
        'occupation',
        'enterprise',
        'enterprise_adress',
        'responsible_of_school_fees',
        'code_parent',
        'code_student',
        'update_infos',
    ];
}

