<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $primaryKey = 'nik';

    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed'
        ];
    }

    public function monitorings(): HasMany
    {
        return  $this->hasMany(Monitoring::class, 'teachers_nik', 'nik');
    }
}
