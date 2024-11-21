<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailStudentMonitoring;
use App\Http\Resources\MonitoringResource;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    public function index()
    {
        $currentTeacher = Auth::user();
        $monitorings = $currentTeacher->monitorings;

        return MonitoringResource::collection($monitorings->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'date' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i'
        ]);

        $validated['teachers_nik'] = Auth::user()->nik;

        $monitoring = Monitoring::create($validated);

        return new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students']));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'date' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i'
        ]);
        $validated['teachers_nik'] = Auth::user()->nik;
        $monitoring = Monitoring::findOrFail($id);
        $monitoring->update($validated);


        return new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']));
    }

    public function destroy($id)
    {
        $monitoring = Monitoring::findOrFail($id);
        $monitoring->delete();

        return new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']));
    }
}
