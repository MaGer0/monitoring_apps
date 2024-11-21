<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailStudentMonitoring extends Model
{
    use HasFactory;

    protected $table = 'detail_students_monitorings';

    protected $fillable = [
        'monitoring_id',
        'students_nisn',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'students_nisn', 'nisn');
    }
}
