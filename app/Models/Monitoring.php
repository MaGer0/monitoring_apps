<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Monitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(DetailStudentMonitoring::class, 'monitoring_id', 'id');
    }
}
