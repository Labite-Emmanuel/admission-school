<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReqFile extends Model
{
    protected $table = 'reqFiles';

    protected $fillable = [
        'academic_year',
        'id_level',
        'id_file',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'id_level');
    }

    public function fileLevel(): BelongsTo
    {
        return $this->belongsTo(FileLevel::class, 'id_file');
    }
}
