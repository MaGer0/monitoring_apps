<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Monitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'teachers_nik',
        'title',
        'description',
        'date',
        'start_time',
        'end_time'
    ];

    public function students(): HasMany
    {
        return $this->hasMany(DetailStudentMonitoring::class, 'monitoring_id', 'id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teachers_nik', 'nik');
    }
}
