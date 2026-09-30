<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $table = 'level';

    protected $fillable = [
        'level_name',
        'description',
        'description_code',
        'status',
    ];

    public function subLevels(): HasMany
    {
        return $this->hasMany(SubLevel::class, 'id_level');
    }

    public function reqFiles(): HasMany
    {
        return $this->hasMany(ReqFile::class, 'id_level');
    }
}
