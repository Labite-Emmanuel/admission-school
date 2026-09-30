<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'code_student',
        'code_academic',
        'data',
        'etat',
    ];

    public function studentDetail(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class, 'code_student', 'code_student');
    }
}
