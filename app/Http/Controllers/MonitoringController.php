<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailStudentMonitoring;
use App\Http\Resources\MonitoringResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
{
    private function validate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'image' => 'mimes:jpeg,jpg,png',
            'description' => 'required',
            'date' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i'
        ]);

        return $validated;
    }

    public function index()
    {
        $currentTeacher = Auth::user();
        $monitorings = $currentTeacher->monitorings;

        return MonitoringResource::collection($monitorings->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']));
    }

    public function show($id)
    {
        $monitoring = Monitoring::findOrFail($id);

        return new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']));
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request);

        if ($validated['image']) {
            $imageName = $this->generateRandomString();
            $extension = $validated['image']->extension();
            $image = $imageName . '.' . $extension;

            $validated['image']->storeAs('/images', $image, 'public');
            $validated['image'] = $image;
        }

        $validated['teachers_nik'] = Auth::user()->nik;

        $monitoring = Monitoring::create($validated);

        return (new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students'])))->response()->setStatusCode(201);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validate($request);

        $validated['teachers_nik'] = Auth::user()->nik;

        $monitoring = Monitoring::findOrFail($id);

        $monitoring->update($validated);

        return (new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan'])))->response()->setStatusCode(200);
    }

    public function updateImage(Request $request, $id)
    {
        $validated =  $request->validate(['image' => 'mimes:jpeg,jpg,png']);

        $imageName = $this->generateRandomString();
        $extension = $validated['image']->extension();
        $image = $imageName . '.' . $extension;

        $validated['image']->storeAs('/images', $image, 'public');
        $validated['image'] = $image;

        $monitoring = Monitoring::findOrFail($id);

        $monitoring->update($validated);

        return (new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan'])))->response()->setStatusCode(200);
    }

    public function destroy($id)
    {
        $monitoring = Monitoring::findOrFail($id);
        $monitoring->delete();

        return (new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan'])))->response()->setStatusCode(204);
    }

    public function destroyImage($id)
    {
        $monitoring = Monitoring::findOrFail($id);

        Storage::disk('public')->delete("images/" . $monitoring->image);
        $deleteImage = ['image' => null];

        $monitoring->update($deleteImage);
    }

    private function generateRandomString($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
