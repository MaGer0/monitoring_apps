<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function import(Request $request)
    {
        Excel::import(new StudentsImport, $request->file, null, \Maatwebsite\Excel\Excel::XLSX);

        return 'Success!';
    }
}
