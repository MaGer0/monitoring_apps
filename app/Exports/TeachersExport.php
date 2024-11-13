<?php

namespace App\Exports;

use App\Models\Teacher;
use Illuminate\Container\Attributes\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;

class TeachersExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Teacher::all();
    }
}
