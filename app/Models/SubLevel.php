<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubLevel extends Model
{
    protected $table = 'subLevel';

    protected $fillable = [
        'description',
        'description_code',
        'id_level',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'id_level');
    }
}
