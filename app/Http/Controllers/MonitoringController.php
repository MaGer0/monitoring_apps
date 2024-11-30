<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Exports\MonitoringsExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MonitoringResource;
use Exception;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\error;
use function PHPSTORM_META\map;

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

    public function exportXLSX()
    {
        return (new MonitoringsExport)->forYear(2024)->download('Monitoring.xlsx', Excel::XLSX);
    }

    public function exportDOMPDF()
    {
        return (new MonitoringsExport)->forYear(2024)->download('Monitoring.pdf', Excel::DOMPDF, [
            'page_size' => 'A4',
            'orientation' => 'portrait',
            'fit_to_page' => true,
        ]);
    }

    public function index()
    {
        $currentTeacher = Auth::user();
        $monitorings = Monitoring::where('teachers_nik', $currentTeacher->nik)->latest()->paginate(3);

        $monitorings->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']);

        return MonitoringResource::collection($monitorings);
    }

    public function show($id)
    {
        $monitoring = Monitoring::findOrFail($id);

        return new MonitoringResource($monitoring->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']));
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request);

        if (isset($validated['image'])) {
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

    public function search($value)
    {;
        urldecode($value);

        $searchs = explode(' ', $value);

        $filteredArray = array_filter($searchs, function ($value) {
            return $value !== "";
        });

        $searchArray = array_map(function ($string) {
            return "+" . $string . "*";
        }, $filteredArray);

        $search = implode(' ', $searchArray);

        $currentTeacher = Auth::user();

        $monitorings = Monitoring::where('teachers_nik', $currentTeacher->nik)
            ->whereRaw("MATCH(title, description) AGAINST(? IN BOOLEAN MODE)", [$search])
            ->orderByRaw("MATCH(title) AGAINST(? IN BOOLEAN MODE) DESC", [$search])
            ->get();

        return MonitoringResource::collection($monitorings->loadMissing(['teacher:nik,name,email,password', 'students:id,monitoring_id,students_nisn,keterangan']))->response()->setStatusCode(200);
    }
}
