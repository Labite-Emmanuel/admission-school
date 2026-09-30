<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FileLevel extends Model
{
    protected $table = 'fileLevel';

    protected $fillable = [
        'description',
        'description_code',
    ];

    public function reqFiles(): HasMany
    {
        return $this->hasMany(ReqFile::class, 'id_file');
    }
}
