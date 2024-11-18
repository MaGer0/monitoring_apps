<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailStudentMonitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitoring_id',
        'students_nisn',
        'keterangan',
    ];
}
