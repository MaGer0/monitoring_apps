<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class StudentsImport implements ToModel, WithHeadingRow, WithSkipDuplicates, WithBatchInserts, WithUpserts
{
    public function model(array $row)
    {
        return new Student([
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
        return ['nisn'];
    }
}
