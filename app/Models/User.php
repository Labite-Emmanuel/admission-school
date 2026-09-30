<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_us';

    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_autority',
        'avatar',
        'first_name',
        'email',
        'username',
        'password',
        'nature',
        'statut',
        'role',
        'etat',
        'code_academic',
        'class',
        'end_registration',
        'code_parent',
        'login',
        'is_connect',
        'hour_connect',
        'over_connect',
        'academic_year',
        'open_school',
        'date_save',
        'token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'token',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_connect' => 'boolean',
            'open_school' => 'boolean',
            'hour_connect' => 'datetime',
            'over_connect' => 'datetime',
            // end_registration: string 'no' | 'process' | 'end', pas datetime
            'date_save' => 'datetime',
        ];
    }

    /**
     * Alias pour compatibilité (ex : notifications) — retourne first_name ou username.
     */
    public function getNameAttribute(): string
    {
        return $this->first_name ?? $this->username ?? '';
    }
}
