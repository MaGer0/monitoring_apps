<?php

namespace App\Http\Controllers;

use App\Http\Resources\MonitoringResource;
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
            'description' => 'required'
        ]);

        $monitoringPost['title'] = $validated['title'];
        $monitoringPost['description'] = $validated['description'];
        $monitoring = Monitoring::create($monitoringPost);

        $detailStudentMonitoringPost['monitoring_id'] = $monitoring['id'];
        $detailStudentMonitoringPost['student'];

        return new MonitoringResource($monitoring);
    }
}
