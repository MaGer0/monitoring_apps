<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class StudentsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Student::create([
                'nisn' => $row['nisn'],
                'name' => $row['nama'],
                'class' => $row['kelas'],
            ]);
        }
    }
}
