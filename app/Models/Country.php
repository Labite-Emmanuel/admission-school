<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';

    protected $fillable = [
        'alpha2',
        'alpha3',
        'nom_en_gb',
        'nom_fr_fr',
    ];

    /**
     * Display name (French if available, else English).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->nom_fr_fr ?: $this->nom_en_gb ?: $this->alpha2 ?: (string) $this->id;
    }
}
