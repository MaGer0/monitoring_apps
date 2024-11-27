<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\StudentResource;

class StudentController extends Controller
{
    public function import(Request $request)
    {
        Excel::import(new StudentsImport, $request->file, null, \Maatwebsite\Excel\Excel::XLSX);

        return 'Success!';
    }

    public function index()
    {
        $currentTeacher = Auth::user();

        $students = Student::query()->where('teachers_nik', $currentTeacher->nik)->paginate(3);

        return StudentResource::collection($students);
    }
}
