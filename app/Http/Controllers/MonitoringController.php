<?php

namespace App\Http\Controllers;

use App\Http\Resources\MonitoringResource;
use App\Models\DetailStudentMonitoring;
use App\Models\Monitoring;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $monitoring = Monitoring::all();
        return MonitoringResource::collection($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,keterangan']));
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

        return new MonitoringResource($monitoring->loadMissing("students"));
    }
}
