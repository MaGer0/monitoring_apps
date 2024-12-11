<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Storage;

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

        $students = Student::query()->where('teachers_nik', $currentTeacher->nik)->paginate(10);

        return StudentResource::collection($students);
    }

    public function search($value)
    {
        urldecode($value);

        $searchs = explode(' ', $value);

        $arraySearch = array_map(
            function ($string) {
                return "+" . $string . "*";
            },
            array_filter($searchs, function ($string) {
                return $string !== "";
            })
        );

        $search = implode(' ', $arraySearch);

        $currentTeacher = Auth::user();

        $students = Student::select('*')
            ->where('teachers_nik', $currentTeacher->nik)
            ->whereRaw("MATCH(name, class) AGAINST(? IN BOOLEAN MODE)", [$search])
            ->get();

        return StudentResource::collection($students);
    }

    public function example()
    {
        return response()->download(public_path('files/Students_Format.xlsx'));
    }
}
