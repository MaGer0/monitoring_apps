<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    // public function model(array $row)
    // {
    //     return new Student([
    //         'nisn'     => $row[0],
    //         'name'    => $row[1],
    //         'class'    => $row[2],
    //     ]);
    // }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Student::create([
                'nisn' => $row[0],
                'name' => $row[1],
                'class' => $row[2],
            ]);
        }
    }
}
