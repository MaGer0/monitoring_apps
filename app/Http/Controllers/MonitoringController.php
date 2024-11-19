<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailStudentMonitoring;
use App\Http\Resources\MonitoringResource;

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
            'teachers_nik' => 'required',
            'title' => 'required',
            'description' => 'required',
            'date' => 'required',
            'detailMonitoring' => 'required|array',
            'detailMonitoring.*.students_nisn' => 'required',
            'detailMonitoring.*.keterangan' => 'required'
        ]);

        $monitoringPost['teachers_nik'] = $validated['teachers_nik'];
        $monitoringPost['title'] = $validated['title'];
        $monitoringPost['description'] = $validated['description'];
        $monitoringPost['date'] = $validated['date'];

        $monitoring = Monitoring::create($monitoringPost);

        foreach ($validated['detailMonitoring'] as $dsm) {
            $detailStudentMonitoring['monitoring_id'] = $monitoring['id'];
            $detailStudentMonitoring['students_nisn'] = $dsm['students_nisn'];
            $detailStudentMonitoring['keterangan'] = $dsm['keterangan'];
            DetailStudentMonitoring::create($detailStudentMonitoring);
        }

        return new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'teachers_nik' => 'required',
            'title' => 'required',
            'description' => 'required',
            'date' => 'required',
            'detailMonitoring' => 'required|array',
            'detailMonitoring.*.students_nisn' => 'required',
            'detailMonitoring.*.keterangan' => 'required'
        ]);
        $monitoringUpdate['teachers_nik'] = $validated['teachers_nik'];
        $monitoringUpdate['title'] = $validated['title'];
        $monitoringUpdate['description'] = $validated['description'];
        $monitoringUpdate['date'] = $validated['date'];
        $monitoring = Monitoring::findOrFail($id);
        $monitoring->update($monitoringUpdate);

        return new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']));
    }

    public function destroy($id)
    {
        $monitoring = Monitoring::findOrFail($id);
        $monitoring->delete();

        return new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']));
    }
}
