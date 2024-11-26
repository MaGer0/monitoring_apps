<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;


class StudentsImport implements ToModel, WithHeadingRow, WithSkipDuplicates, WithUpserts, WithBatchInserts
{
    protected $nisnList = [];

    public function model(array $row)
    {

        $this->nisnList[] = $row['nisn'];

        if (empty(array_filter($row))) {
            return null;
        }

        return new Student([
            'teachers_nik' => Auth::user()->nik,
            'nisn' => $row['nisn'],
            'name' => $row['nama'],
            'class' => $row['kelas'],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function uniqueBy()
    {
        return ['teachers_nik', 'nisn'];
    }

    public function __destruct()
    {
        $nisnList = array_filter($this->nisnList);
        Student::whereNotIn('nisn', $nisnList)->delete();
    }
}
