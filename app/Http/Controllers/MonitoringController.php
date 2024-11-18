<?php

namespace App\Http\Controllers;

use App\Http\Resources\MonitoringResource;
use App\Models\DetailStudentMonitoring;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Validator;

class MonitoringController extends Controller
{
    public function index()
    {
        $monitoring = Monitoring::all();
        return MonitoringResource::collection($monitoring);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teachers_nik' => 'required',
            'title' => 'required',
            'description' => 'required',
            'detailMonitoring' => 'required|array',
            'detailMonitoring.*.students_nisn' => 'required',
            'detailMonitoring.*.keterangan' => 'required'
        ]);

        $monitoringPost['teachers_nik'] = $validated['teachers_nik'];
        $monitoringPost['title'] = $validated['title'];
        $monitoringPost['description'] = $validated['description'];
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
