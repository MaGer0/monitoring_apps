<?php

namespace App\Http\Controllers;

use App\Http\Resources\MonitoringResource;
use App\Models\Monitoring;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $monitoring = Monitoring::create($validated);
        return new MonitoringResource($monitoring);
    }
}
