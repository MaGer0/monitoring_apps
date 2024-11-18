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
        return MonitoringResource::collection($monitoring);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'detailMonitoring' => [
                'student_nisn' => 'requried',
                'keterangan' => 'required'
            ]
        ]);

        $monitoringPost['title'] = $validated['title'];
        $monitoringPost['description'] = $validated['description'];
        $monitoring = Monitoring::create($monitoringPost);

        foreach ($validated['detailMonitoring'] as $dsm) {
            $detailStudentMonitoring['monitoring_id'] = $monitoring['id'];
            $detailStudentMonitoring['student_nisn'] = $dsm['student_nisn'];
            $detailStudentMonitoring['keterangan'] = $dsm['keterangan'];
            DetailStudentMonitoring::create($detailStudentMonitoring);
        }

        return new MonitoringResource($monitoring);
    }
}
