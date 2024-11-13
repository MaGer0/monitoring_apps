<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TeachersExport;
use App\Http\Resources\teacherResource;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    // public function export()
    // {
    //     return Excel::download(new TeachersExport, 'teacher.xlsx');
    // }

    public function me()
    {
        $currentTeacher = Auth::user();

        return new TeacherResource($currentTeacher);
    }
}
